<?php
/**
 * Ashish Vegan MP3 Player
 * Modern Colorful Gradient Mobile Web App
 * Compatible with PHP 5.6+ and PHP 8.x
 */

// Error handling
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

$mp3Dir = __DIR__ . DIRECTORY_SEPARATOR . 'mp3-files';
$webMp3Dir = 'mp3-files';

// Scan mp3-files directory
$playlist = array();
if (is_dir($mp3Dir)) {
    $files = scandir($mp3Dir);
    // Filter only valid audio files
    $audioFiles = array();
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, array('mp3', 'm4a', 'wav', 'ogg', 'aac'))) {
            $audioFiles[] = $file;
        }
    }

    // Sort files in Ascending order (Natural sorting, case-insensitive)
    natcasesort($audioFiles);
    $audioFiles = array_values($audioFiles);

    // Format metadata for each song
    $index = 1;
    foreach ($audioFiles as $file) {
        $filePath = $mp3Dir . DIRECTORY_SEPARATOR . $file;
        $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
        
        // Format size
        if ($fileSize >= 1048576) {
            $formattedSize = number_format($fileSize / 1048576, 1) . ' MB';
        } elseif ($fileSize >= 1024) {
            $formattedSize = number_format($fileSize / 1024, 0) . ' KB';
        } else {
            $formattedSize = $fileSize . ' B';
        }

        // Clean song title and artist from filename
        $nameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
        $artist = 'Ashish Vegan';
        $title = $nameWithoutExt;

        // Check if filename has format: "01 - Artist - Title" or "01 - Title" or "Artist - Title"
        if (strpos($nameWithoutExt, ' - ') !== false) {
            $parts = explode(' - ', $nameWithoutExt);
            if (count($parts) >= 3) {
                $artist = trim($parts[1]);
                $title = trim($parts[2]);
            } elseif (count($parts) == 2) {
                // If first part is numeric track number
                if (is_numeric(trim($parts[0])) || preg_match('/^\d+$/', trim($parts[0]))) {
                    $title = trim($parts[1]);
                } else {
                    $artist = trim($parts[0]);
                    $title = trim($parts[1]);
                }
            }
        } else {
            // Strip leading track numbers like "01. " or "01_"
            $title = preg_replace('/^\d+[\s\._\-]+/', '', $nameWithoutExt);
        }

        $playlist[] = array(
            'id' => $index,
            'filename' => $file,
            'title' => $title,
            'artist' => $artist,
            'url' => $webMp3Dir . '/' . rawurlencode($file),
            'size' => $formattedSize,
            'bytes' => $fileSize,
            'album' => 'Vegan Soundwaves 2026'
        );
        $index++;
    }
}

