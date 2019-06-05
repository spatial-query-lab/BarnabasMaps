<?php 
require_once "../Library/DBHelper.php";
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<title>Barnabas Maps</title>
</head>
<body>

<!-- main -->
<main class="container">
    <div class="row">
        <div class="col">
            <h1 class="text-center">Barnabas Maps</h1>
            <hr>

            <!-- Upload CSVs -->
            <button class="btn btn-primary" onclick="main()">Upload CSVs to Database</button>

            <!-- Map -->
            <!-- https://developers.arcgis.com/javascript/latest/sample-code/intro-graphics/index.html -->
        </div>
    </div>
</main>

</body>

<!-- Jquery and Bootstrap CDN -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- Custom Script -->
<script src="../Scripts/parse-data.js"></script>
</html>