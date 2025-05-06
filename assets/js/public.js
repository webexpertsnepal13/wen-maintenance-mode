var maintenanceTime = document.getElementById("countdown").dataset.maintenanceoff;
var maintenanceTime = JSON.parse(maintenanceTime);
var maintenanceEnabled = true;

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
    document.getElementById("days").innerText = maintenanceTime.days ? maintenanceTime.days : 0;
    document.getElementById("hours").innerText = maintenanceTime.hours ? maintenanceTime.hours : 0;
    document.getElementById("minutes").innerText = maintenanceTime.minutes ? maintenanceTime.minutes : 0;
    document.getElementById("seconds").innerText = maintenanceTime.seconds ? maintenanceTime.seconds : 0;
  }
}

// Initial update
updateCountdown();

// Update the countdown every second
var countdownTimer = setInterval(updateCountdown, 1000);

