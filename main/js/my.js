
document.getElementById("est").addEventListener("click", function() {
    let form = document.querySelector(".calculator-form");
    let formData = new FormData(form);

    // Convert form data into a query string
    let queryString = new URLSearchParams(formData).toString();
    
    // Redirect to PHP page with form data
    window.location.href = "calc/server.php?" + queryString;
});

