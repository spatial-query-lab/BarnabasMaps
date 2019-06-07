/************************************MAP CONTROLS****************************************/
$(document).ready(() => {
    getCoordinates();
});

function map(coordinates) {

    require(["esri/Map", "esri/views/MapView", "esri/Graphic", "esri/geometry/geometryEngine", "esri/geometry/Point", "esri/geometry/Multipoint", "esri/layers/GraphicsLayer"], function (
        Map,
        MapView,
        Graphic,
        geometryEngine,
        Point,
        Multipoint,
        GraphicsLayer
    ) {
        var map = new Map({
            basemap: "hybrid"
        });

        /***************************
         * Create a polygon graphic
         ***************************/

        // Create a polygon geometry using convex hull
        var pointArray = new Multipoint({
            points: coordinates
        });
        var polygon = geometryEngine.convexHull(pointArray);

        // Create a symbol for rendering the graphic
        var fillSymbol = {
            type: "simple-fill", // autocasts as new SimpleFillSymbol()
            color: [227, 139, 79, 0.8],
            outline: {
                // autocasts as new SimpleLineSymbol()
                color: [255, 255, 255],
                width: 1
            }
        };

        // Add the geometry and symbol to a new graphic
        var polygonGraphic = new Graphic({
            geometry: polygon,
            symbol: fillSymbol
        });

        // Create graphics layer for polygon
        var layer = new GraphicsLayer({
            graphics: [polygonGraphic]
        });

        var view = new MapView({
            center: polygon.centroid,
            container: "viewDiv",
            map: map,
            zoom: 3
        });

        // Create a symbol for drawing the point
        let markerSymbol = {
            type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
            color: [226, 119, 40],
            outline: {
                // autocasts as new SimpleLineSymbol()
                color: [255, 255, 255],
                width: 2
            }
        };

        let pointLayer = new GraphicsLayer();

        // draw point
        view.on("click", function (event) {
            // Getting lat and lon
            let latitude = event.mapPoint.latitude;
            let longitude = event.mapPoint.longitude;

            // Setting input fields
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);

            // Clearing the graphic layer
            pointLayer.removeAll();
            

            // Create a graphic and add the geometry and symbol to it
            let pointGraphic = new Graphic({
                geometry: event.mapPoint,
                symbol: markerSymbol
            });
            
            pointLayer.add(pointGraphic);
            console.log(event.mapPoint);
        });


        map.add(layer);
        map.add(pointLayer);
    });
}

function getCoordinates() {
    $.ajax({
        url: 'process-coordinates.php',
        method: 'post',
        data: {
            plantid: $('#plant').val()
        },
        success: (response) => {
            map(JSON.parse(response));
        }
    });
}

$('#plant').change(() => {
    getCoordinates();
});
/****************************************************************************/

$('#reportForm').submit((event) => {
    event.preventDefault();

    // Get values
    let form = $('#reportForm')[0];
    let data = new FormData(form);

    $.ajax({
        url: "../Scripts/process-report.php",
        method: 'post',
        enctype: 'multipart/form-data',
        processData: false, // Important!
        contentType: false,
        cache: false,
        data: data,
        success: (response) => {
            alert(response);
            document.getElementById("reportForm").reset();
        }
    });
});