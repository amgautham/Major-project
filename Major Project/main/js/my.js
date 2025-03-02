document.addEventListener("DOMContentLoaded", async () => {
    const districtSelect = document.getElementById("district");

    // Fetch districts on page load
    try {
        const response = await fetch("adphp/get_locations.php");
        const districts = await response.json();

        districts.forEach(district => {
            const option = document.createElement("option");
            option.value = district.id; // Use district ID as the value
            option.textContent = district.name; // Display district name
            districtSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error fetching districts:", error);
    }
});

async function fetchLocations() {
    const districtId = document.getElementById("district").value;
    const locationSelect = document.getElementById("location");

    // Clear existing options
    locationSelect.innerHTML = '<option value="">Select Location</option>';

    if (districtId) {
        try {
            const response = await fetch(`adphp/get_locations.php?district_id=${encodeURIComponent(districtId)}`);
            const locations = await response.json();

            locations.forEach(location => {
                const option = document.createElement("option");
                option.value = location;
                option.textContent = location;
                locationSelect.appendChild(option);
            });
        } catch (error) {
            console.error("Error fetching locations:", error);
        }
    }
}

async function goToGraph() {
    window.location.href = "../forms/grapes.html"; // Adjust path based on your folder structure
}
