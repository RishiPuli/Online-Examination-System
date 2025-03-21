let timeLeft = 1800; // 30 minutes in seconds

const timer = setInterval(() => {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;

    document.getElementById("time").innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    timeLeft--;

    if (timeLeft < 0) {
        clearInterval(timer);
        document.getElementById("exam-form").submit(); // Automatically submit when time is up
    }
}, 1000);
