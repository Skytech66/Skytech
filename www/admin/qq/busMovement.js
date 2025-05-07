function moveBusTowardsStudent(studentName) {
    let bus = document.getElementById("busIcon");
    let studentMarker = document.querySelector(`[onclick="showETAModal('${studentName}')"]`);

    if (!studentMarker) return;

    // Get positions of bus and student relative to the map container
    let mapContainer = document.querySelector(".map-container");
    let busRect = bus.getBoundingClientRect();
    let studentRect = studentMarker.getBoundingClientRect();
    let mapRect = mapContainer.getBoundingClientRect();

    // Calculate the target position inside the map container
    let targetX = studentRect.left - mapRect.left;
    let targetY = studentRect.top - mapRect.top;

    // Calculate ETA-based duration
    let etaSeconds = parseInt(document.getElementById("etaCountdown").innerText);
    let duration = Math.max(etaSeconds, 5); // Minimum 5 seconds for smooth movement

    // Apply smooth movement using CSS transition
    bus.style.transition = `top ${duration}s linear, left ${duration}s linear`;
    bus.style.left = targetX + "px";
    bus.style.top = targetY + "px";

    // Reset transition after arrival for next move
    setTimeout(() => {
        bus.style.transition = ""; // Remove transition for next move
    }, duration * 1000);
}