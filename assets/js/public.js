var maintenanceTime = document.getElementById("countdown").dataset.maintenanceoff;
var maintenanceTime = JSON.parse(maintenanceTime);
var maintenanceEnabled = true;
const FULL_DASH = 339.29; // 2πr with r=54


function updateCountdown() {
  // Decrement the time
  if (maintenanceTime.seconds > 0) {
    maintenanceTime.seconds--;
  } else {
    if (maintenanceTime.minutes > 0) {
      maintenanceTime.minutes--;
      maintenanceTime.seconds = 59;
    } else {
      if (maintenanceTime.hours > 0) {
        maintenanceTime.hours--;
        maintenanceTime.minutes = 59;
        maintenanceTime.seconds = 59;
      } else {
        if (maintenanceTime.days > 0) {
          maintenanceTime.days--;
          maintenanceTime.hours = 23;
          maintenanceTime.minutes = 59;
          maintenanceTime.seconds = 59;
        } else {
          clearInterval(countdownTimer);
          document.getElementById("countdown").innerHTML =
            "Turning off the maintenance mode! Please hold on while the website is reloading.";
            maintenanceEnabled = false;
            

        }
      }
    }
    setTimeout(() => {
      if (!maintenanceEnabled) location.reload();
    }, 3000);
  }

  if (maintenanceTime.seconds > 0) {
    // Update the UI
    setCircle("days", maintenanceTime.days, 365); // assume max 30 days
    setCircle("hours", maintenanceTime.hours, 24);
    setCircle("minutes", maintenanceTime.minutes, 60);
    setCircle("seconds", maintenanceTime.seconds, 60);
  }
}

function setCircle(id, value, max) {
  const val = String(value).padStart(2, "0");
  document.getElementById(`${id}-value`).textContent = val;

  const circle = document.querySelector(`#${id} .circle-progress`);
  const offset = FULL_DASH - (FULL_DASH * value) / max;
  circle.style.strokeDashoffset = offset;
}
// Initial update
updateCountdown();

// Update the countdown every second
var countdownTimer = setInterval(updateCountdown, 1000);

