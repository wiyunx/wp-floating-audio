document.addEventListener("DOMContentLoaded", () => {
    
    // --- 1. SETUP ---
    const splash = document.getElementById("wpfa-splash");
    const startBtn = document.getElementById("wpfa-start");
    const masterAudio = document.getElementById("wpfa-master-audio"); // The one and only audio
    const toggleButtons = document.querySelectorAll(".wpfa-toggle");

    // If no audio exists (shortcode not used), kill the splash screen safely
    if (splash && !masterAudio) {
        splash.style.display = 'none';
        document.body.classList.remove("wpfa-lock");
        document.documentElement.classList.remove("wpfa-lock");
        return;
    }

    if (!splash || !startBtn || !masterAudio) return;

    // Lock Screen
    document.body.classList.add("wpfa-lock");
    document.documentElement.classList.add("wpfa-lock");

    let userActivated = false;
    let wasPlayingBeforeHide = false;

    // --- 2. SYNC UI FUNCTION ---
    // This updates ALL buttons on the page (if you have more than one)
    const updateUI = (isPlaying) => {
        toggleButtons.forEach(btn => {
            if (isPlaying) {
                btn.classList.add("wpfa-playing");
                btn.classList.remove("wpfa-paused");
            } else {
                btn.classList.remove("wpfa-playing");
                btn.classList.add("wpfa-paused");
            }
        });
    };

    // --- 3. CONTROLS ---

    const playAudio = async () => {
        try {
            await masterAudio.play();
            updateUI(true);
        } catch (err) {
            console.warn("Playback failed", err);
            updateUI(false);
        }
    };

    const pauseAudio = () => {
        masterAudio.pause();
        updateUI(false);
    };

    // --- 4. START BUTTON (Splash) ---
    const openInvitation = async () => {
        userActivated = true;
        wasPlayingBeforeHide = true; // Mark as "User wants music"

        splash.classList.add("wpfa-hidden");
        document.body.classList.remove("wpfa-lock");
        document.documentElement.classList.remove("wpfa-lock");

        await playAudio();
    };

    startBtn.addEventListener("click", openInvitation);
    startBtn.addEventListener("touchend", (e) => {
        e.preventDefault();
        openInvitation();
    });

    // --- 5. TOGGLE BUTTONS (Play/Pause) ---
    // Use document delegation so it works even if HTML moves
    document.addEventListener("click", async (e) => {
        if (!e.target.closest(".wpfa-toggle")) return;

        userActivated = true;

        if (masterAudio.paused) {
            await playAudio();
            wasPlayingBeforeHide = true;
        } else {
            pauseAudio();
            wasPlayingBeforeHide = false;
        }
    });

    // --- 6. SMART VISIBILITY (Tab Switching) ---
    document.addEventListener("visibilitychange", async () => {
        if (!userActivated) return;

        if (document.hidden) {
            // User left tab.
            // Check if it was playing NOW, save that state.
            wasPlayingBeforeHide = !masterAudio.paused;
            pauseAudio();
        } else {
            // User returned.
            // Only play if it was playing when they left.
            if (wasPlayingBeforeHide) {
                await playAudio();
            }
        }
    });

    // Mobile specific safety
    window.addEventListener("pagehide", () => {
        pauseAudio();
    });
});