const audio = document.getElementById("audio-player");
const playPauseBtn = document.getElementById("play-pause");
const playIcon = document.getElementById("play-icon");
const pauseIcon = document.getElementById("pause-icon");
const seekBar = document.getElementById("seek-bar");
const currentTimeDisplay = document.getElementById("current-time");
const durationDisplay = document.getElementById("duration");
const volumeControl = document.getElementById("volume");

playPauseBtn.addEventListener("click", () => {
    if (audio.paused) {
        audio.play();
        playIcon.style.display = "none";
        pauseIcon.style.display = "inline";
    } else {
        audio.pause();
        playIcon.style.display = "inline";
        pauseIcon.style.display = "none";
    }
});

function formatTime(seconds) {
    const min = Math.floor(seconds / 60);
    const sec = Math.floor(seconds % 60);
    return `${min}:${sec < 10 ? "0" + sec : sec}`;
}

audio.addEventListener("loadedmetadata", () => {
    durationDisplay.textContent = formatTime(audio.duration);
    seekBar.max = 100;
});

audio.addEventListener("timeupdate", () => {
    const progress = (audio.currentTime / audio.duration) * 100;
    seekBar.value = progress;
    currentTimeDisplay.textContent = formatTime(audio.currentTime);
});

seekBar.addEventListener("input", () => {
    const seekTo = (seekBar.value / 100) * audio.duration;
    audio.currentTime = seekTo;
});

volumeControl.addEventListener("input", () => {
    audio.volume = volumeControl.value;
});

// 抓取歌曲標題與歌手名
function updateTrackTitle() {
    const title = document.querySelector(".song-info h1").textContent;
    const artist = document.querySelector(".song-info p").textContent;
    document.querySelector(".track-title").textContent = `播放中：${title} - ${artist}`;
}

document.addEventListener("DOMContentLoaded", updateTrackTitle);