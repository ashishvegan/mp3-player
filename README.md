# Ashish Vegan MP3 Player 🎵

A sleek, colorful gradient mobile-first MP3 player web app built with **PHP** and **Tailwind CSS**. Features dynamic playlist loading from `mp3-files/` sorted in ascending order, real-time Web Audio API visualizer, rotating vinyl disc animation, and comprehensive audio playback controls.

![Ashish Vegan MP3 Player](https://raw.githubusercontent.com/ashishvegan/mp3-player/main/screenshot.png)

## ✨ Features

- **📱 Mobile UI/UX Focused**:
  - Desktop: Rendered inside a realistic flagship smartphone frame with dynamic notch/island, status bar, and ambient glow.
  - Mobile: Fullscreen native app experience with safe areas and touch-optimized controls.
  - Desktop frame can be toggled on/off to test full viewport scaling.
- **🎨 Colorful Gradient Themes**:
  - Vibrant multi-stop gradients (Electric Violet, Sunset Coral, Cyberpunk Neon, Aurora Emerald, Deep Ocean).
  - One-tap top theme switcher to dynamically cycle ambient themes.
- **🎶 Dynamic Playlist in Ascending Order**:
  - Automatically scans `mp3-files/` directory in PHP.
  - Naturally sorts songs in ascending order (`01`, `02`, `03`...).
  - Pull-up/slide-up playlist drawer with real-time search filter and active track soundwave indicator.
  - Drag-and-drop / local file picker to preview local tracks on the fly.
- **🎛️ Complete Audio Controls**:
  - Play, Pause, Next, Previous track navigation.
  - Rotating vinyl record disc synchronized with playback.
  - Real-time Web Audio API frequency visualizer equalizer.
  - Scrubbable seekbar with elapsed time and total duration.
  - -10s rewind and +10s forward skip.
  - Shuffle and Repeat modes (Repeat All, Repeat 1, Repeat Off).
  - Volume slider with one-tap instant mute.
  - Playback speed adjustment (0.75x, 1.0x, 1.25x, 1.5x, 2.0x).
  - Mobile Lock Screen controls via MediaSession API.
  - Keyboard shortcuts (`Space`, `ArrowLeft`/`ArrowRight`, `ArrowUp`/`ArrowDown`, `N`, `P`, `M`).
- **🔗 Clean URLs & Hidden `.php`**:
  - Apache `.htaccess` rules automatically strip and hide `.php` extensions.
  - Proper MIME type headers configured for audio streaming.

---

## 📁 Directory Structure

```text
├── .htaccess                 # Apache rewrite rules to hide *.php and stream audio
├── index.php                 # Core application (PHP backend & Tailwind frontend)
├── README.md                 # Project documentation
└── mp3-files/                # Directory containing MP3 files (sorted ascending)
    ├── 01 - Cyber Ambient Glow.mp3
    ├── 02 - Sunset Synth Odyssey.mp3
    ├── 03 - Vegan Chill Pulse.mp3
    └── 04 - Digital Aurora Wave.mp3
```

---

## 🚀 Getting Started

### 1. Requirements
- PHP 5.6+ or PHP 8.x
- Any modern web browser (Chrome, Safari, Edge, Firefox)

### 2. Run Locally

Clone the repository:
```bash
git clone https://github.com/ashishvegan/mp3-player.git
cd mp3-player
```

Start the PHP built-in web server:
```bash
php -S 127.0.0.1:8080
```

Open your browser and navigate to:
```
http://127.0.0.1:8080/
```

### 3. Adding Your Own Music
Simply place your `.mp3` files into the `mp3-files/` directory. They will automatically be sorted in **Ascending** order by filename and appear in the playlist!

---

## 📄 License
MIT License. Created by [Ashish Vegan](https://github.com/ashishvegan).