// API endpoint to return JSON if requested
if (isset($_GET['api']) && $_GET['api'] === 'playlist') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array(
        'status' => 'success',
        'count' => count($playlist),
        'playlist' => $playlist
    ));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Ashish Vegan MP3 Player</title>
    <meta name="description" content="Ashish Vegan MP3 Player - Sleek Colorful Gradient Mobile Web App">
    <meta name="theme-color" content="#8b5cf6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Ashish Vegan MP3">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            neon: '#06ffd2'
                        }
                    },
                    animation: {
                        'spin-slow': 'spin 12s linear infinite',
                        'pulse-subtle': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'gradient-flow': 'gradientFlow 10s ease infinite',
                        'eq-bar-1': 'eqPulse 1.2s ease-in-out infinite',
                        'eq-bar-2': 'eqPulse 0.9s ease-in-out 0.2s infinite',
                        'eq-bar-3': 'eqPulse 1.4s ease-in-out 0.4s infinite',
                        'eq-bar-4': 'eqPulse 1.0s ease-in-out 0.1s infinite',
                    },
                    keyframes: {
                        gradientFlow: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        eqPulse: {
                            '0%, 100%': { height: '20%' },
                            '50%': { height: '95%' }
                        }
                    }
                }
            }
        };
    </script>

    <style>
        /* Modern Scrollbar styling */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Glassmorphism primitives */
        .glass-panel {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }
        .glass-card {
            background: rgba(18, 12, 38, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .glass-btn {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }
        .glass-btn:active {
            transform: scale(0.95);
        }

        /* Seek slider custom styling */
        .seek-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 6px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.18);
            outline: none;
            cursor: pointer;
            position: relative;
        }
        .seek-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 0 14px rgba(255, 255, 255, 0.9), 0 0 20px #8b5cf6;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .seek-slider::-webkit-slider-thumb:hover {
            transform: scale(1.35);
            box-shadow: 0 0 18px #06ffd2, 0 0 25px #c084fc;
        }
        .seek-slider::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 0 14px rgba(255, 255, 255, 0.9);
            cursor: pointer;
            border: none;
        }

        /* Vinyl Record Grooves */
        .vinyl-grooves {
            background: repeating-radial-gradient(
                circle at center,
                #111827 0,
                #111827 4px,
                #1f2937 5px,
                #111827 6px,
                #0f172a 7px
            );
        }

        /* Theme Gradients */
        .theme-aurora {
            background: linear-gradient(135deg, #0f172a 0%, #311042 35%, #0d3b4c 70%, #064e3b 100%);
        }
        .theme-sunset {
            background: linear-gradient(135deg, #2b0938 0%, #701a75 35%, #be123c 65%, #f97316 100%);
        }
        .theme-cyberpunk {
            background: linear-gradient(135deg, #09090b 0%, #3b0764 40%, #0891b2 75%, #06ffd2 100%);
        }
        .theme-electric {
            background: linear-gradient(135deg, #18002e 0%, #4c1d95 40%, #8b5cf6 70%, #ec4899 100%);
        }
        .theme-ocean {
            background: linear-gradient(135deg, #022c22 0%, #064e3b 30%, #0e7490 65%, #2563eb 100%);
        }

        /* Animation for Vinyl Spin Pause */
        .paused-spin {
            animation-play-state: paused !important;
        }

        /* Custom glow shadow */
        .glow-purple {
            box-shadow: 0 10px 35px -5px rgba(139, 92, 246, 0.45);
        }
        .glow-cyan {
            box-shadow: 0 10px 35px -5px rgba(6, 255, 210, 0.4);
        }
        .glow-pink {
            box-shadow: 0 10px 35px -5px rgba(236, 72, 153, 0.45);
        }
    </style>
</head>
<body class="h-full w-full bg-slate-950 font-sans text-slate-100 flex flex-col items-center justify-center p-0 sm:p-4 md:p-6 select-none overflow-x-hidden antialiased">

    <!-- Desktop Background Ambience (Visible on larger screens) -->
    <div class="fixed inset-0 pointer-events-none hidden sm:block overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-purple-600/25 rounded-full blur-3xl animate-pulse-subtle"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-pink-600/20 rounded-full blur-3xl animate-pulse-subtle" style="animation-delay: 1.5s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl animate-pulse-subtle" style="animation-delay: 2.5s;"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#ffffff08_1px,transparent_1px)] [background-size:24px_24px]"></div>
    </div>

    <!-- Desktop Viewport Mode Bar (Allows user to toggle between Phone Chassis & Fullscreen View on Desktop) -->
    <header class="w-full max-w-sm mb-3 px-3 py-1.5 hidden sm:flex items-center justify-between text-xs text-slate-400">
        <div class="flex items-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span class="font-semibold tracking-wide text-slate-300">Mobile UI/UX View</span>
        </div>
        <div class="flex items-center gap-2">
            <button id="toggleChassisBtn" class="hover:text-white transition-colors flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span id="chassisBtnText">Chassis: ON</span>
            </button>
        </div>
    </header>

    <!-- MOBILE PHONE CONTAINER -->
    <!-- On mobile screens: 100% full screen. On desktop: realistic flagship smartphone frame -->
    <main id="phoneContainer" class="relative w-full sm:max-w-[395px] h-full sm:h-[844px] sm:max-h-[92vh] sm:rounded-[48px] sm:shadow-2xl sm:shadow-purple-950/80 sm:border-[10px] sm:border-slate-800/90 overflow-hidden flex flex-col transition-all duration-300">

        <!-- Phone Notch / Dynamic Island (Only in phone chassis mode) -->
        <div id="phoneIsland" class="absolute top-2.5 left-1/2 -translate-x-1/2 z-50 hidden sm:flex items-center justify-between px-3 h-7 w-28 bg-black/90 rounded-full border border-white/10 shadow-lg pointer-events-none">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-900/60 border border-blue-400/40"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 animate-pulse"></span>
        </div>

        <!-- Phone App Outer Wrapper (Gradient Backdrop) -->
        <div id="appGradientBg" class="relative w-full h-full flex flex-col theme-electric transition-all duration-700 overflow-hidden">
            
            <!-- Dynamic Mesh Overlay (Gives the colorful gradient fluid luminescence) -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/80 pointer-events-none"></div>
            <div id="ambientAura" class="absolute -top-24 left-1/2 -translate-x-1/2 w-80 h-80 bg-purple-500/30 rounded-full blur-[70px] pointer-events-none transition-all duration-1000"></div>

            <!-- TOP STATUS BAR -->
            <div class="relative z-40 w-full pt-3 px-6 pb-2 flex items-center justify-between text-xs font-semibold tracking-tight text-white/90">
                <span id="statusBarClock" class="font-display">9:41</span>
                <div class="flex items-center space-x-2">
                    <!-- Cellular Signal -->
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 18.25A10.95 10.95 0 0 1 1 12C1 5.92 5.92 1 12 1s11 4.92 11 11c0 2.45-.8 4.71-2.16 6.55l-.62-.64C21.36 16.2 22 14.19 22 12c0-4.97-4.03-9-9-9z"/></svg>
                    <!-- Wifi -->
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 4C7.31 4 3.07 5.9 0 8.98L12 21 24 8.98A16.88 16.88 0 0 0 12 4zm0 2.5a14.28 14.28 0 0 1 10.22 4.22L12 18.57 1.78 10.72A14.28 14.28 0 0 1 12 6.5z"/></svg>
                    <!-- Battery -->
                    <div class="w-5 h-2.5 border border-white/80 rounded-sm p-0.5 flex items-center">
                        <div class="w-full h-full bg-emerald-400 rounded-2xs"></div>
                    </div>
                </div>
            </div>

            <!-- APP HEADER -->
            <header class="relative z-30 px-5 py-2 flex items-center justify-between">
                <!-- Theme Swapper Button -->
                <button id="themeToggleBtn" title="Change Gradient Theme" class="glass-btn w-9 h-9 rounded-full flex items-center justify-center text-white/90">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4 4 0 014-4 4 4 0 014 4 4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </button>

                <!-- App Title -->
                <div class="text-center flex-1 px-2">
                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-neon animate-ping"></span>
                        <span class="text-[10px] font-bold tracking-wider uppercase text-brand-neon">LIVE AUDIO</span>
                    </div>
                    <h1 class="text-base font-extrabold tracking-tight text-white font-display leading-tight drop-shadow-md">
                        Ashish Vegan MP3
                    </h1>
                </div>

                <!-- Open Playlist Drawer Button -->
                <button id="openPlaylistBtn" title="View Playlist" class="glass-btn w-9 h-9 rounded-full flex items-center justify-center text-white/90 relative">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    <span id="playlistBadgeCount" class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-pink-500 text-[9px] font-bold flex items-center justify-center text-white border border-white/40">
                        <?php echo count($playlist); ?>
                    </span>
                </button>
            </header>

            <!-- MAIN PLAYER BODY (SCROLLABLE ON SMALLER PHONE DISPLAYS) -->
            <section class="relative z-20 flex-1 flex flex-col items-center justify-between px-6 py-2 overflow-y-auto overflow-x-hidden">
                
                <!-- ALBUM ART / VINYL DISC DISPLAY -->
                <div class="relative w-full flex flex-col items-center justify-center my-auto">
                    <!-- Vinyl Record Behind Art Glow -->
                    <div class="relative w-56 h-56 sm:w-64 sm:h-64 flex items-center justify-center">
                        
                        <!-- Reactive Ambient Glow under Vinyl -->
                        <div id="vinylAuraGlow" class="absolute inset-0 rounded-full bg-gradient-to-tr from-purple-500/40 via-pink-500/30 to-cyan-400/40 blur-2xl transition-all duration-700"></div>

                        <!-- Spinning Vinyl Record -->
                        <div id="vinylRecord" class="relative w-52 h-52 sm:w-60 sm:h-60 rounded-full vinyl-grooves shadow-2xl border-4 border-slate-900/80 flex items-center justify-center animate-spin-slow paused-spin transition-transform duration-500">
                            
                            <!-- Vinyl Shiny Light Reflections -->
                            <div class="absolute inset-0 rounded-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white/15 via-transparent to-transparent pointer-events-none"></div>
                            <div class="absolute inset-0 rounded-full bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent pointer-events-none"></div>

                            <!-- Center Artwork Cover -->
                            <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden border-4 border-slate-950/80 shadow-inner flex items-center justify-center">
                                <div id="albumCoverBg" class="w-full h-full bg-gradient-to-tr from-violet-600 via-fuchsia-600 to-amber-400 flex flex-col items-center justify-center text-white text-center p-2">
                                    <svg class="w-8 h-8 text-white/90 mb-1 drop-shadow" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                                    <span id="discCenterText" class="text-[9px] font-black uppercase tracking-wider text-white/90 line-clamp-1">Ashish Vegan</span>
                                </div>
                                <!-- Center Spindle Hole -->
                                <div class="absolute w-6 h-6 rounded-full bg-slate-950 border-2 border-white/60 shadow-lg"></div>
                            </div>
                        </div>

                        <!-- Real-time Equalizer Wave Visualizer Bars Overlaid or Below Disc -->
                        <div id="visualizerCanvasWrap" class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-44 h-8 flex items-end justify-center gap-1 px-3 py-1 rounded-full bg-black/40 backdrop-blur-md border border-white/10">
                            <canvas id="visualizerCanvas" width="160" height="30" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

                <!-- SONG INFORMATION & ARTIST -->
                <div class="w-full text-center mt-3 mb-2 px-2">
                    <div class="flex items-center justify-center gap-2 mb-0.5">
                        <span id="trackNumberBadge" class="text-[10px] font-bold px-2 py-0.5 rounded bg-white/15 text-brand-neon tracking-wide border border-white/10">
                            #01 / <?php echo str_pad(count($playlist), 2, '0', STR_PAD_LEFT); ?>
                        </span>
                        <span id="sourceBadge" class="text-[10px] font-medium text-slate-300">
                            Ascending Order
                        </span>
                    </div>
                    <h2 id="currentSongTitle" class="text-xl font-extrabold text-white tracking-tight truncate font-display drop-shadow-sm">
                        <?php echo !empty($playlist) ? htmlspecialchars($playlist[0]['title']) : 'No MP3 Files Found'; ?>
                    </h2>
                    <p id="currentSongArtist" class="text-sm font-medium text-purple-200/80 truncate">
                        <?php echo !empty($playlist) ? htmlspecialchars($playlist[0]['artist']) : 'Add MP3s to mp3-files/'; ?>
                    </p>
                </div>

                <!-- SEEK BAR & TIMESTAMPS -->
                <div class="w-full space-y-1.5 px-1">
                    <div class="relative flex items-center">
                        <input id="seekBar" type="range" min="0" max="100" value="0" step="0.1" class="seek-slider">
                    </div>
                    <div class="flex items-center justify-between text-[11px] font-semibold text-white/70 font-mono tracking-wider">
                        <span id="currentTimeLabel">0:00</span>
                        <div class="flex items-center gap-1 text-[10px] text-brand-neon/80">
                            <span id="streamStatus">READY</span>
                        </div>
                        <span id="durationTimeLabel">0:00</span>
                    </div>
                </div>

                <!-- MAIN PLAYBACK CONTROLS -->
                <div class="w-full py-2">
                    <div class="flex items-center justify-between gap-1">
                        <!-- Shuffle Button -->
                        <button id="shuffleBtn" title="Toggle Shuffle" class="glass-btn w-10 h-10 rounded-full flex items-center justify-center text-white/70 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h4l4 6-4 6H4M20 4h-4l-2.5 3.75M20 20h-4l-4-6"/></svg>
                        </button>

                        <!-- Skip Backward 10s -->
                        <button id="skipBack10Btn" title="Rewind 10s" class="glass-btn w-9 h-9 rounded-full flex items-center justify-center text-white/80 hover:text-white text-xs font-bold">
                            -10
                        </button>

                        <!-- Previous Track -->
                        <button id="prevTrackBtn" title="Previous Track" class="glass-btn w-12 h-12 rounded-full flex items-center justify-center text-white hover:text-white">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg>
                        </button>

                        <!-- Main Play / Pause Hero Button -->
                        <button id="playPauseBtn" title="Play / Pause" class="relative group w-16 h-16 rounded-full bg-gradient-to-tr from-brand-500 to-pink-500 hover:from-brand-400 hover:to-pink-400 text-white flex items-center justify-center shadow-lg shadow-purple-900/60 transform transition-all duration-200 active:scale-95 border-2 border-white/30">
                            <div class="absolute inset-0 rounded-full bg-white/20 blur-sm group-hover:blur-md transition-all"></div>
                            <!-- Play Icon -->
                            <svg id="playIcon" class="w-7 h-7 fill-current translate-x-0.5" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <!-- Pause Icon (hidden by default) -->
                            <svg id="pauseIcon" class="w-7 h-7 fill-current hidden" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                        </button>

                        <!-- Next Track -->
                        <button id="nextTrackBtn" title="Next Track" class="glass-btn w-12 h-12 rounded-full flex items-center justify-center text-white hover:text-white">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/></svg>
                        </button>

                        <!-- Skip Forward 10s -->
                        <button id="skipForward10Btn" title="Forward 10s" class="glass-btn w-9 h-9 rounded-full flex items-center justify-center text-white/80 hover:text-white text-xs font-bold">
                            +10
                        </button>

                        <!-- Repeat Mode (Off, All, 1) -->
                        <button id="repeatBtn" title="Toggle Repeat" class="glass-btn w-10 h-10 rounded-full flex items-center justify-center text-white/70 hover:text-white relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span id="repeatIndicator" class="absolute -top-1 -right-1 text-[8px] font-black px-1 rounded-full bg-brand-neon text-slate-950 hidden">1</span>
                        </button>
                    </div>
                </div>

                <!-- SECONDARY CONTROLS: VOLUME & SPEED PILL -->
                <div class="w-full flex items-center justify-between gap-3 px-2 py-1 mb-2 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-md">
                    <!-- Volume Control -->
                    <div class="flex items-center gap-2 flex-1">
                        <button id="muteBtn" title="Mute/Unmute" class="text-white/80 hover:text-white p-1">
                            <svg id="volumeHighIcon" class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                            <svg id="volumeMutedIcon" class="w-4 h-4 fill-current hidden" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                        </button>
                        <input id="volumeSlider" type="range" min="0" max="1" step="0.01" value="0.85" class="seek-slider h-1">
                    </div>

                    <div class="h-4 w-[1px] bg-white/20"></div>

                    <!-- Playback Speed Button -->
                    <button id="speedBtn" title="Playback Speed" class="px-2.5 py-1 rounded-xl bg-white/10 hover:bg-white/20 text-[11px] font-bold font-mono text-white/90 border border-white/10 tracking-tight flex items-center gap-1">
                        <span id="speedLabel">1.0x</span>
                    </button>
                </div>

            </section>

            <!-- BOTTOM MINI DOCK / PULL-UP PLAYLIST BAR -->
            <footer class="relative z-30 w-full px-5 py-3.5 bg-slate-950/60 backdrop-blur-xl border-t border-white/10 flex items-center justify-between">
                <div id="footerTrackInfo" class="flex items-center gap-3 cursor-pointer flex-1 min-w-0 pr-2">
                    <div class="w-9 h-9 rounded-xl bg-brand-600/60 border border-white/20 flex items-center justify-center shadow-md flex-shrink-0">
                        <svg class="w-4 h-4 text-brand-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p id="miniTrackTitle" class="text-xs font-bold text-white truncate">
                            <?php echo !empty($playlist) ? htmlspecialchars($playlist[0]['title']) : 'Playlist'; ?>
                        </p>
                        <p class="text-[10px] text-slate-300 flex items-center gap-1">
                            <span>Tap to view Playlist</span>
                            <span class="text-brand-neon">▲</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Quick Playlist Open Pill -->
                    <button id="showPlaylistPillBtn" class="px-3 py-1.5 rounded-full bg-brand-500/30 hover:bg-brand-500/50 border border-brand-400/40 text-xs font-semibold text-brand-200 flex items-center gap-1.5 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                        <span>Queue (<?php echo count($playlist); ?>)</span>
                    </button>
                </div>
            </footer>

            <!-- PHONE HOME INDICATOR BAR (Mobile Standard UX) -->
            <div class="w-full pb-1 pt-1 flex justify-center bg-slate-950/70">
                <div class="w-32 h-1 rounded-full bg-white/30"></div>
            </div>

            <!-- SLIDE-UP PLAYLIST DRAWER (MODAL OVERLAY) -->
            <div id="playlistDrawer" class="absolute inset-0 z-50 bg-slate-950/95 backdrop-blur-2xl flex flex-col transform translate-y-full transition-transform duration-300 ease-out">
                
                <!-- Drawer Header -->
                <div class="p-5 pb-3 border-b border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-pink-500/30 border border-pink-400/40 flex items-center justify-center text-pink-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white font-display">Audio Playlist</h3>
                            <p class="text-[11px] text-slate-400">Sorted in Ascending Order (<span id="drawerCountText"><?php echo count($playlist); ?></span> tracks)</p>
                        </div>
                    </div>

                    <!-- Close Drawer Button -->
                    <button id="closePlaylistBtn" class="glass-btn w-8 h-8 rounded-full flex items-center justify-center text-white/80 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Playlist Search & Filter Bar -->
                <div class="px-5 py-2.5">
                    <div class="relative">
                        <input id="playlistSearchInput" type="text" placeholder="Search track or artist..." class="w-full pl-9 pr-4 py-2 rounded-xl bg-white/10 border border-white/15 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-brand-neon focus:ring-1 focus:ring-brand-neon">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Playlist Scrollable Items List -->
                <div id="playlistContainer" class="flex-1 overflow-y-auto px-4 py-2 space-y-1.5">
                    <!-- Populated dynamically by JavaScript -->
                </div>

                <!-- Local File Adder & Help Footer -->
                <div class="p-4 bg-slate-900/80 border-t border-white/10 space-y-2">
                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>Directory: <code class="text-brand-neon font-mono">mp3-files/</code></span>
                        <button id="refreshPlaylistBtn" class="hover:text-white flex items-center gap-1 text-[11px] text-brand-300 font-semibold">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Reload</span>
                        </button>
                    </div>
                    <!-- Drop/File Input option for user convenience -->
                    <label class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-white/10 hover:bg-white/15 border border-dashed border-white/20 text-xs font-semibold text-slate-200 cursor-pointer transition-colors">
                        <svg class="w-4 h-4 text-brand-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Local MP3 to Queue</span>
                        <input id="localFileInput" type="file" accept="audio/*" multiple class="hidden">
                    </label>
                </div>
            </div>

        </div>
    </main>

    <!-- Hidden HTML5 Audio Element -->
    <audio id="audioElement" preload="metadata" crossorigin="anonymous"></audio>

    <!-- Client-side Audio Logic & Playlist Management -->
    <script>
        // Pre-loaded playlist from server-side PHP (Already naturally sorted in Ascending order)
        const serverPlaylist = <?php echo json_encode($playlist); ?>;

        // Player State
        const state = {
            playlist: serverPlaylist.length > 0 ? [...serverPlaylist] : [],
            currentIndex: 0,
            isPlaying: false,
            isShuffle: false,
            repeatMode: 'all', // 'off', 'all', 'one'
            playbackSpeed: 1.0,
            currentThemeIndex: 0,
            themes: ['theme-electric', 'theme-sunset', 'theme-cyberpunk', 'theme-aurora', 'theme-ocean'],
            speeds: [0.75, 1.0, 1.25, 1.5, 2.0],
            isChassis: true,
            audioContext: null,
            analyser: null,
            dataArray: null,
            audioSourceNode: null
        };

        // DOM Elements
        const audio = document.getElementById('audioElement');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const playIcon = document.getElementById('playIcon');
        const pauseIcon = document.getElementById('pauseIcon');
        const prevTrackBtn = document.getElementById('prevTrackBtn');
        const nextTrackBtn = document.getElementById('nextTrackBtn');
        const skipBack10Btn = document.getElementById('skipBack10Btn');
        const skipForward10Btn = document.getElementById('skipForward10Btn');
        const seekBar = document.getElementById('seekBar');
        const currentTimeLabel = document.getElementById('currentTimeLabel');
        const durationTimeLabel = document.getElementById('durationTimeLabel');
        const streamStatus = document.getElementById('streamStatus');
        const volumeSlider = document.getElementById('volumeSlider');
        const muteBtn = document.getElementById('muteBtn');
        const volumeHighIcon = document.getElementById('volumeHighIcon');
        const volumeMutedIcon = document.getElementById('volumeMutedIcon');
        const shuffleBtn = document.getElementById('shuffleBtn');
        const repeatBtn = document.getElementById('repeatBtn');
        const repeatIndicator = document.getElementById('repeatIndicator');
        const speedBtn = document.getElementById('speedBtn');
        const speedLabel = document.getElementById('speedLabel');
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const appGradientBg = document.getElementById('appGradientBg');
        const ambientAura = document.getElementById('ambientAura');
        const vinylRecord = document.getElementById('vinylRecord');
        const vinylAuraGlow = document.getElementById('vinylAuraGlow');
        const discCenterText = document.getElementById('discCenterText');
        const currentSongTitle = document.getElementById('currentSongTitle');
        const currentSongArtist = document.getElementById('currentSongArtist');
        const trackNumberBadge = document.getElementById('trackNumberBadge');
        const miniTrackTitle = document.getElementById('miniTrackTitle');
        const playlistDrawer = document.getElementById('playlistDrawer');
        const openPlaylistBtn = document.getElementById('openPlaylistBtn');
        const closePlaylistBtn = document.getElementById('closePlaylistBtn');
        const footerTrackInfo = document.getElementById('footerTrackInfo');
        const showPlaylistPillBtn = document.getElementById('showPlaylistPillBtn');
        const playlistContainer = document.getElementById('playlistContainer');
        const playlistSearchInput = document.getElementById('playlistSearchInput');
        const refreshPlaylistBtn = document.getElementById('refreshPlaylistBtn');
        const localFileInput = document.getElementById('localFileInput');
        const visualizerCanvas = document.getElementById('visualizerCanvas');
        const toggleChassisBtn = document.getElementById('toggleChassisBtn');
        const chassisBtnText = document.getElementById('chassisBtnText');
        const phoneContainer = document.getElementById('phoneContainer');
        const phoneIsland = document.getElementById('phoneIsland');
        const statusBarClock = document.getElementById('statusBarClock');

        // Update Phone Status Bar Clock
        function updateClock() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            statusBarClock.textContent = `${hours}:${minutes}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Format Seconds into mm:ss
        function formatTime(seconds) {
            if (isNaN(seconds) || seconds < 0) return '0:00';
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
        }

        // Initialize Web Audio API Analyser for Visualizer
        function initAudioContext() {
            if (state.audioContext) return;
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                state.audioContext = new AudioCtx();
                state.analyser = state.audioContext.createAnalyser();
                state.analyser.fftSize = 64;
                
                state.audioSourceNode = state.audioContext.createMediaElementSource(audio);
                state.audioSourceNode.connect(state.analyser);
                state.analyser.connect(state.audioContext.destination);

                const bufferLength = state.analyser.frequencyBinCount;
                state.dataArray = new Uint8Array(bufferLength);
                renderVisualizer();
            } catch (err) {
                console.warn('Web Audio API not allowed without user gesture or cross-origin:', err);
                renderFallbackVisualizer();
            }
        }

        // Render Audio Visualizer on Canvas
        function renderVisualizer() {
            if (!visualizerCanvas) return;
            const ctx = visualizerCanvas.getContext('2d');
            const width = visualizerCanvas.width;
            const height = visualizerCanvas.height;

            function draw() {
                requestAnimationFrame(draw);
                if (!state.analyser || !state.isPlaying) {
                    ctx.clearRect(0, 0, width, height);
                    // Draw idle baseline
                    ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
                    ctx.fillRect(0, height - 3, width, 2);
                    return;
                }

                state.analyser.getByteFrequencyData(state.dataArray);
                ctx.clearRect(0, 0, width, height);

                const barCount = 18;
                const barWidth = (width / barCount) - 2;
                let x = 1;

                for (let i = 0; i < barCount; i++) {
                    const freqIndex = Math.floor(i * (state.dataArray.length / barCount));
                    const barHeight = Math.max(3, (state.dataArray[freqIndex] / 255) * height);

                    // Dynamic gradient bar
                    const grad = ctx.createLinearGradient(0, height, 0, 0);
                    grad.addColorStop(0, '#8b5cf6');
                    grad.addColorStop(0.5, '#ec4899');
                    grad.addColorStop(1, '#06ffd2');

                    ctx.fillStyle = grad;
                    ctx.beginPath();
                    ctx.roundRect(x, height - barHeight, barWidth, barHeight, [2, 2, 0, 0]);
                    ctx.fill();

                    x += barWidth + 2;
                }
            }
            draw();
        }

        function renderFallbackVisualizer() {
            if (!visualizerCanvas) return;
            const ctx = visualizerCanvas.getContext('2d');
            const width = visualizerCanvas.width;
            const height = visualizerCanvas.height;

            function drawFake() {
                requestAnimationFrame(drawFake);
                ctx.clearRect(0, 0, width, height);
                if (!state.isPlaying) {
                    ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
                    ctx.fillRect(0, height - 3, width, 2);
                    return;
                }
                const barCount = 18;
                const barWidth = (width / barCount) - 2;
                let x = 1;
                const time = Date.now() / 150;
                for (let i = 0; i < barCount; i++) {
                    const barHeight = Math.max(4, Math.sin(time + i * 0.6) * (height / 2.2) + (height / 2));
                    const grad = ctx.createLinearGradient(0, height, 0, 0);
                    grad.addColorStop(0, '#8b5cf6');
                    grad.addColorStop(1, '#06ffd2');
                    ctx.fillStyle = grad;
                    ctx.beginPath();
                    ctx.roundRect(x, height - barHeight, barWidth, barHeight, [2, 2, 0, 0]);
                    ctx.fill();
                    x += barWidth + 2;
                }
            }
            drawFake();
        }

        // Render Playlist Drawer Items
        function renderPlaylist(filter = '') {
            playlistContainer.innerHTML = '';
            const filtered = state.playlist.filter(song => 
                song.title.toLowerCase().includes(filter.toLowerCase()) || 
                song.artist.toLowerCase().includes(filter.toLowerCase()) ||
                song.filename.toLowerCase().includes(filter.toLowerCase())
            );

            if (filtered.length === 0) {
                playlistContainer.innerHTML = `
                    <div class="text-center py-8 text-slate-400 text-xs">
                        <p class="font-bold text-slate-300">No tracks match "${filter}"</p>
                        <p class="mt-1">Place .mp3 files inside <code class="text-brand-neon">mp3-files/</code></p>
                    </div>
                `;
                return;
            }

            filtered.forEach((song, idx) => {
                const originalIndex = state.playlist.indexOf(song);
                const isCurrent = originalIndex === state.currentIndex;

                const item = document.createElement('div');
                item.className = `flex items-center justify-between p-3 rounded-2xl transition-all cursor-pointer ${
                    isCurrent 
                    ? 'bg-gradient-to-r from-brand-600/40 via-purple-600/30 to-pink-600/20 border border-brand-400/50 shadow-md' 
                    : 'bg-white/5 hover:bg-white/10 border border-white/5'
                }`;

                item.innerHTML = `
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-mono text-xs font-black ${
                            isCurrent ? 'bg-brand-500 text-white shadow-lg shadow-purple-900/50' : 'bg-white/10 text-slate-300'
                        }">
                            ${isCurrent && state.isPlaying ? `
                                <div class="flex items-end justify-center gap-0.5 h-4 w-4">
                                    <span class="w-1 bg-white rounded-full animate-eq-bar-1 h-full"></span>
                                    <span class="w-1 bg-white rounded-full animate-eq-bar-2 h-full"></span>
                                    <span class="w-1 bg-white rounded-full animate-eq-bar-3 h-full"></span>
                                </div>
                            ` : (originalIndex + 1).toString().padStart(2, '0')}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-white truncate ${isCurrent ? 'text-brand-neon' : ''}">
                                ${song.title}
                            </h4>
                            <p class="text-[10px] text-slate-400 truncate">
                                ${song.artist} • <span class="font-mono">${song.size || ''}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pl-2">
                        ${isCurrent ? `
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-brand-neon/20 text-brand-neon border border-brand-neon/40">
                                PLAYING
                            </span>
                        ` : `
                            <span class="text-[10px] text-slate-400">#${originalIndex + 1}</span>
                        `}
                    </div>
                `;

                item.addEventListener('click', () => {
                    loadSong(originalIndex);
                    playAudio();
                    closePlaylistDrawer();
                });

                playlistContainer.appendChild(item);
            });
        }

        // Load Track Metadata & Source
        function loadSong(index) {
            if (state.playlist.length === 0) return;
            if (index < 0) index = state.playlist.length - 1;
            if (index >= state.playlist.length) index = 0;
            
            state.currentIndex = index;
            const song = state.playlist[index];

            audio.src = song.url;
            audio.playbackRate = state.playbackSpeed;

            currentSongTitle.textContent = song.title;
            currentSongArtist.textContent = song.artist;
            miniTrackTitle.textContent = song.title;
            discCenterText.textContent = song.title;
            trackNumberBadge.textContent = `#${(index + 1).toString().padStart(2, '0')} / ${state.playlist.length.toString().padStart(2, '0')}`;

            // Reset Seekbar
            seekBar.value = 0;
            currentTimeLabel.textContent = '0:00';
            durationTimeLabel.textContent = '0:00';
            streamStatus.textContent = 'BUFFERING';

            // Dynamic Cover Hue shift
            const hues = [260, 320, 190, 45, 140, 280];
            const hue = hues[index % hues.length];
            ambientAura.style.backgroundColor = `hsla(${hue}, 80%, 55%, 0.35)`;
            vinylAuraGlow.style.background = `radial-gradient(circle, hsla(${hue}, 90%, 60%, 0.4) 0%, transparent 70%)`;

            // Mobile MediaSession lockscreen metadata
            if ('mediaSession' in navigator) {
                navigator.mediaSession.metadata = new MediaMetadata({
                    title: song.title,
                    artist: song.artist,
                    album: song.album || 'Ashish Vegan MP3 Player',
                    artwork: [
                        { src: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><rect width="512" height="512" fill="%238b5cf6"/><circle cx="256" cy="256" r="180" fill="%23111827"/><circle cx="256" cy="256" r="60" fill="%2306ffd2"/></svg>', sizes: '512x512', type: 'image/svg+xml' }
                    ]
                });
            }

            renderPlaylist(playlistSearchInput.value);
        }

        // Play Audio
        function playAudio() {
            initAudioContext();
            if (state.audioContext && state.audioContext.state === 'suspended') {
                state.audioContext.resume();
            }

            audio.play().then(() => {
                state.isPlaying = true;
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
                vinylRecord.classList.remove('paused-spin');
                streamStatus.textContent = 'PLAYING';
                renderPlaylist(playlistSearchInput.value);
            }).catch(e => {
                console.warn('Playback error:', e);
                streamStatus.textContent = 'CLICK PLAY';
            });
        }

        // Pause Audio
        function pauseAudio() {
            audio.pause();
            state.isPlaying = false;
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            vinylRecord.classList.add('paused-spin');
            streamStatus.textContent = 'PAUSED';
            renderPlaylist(playlistSearchInput.value);
        }

        // Play / Pause Toggle
        function togglePlay() {
            if (state.playlist.length === 0) return;
            if (state.isPlaying) {
                pauseAudio();
            } else {
                playAudio();
            }
        }

        // Next Track (respects shuffle and ascending order)
        function nextTrack() {
            if (state.playlist.length === 0) return;
            let nextIndex;
            if (state.isShuffle) {
                nextIndex = Math.floor(Math.random() * state.playlist.length);
                if (nextIndex === state.currentIndex && state.playlist.length > 1) {
                    nextIndex = (nextIndex + 1) % state.playlist.length;
                }
            } else {
                nextIndex = (state.currentIndex + 1) % state.playlist.length;
            }
            loadSong(nextIndex);
            playAudio();
        }

        // Previous Track
        function prevTrack() {
            if (state.playlist.length === 0) return;
            // If track played more than 3 seconds, rewind to start first
            if (audio.currentTime > 3) {
                audio.currentTime = 0;
                return;
            }
            let prevIndex = state.currentIndex - 1;
            if (prevIndex < 0) prevIndex = state.playlist.length - 1;
            loadSong(prevIndex);
            playAudio();
        }

        // Open & Close Playlist Drawer
        function openPlaylistDrawer() {
            playlistDrawer.classList.remove('translate-y-full');
            renderPlaylist(playlistSearchInput.value);
        }
        function closePlaylistDrawer() {
            playlistDrawer.classList.add('translate-y-full');
        }

        // Event Listeners
        playPauseBtn.addEventListener('click', togglePlay);
        nextTrackBtn.addEventListener('click', nextTrack);
        prevTrackBtn.addEventListener('click', prevTrack);

        // 10s Rewind / Forward
        skipBack10Btn.addEventListener('click', () => {
            audio.currentTime = Math.max(0, audio.currentTime - 10);
        });
        skipForward10Btn.addEventListener('click', () => {
            audio.currentTime = Math.min(audio.duration || 0, audio.currentTime + 10);
        });

        // Seek Bar Interactions
        audio.addEventListener('timeupdate', () => {
            if (!isNaN(audio.duration) && audio.duration > 0) {
                const progress = (audio.currentTime / audio.duration) * 100;
                seekBar.value = progress;
                currentTimeLabel.textContent = formatTime(audio.currentTime);
            }
        });

        audio.addEventListener('loadedmetadata', () => {
            durationTimeLabel.textContent = formatTime(audio.duration);
            streamStatus.textContent = 'READY';
        });

        audio.addEventListener('waiting', () => {
            streamStatus.textContent = 'LOADING...';
        });

        audio.addEventListener('playing', () => {
            streamStatus.textContent = 'PLAYING';
        });

        seekBar.addEventListener('input', (e) => {
            if (!isNaN(audio.duration) && audio.duration > 0) {
                const seekTo = (e.target.value / 100) * audio.duration;
                currentTimeLabel.textContent = formatTime(seekTo);
            }
        });

        seekBar.addEventListener('change', (e) => {
            if (!isNaN(audio.duration) && audio.duration > 0) {
                audio.currentTime = (e.target.value / 100) * audio.duration;
            }
        });

        // Track Ended Handler
        audio.addEventListener('ended', () => {
            if (state.repeatMode === 'one') {
                audio.currentTime = 0;
                playAudio();
            } else if (state.repeatMode === 'all') {
                nextTrack();
            } else {
                // If repeat is off and it's the last song, pause
                if (state.currentIndex === state.playlist.length - 1 && !state.isShuffle) {
                    pauseAudio();
                } else {
                    nextTrack();
                }
            }
        });

        // Volume Slider & Mute
        let previousVolume = 0.85;
        volumeSlider.addEventListener('input', (e) => {
            const val = parseFloat(e.target.value);
            audio.volume = val;
            if (val === 0) {
                volumeHighIcon.classList.add('hidden');
                volumeMutedIcon.classList.remove('hidden');
            } else {
                volumeHighIcon.classList.remove('hidden');
                volumeMutedIcon.classList.add('hidden');
                previousVolume = val;
            }
        });

        muteBtn.addEventListener('click', () => {
            if (audio.volume > 0) {
                previousVolume = audio.volume;
                audio.volume = 0;
                volumeSlider.value = 0;
                volumeHighIcon.classList.add('hidden');
                volumeMutedIcon.classList.remove('hidden');
            } else {
                audio.volume = previousVolume || 0.8;
                volumeSlider.value = audio.volume;
                volumeHighIcon.classList.remove('hidden');
                volumeMutedIcon.classList.add('hidden');
            }
        });

        // Shuffle Toggle
        shuffleBtn.addEventListener('click', () => {
            state.isShuffle = !state.isShuffle;
            if (state.isShuffle) {
                shuffleBtn.classList.add('text-brand-neon', 'border-brand-neon/50', 'bg-brand-neon/15');
                shuffleBtn.classList.remove('text-white/70');
            } else {
                shuffleBtn.classList.remove('text-brand-neon', 'border-brand-neon/50', 'bg-brand-neon/15');
                shuffleBtn.classList.add('text-white/70');
            }
        });

        // Repeat Mode Toggle
        repeatBtn.addEventListener('click', () => {
            if (state.repeatMode === 'all') {
                state.repeatMode = 'one';
                repeatIndicator.classList.remove('hidden');
                repeatBtn.classList.add('text-brand-neon', 'border-brand-neon/50');
            } else if (state.repeatMode === 'one') {
                state.repeatMode = 'off';
                repeatIndicator.classList.add('hidden');
                repeatBtn.classList.remove('text-brand-neon', 'border-brand-neon/50');
                repeatBtn.classList.add('opacity-40');
            } else {
                state.repeatMode = 'all';
                repeatIndicator.classList.add('hidden');
                repeatBtn.classList.remove('opacity-40');
                repeatBtn.classList.add('text-white/70');
            }
        });

        // Playback Speed Toggle
        speedBtn.addEventListener('click', () => {
            const currentSpeedIdx = state.speeds.indexOf(state.playbackSpeed);
            const nextSpeedIdx = (currentSpeedIdx + 1) % state.speeds.length;
            state.playbackSpeed = state.speeds[nextSpeedIdx];
            audio.playbackRate = state.playbackSpeed;
            speedLabel.textContent = `${state.playbackSpeed.toFixed(2).replace(/\.00$/, '')}x`;
        });

        // Theme Toggle (Cycle through colorful gradient backgrounds)
        themeToggleBtn.addEventListener('click', () => {
            appGradientBg.classList.remove(state.themes[state.currentThemeIndex]);
            state.currentThemeIndex = (state.currentThemeIndex + 1) % state.themes.length;
            appGradientBg.classList.add(state.themes[state.currentThemeIndex]);
        });

        // Drawer Controls
        openPlaylistBtn.addEventListener('click', openPlaylistDrawer);
        showPlaylistPillBtn.addEventListener('click', openPlaylistDrawer);
        footerTrackInfo.addEventListener('click', openPlaylistDrawer);
        closePlaylistBtn.addEventListener('click', closePlaylistDrawer);

        // Search Filter
        playlistSearchInput.addEventListener('input', (e) => {
            renderPlaylist(e.target.value);
        });

        // Reload Playlist from Server
        refreshPlaylistBtn.addEventListener('click', () => {
            refreshPlaylistBtn.classList.add('animate-spin');
            fetch('?api=playlist')
                .then(r => r.json())
                .then(data => {
                    if (data && data.playlist) {
                        state.playlist = data.playlist;
                        renderPlaylist();
                    }
                    setTimeout(() => refreshPlaylistBtn.classList.remove('animate-spin'), 600);
                })
                .catch(() => {
                    refreshPlaylistBtn.classList.remove('animate-spin');
                });
        });

        // Local File Picker (Allows dropping or picking user's own MP3 files)
        localFileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            if (files.length === 0) return;

            // Sort added local files ascending
            files.sort((a, b) => a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: 'base' }));

            files.forEach(file => {
                const url = URL.createObjectURL(file);
                const sizeMb = (file.size / 1048576).toFixed(1) + ' MB';
                state.playlist.push({
                    id: state.playlist.length + 1,
                    filename: file.name,
                    title: file.name.replace(/\.[^/.]+$/, ''),
                    artist: 'Local Device',
                    url: url,
                    size: sizeMb,
                    bytes: file.size,
                    album: 'User Uploads'
                });
            });

            document.getElementById('playlistBadgeCount').textContent = state.playlist.length;
            document.getElementById('drawerCountText').textContent = state.playlist.length;
            renderPlaylist();
            alert(`Added ${files.length} local audio track(s) to playlist queue!`);
        });

        // Desktop Chassis Toggle (Mobile mockup frame vs full viewport)
        toggleChassisBtn.addEventListener('click', () => {
            state.isChassis = !state.isChassis;
            if (state.isChassis) {
                phoneContainer.classList.add('sm:max-w-[395px]', 'sm:h-[844px]', 'sm:rounded-[48px]', 'sm:border-[10px]');
                phoneIsland.classList.remove('hidden');
                phoneIsland.classList.add('hidden', 'sm:flex');
                chassisBtnText.textContent = 'Chassis: ON';
            } else {
                phoneContainer.classList.remove('sm:max-w-[395px]', 'sm:h-[844px]', 'sm:rounded-[48px]', 'sm:border-[10px]');
                phoneIsland.classList.add('hidden');
                phoneIsland.classList.remove('sm:flex');
                chassisBtnText.textContent = 'Chassis: OFF (Full)';
            }
        });

        // Keyboard Shortcuts
        window.addEventListener('keydown', (e) => {
            if (e.target.tagName === 'INPUT') return;
            if (e.code === 'Space') {
                e.preventDefault();
                togglePlay();
            } else if (e.code === 'ArrowRight') {
                audio.currentTime = Math.min(audio.duration, audio.currentTime + 5);
            } else if (e.code === 'ArrowLeft') {
                audio.currentTime = Math.max(0, audio.currentTime - 5);
            } else if (e.code === 'ArrowUp') {
                audio.volume = Math.min(1, audio.volume + 0.05);
                volumeSlider.value = audio.volume;
            } else if (e.code === 'ArrowDown') {
                audio.volume = Math.max(0, audio.volume - 0.05);
                volumeSlider.value = audio.volume;
            } else if (e.key.toLowerCase() === 'n') {
                nextTrack();
            } else if (e.key.toLowerCase() === 'p') {
                prevTrack();
            } else if (e.key.toLowerCase() === 'm') {
                muteBtn.click();
            }
        });

        // Setup MediaSession Action Handlers for Lockscreen / Mobile Notifications
        if ('mediaSession' in navigator) {
            navigator.mediaSession.setActionHandler('play', playAudio);
            navigator.mediaSession.setActionHandler('pause', pauseAudio);
            navigator.mediaSession.setActionHandler('previoustrack', prevTrack);
            navigator.mediaSession.setActionHandler('nexttrack', nextTrack);
            navigator.mediaSession.setActionHandler('seekbackward', () => {
                audio.currentTime = Math.max(0, audio.currentTime - 10);
            });
            navigator.mediaSession.setActionHandler('seekforward', () => {
                audio.currentTime = Math.min(audio.duration || 0, audio.currentTime + 10);
            });
        }

        // Initialize App
        if (state.playlist.length > 0) {
            loadSong(0);
        } else {
            currentSongTitle.textContent = "No MP3s Found";
            currentSongArtist.textContent = "Put audio files in mp3-files/";
        }
        renderPlaylist();
    </script>
</body>
</html>
