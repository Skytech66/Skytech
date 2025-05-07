document.addEventListener("DOMContentLoaded", function () {
    const busIcon = document.getElementById("bus-icon"); // Bus element on the map
    const mapContainer = document.getElementById("map-container");
    const apiUrl = "http://localhost/MainProjecttt/www/admin/qq/bus_api.php";

    function updateBusLocation() {
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.latitude !== null && data.longitude !== null) {
                    moveBusSmoothly(data.latitude, data.longitude);
                }
            })
            .catch(error => console.error("Error fetching bus location:", error));
    }

    function moveBusSmoothly(newLat, newLng) {
        const scaleX = mapContainer.clientWidth / 100; // Adjust scaling based on your map size
        const scaleY = mapContainer.clientHeight / 100;
        
        const newX = newLng * scaleX;
        const newY = newLat * scaleY;
        
        busIcon.style.transition = "transform 2s ease-in-out";
        busIcon.style.transform = `translate(${newX}px, ${newY}px)`;
    }

    // Update location every 5 seconds
    setInterval(updateBusLocation, 5000);

    // Initial update
    updateBusLocation();
});
