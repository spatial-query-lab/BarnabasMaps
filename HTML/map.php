<?php
require_once "../Library/DBHelper.php";
$db = new DBHelper();

$plants = $db->SELECT_TABLE_BY_NAME("plants");
?>
<!DOCTYPE html>
<html>

<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://js.arcgis.com/4.11/esri/themes/light/main.css" />
    <title>Barnabas Maps</title>

    <style>
        #viewDiv {
            padding: 0;
            margin: 0;
            height: 500px;
            width: 100%;
        }
    </style>

</head>

<body>

    <!-- main -->
    <main class="container">
        <div class="row">
            <div class="col">
                <h1 class="text-center">Barnabas Maps</h1>
                <hr>
                <!-- Map -->
                <!-- https://developers.arcgis.com/javascript/latest/sample-code/intro-graphics/index.html -->
            </div>
        </div>
        <div class="row">
            <!-- Map -->
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">Change Plant</h5>
                    </div>
                    <div class="card-body">
                        <select class="form-control" id="plant" name="plant">
                            <?php 
                                        foreach($plants as $plant)
                                        {
                                            echo '<option value="' . $plant["id"] . '">' . $plant["name"] . '</option>';
                                        }
                                    ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Submit Report</h2>
                    </div>
                    <form id="reportForm">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" placeholder="Longitude"
                                        required name="longitude">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" placeholder="Latitude"
                                        requried name="latitude">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="number">Number Found</label>
                                <input type="text" class="form-control" id="number" placeholder="Number of plants found"
                                    name="number">
                            </div>
                            <div class="form-group">
                                <label for="plantid">Plant</label>
                                <select class="form-control" id="plantid" name="plantid" required>
                                    <?php 
                                        foreach($plants as $plant)
                                        {
                                            echo '<option value="' . $plant["id"] . '">' . $plant["name"] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="row mt-3 mb-3">
            
            <div class="col" id="viewDiv"></div>
            
        </div>

    </main>

</body>

<!-- Jquery and Bootstrap CDN -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>

<script src="https://js.arcgis.com/4.11/"></script>

<!-- Custom Script -->
<script src="../Scripts/map.js"></script>

</html>