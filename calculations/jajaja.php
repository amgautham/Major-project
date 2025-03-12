<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="container" id="services">
    <h1>Find Out How Much Your Home-building Project Will Cost</h1>
    <form class="calculator-form" action="server.php" method="POST">

        <div class="form-group">
            <label for="district">Select District</label>
            <select id="district" name="district" onchange="fetchLocations()">
                <option value="">Select District</option>
                <option value="Ernakulam">Ernakulam</option>
                <option value="Trivandrum">Trivandrum</option>
                <option value="Kozhikode">Kozhikode</option>
            </select>
        </div>

        <div class="form-group">
            <label for="location">Select Location</label>
            <select id="location" name="location">
                <option value="">Select Location</option>
                <option value="Kakkanad">Kakkanad</option>
                <option value="Vazhuthacaud">Vazhuthacaud</option>
                <option value="Mananchira">Mananchira</option>
            </select>
        </div>

        <div class="form-group">
            <label for="area">Area</label>
            <div class="area-input">
                <input type="number" id="area" name="area" placeholder="5000" required>
                <div class="unit-options">
                    <label>
                        <input type="radio" name="unit" value="sqft" checked> Sq. Feet
                    </label>
                    <label>
                        <input type="radio" name="unit" value="sqm"> Sq. Meter
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="residential">Residential Building</label>
            <select id="residential" name="residential">
                <option value="1bhk">1 BHK</option>
                <option value="2bhk">2 BHK</option>
                <option value="3bhk">3 BHK</option>
                <option value="4bhk">4 BHK</option>
            </select>
        </div>

        <button type="submit" class="next-button">Estimate Cost ➝</button>
    </form>
</div>

</body>
</html>