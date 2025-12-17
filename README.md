# WP Floating Audio

A lightweight WordPress plugin that adds a floating audio player with a "Splash Screen" to handle browser autoplay policies effectively.

## Features
* **Autoplay Workaround:** Uses an "Open Invitation" splash screen to unlock audio context on modern browsers (Chrome/Safari).
* **Smart Visibility:** Pauses audio when the user switches tabs and resumes when they return.
* **Mobile Lock:** Prevents scrolling until the user interacts, ensuring they see the invitation.
* **Single Audio Instance:** Prevents "ghost audio" duplicates even if the theme renders content twice.

## Installation
1.  Download the repository as a ZIP file.
2.  Go to **WordPress Dashboard > Plugins > Add New > Upload Plugin**.
3.  Upload the ZIP and activate.

## Usage
Add the shortcode to any page or post:
[floating_audio src="YOUR_MP3_URL_HERE"]

## Credits
Built for custom WordPress themes.