<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low-Cost Housing Calculator</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Find Out How Much Your Home-building Project Will Cost</h1>
        <form class="calculator-form">
            <div class="form-group">
                <label for="state">Select District</label>
                <select id="state" name="state">
                    <option value="">Select District</option>
<option value="Alappuzha">Alappuzha</option>
<option value="Ernakulam">Ernakulam</option>
<option value="Idukki">Idukki</option>
<option value="Kannur">Kannur</option>
<option value="Kasaragod">Kasaragod</option>
<option value="Kollam">Kollam</option>
<option value="Kottayam">Kottayam</option>
<option value="Kozhikode">Kozhikode</option>
<option value="Malappuram">Malappuram</option>
<option value="Palakkad">Palakkad</option>
<option value="Pathanamthitta">Pathanamthitta</option>
<option value="Thrissur">Thrissur</option>
<option value="Thiruvananthapuram">Thiruvananthapuram</option>
<option value="Wayanad">Wayanad</option>
                    <!-- Add more options as required -->
                </select>
            </div>
            <div class="form-group">
                <label for="city">Select Location</label>
                <select id="city" name="city">
                    <option value="">Select Location</option>
                    <!-- Add more options as required -->
                </select>
            </div>
            <div class="form-group">
                <label for="area">Area</label>
                <div class="area-input">
                    <input type="number" id="area" name="area" placeholder="5000">
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
                <label for="city">Residential Building</label>
                <select id="city" name="city">
                    <option value="">1 BHK</option>
                    <option value="">2 BHK</option>
                    <!-- Add more options as required -->
                </select>
            </div>
            
            <button type="submit" class="next-button">Estimate Cost➝</button>
        </form>
    </div>
</body>
</html>
