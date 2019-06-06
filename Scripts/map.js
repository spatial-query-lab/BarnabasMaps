
/************************************MAP CONTROLS****************************************/
$(document).ready(() => {
    getCoordinates();
});

function map(coordinates) {
    
    require(["esri/Map", "esri/views/MapView", "esri/Graphic", "esri/geometry/geometryEngine", "esri/geometry/Point", "esri/geometry/Multipoint"], function (
        Map,
        MapView,
        Graphic,
        geometryEngine,
        Point,
        Multipoint
    ) {
        var map = new Map({
            basemap: "hybrid"
        });

        var view = new MapView({
            center: [-80, 35],
            container: "viewDiv",
            map: map,
            zoom: 3
        });

        view.when(function () {
            const sketch = new Sketch({
                layer: layer,
                view: view
            });
            //view.ui.add(sketch, "top-right");
        });
        // draw point
        view.on("click", function (event) {
            // Checking to make sure that there are no points on the map
            if (view.graphics.length > 0) {
                view.graphics.removeAll();
            }
            // Setting longitude and latitude
            $('#longitude').val(event.mapPoint.longitude);
            $('#latitude').val(event.mapPoint.latitude);
            // First create a point geometry (this is the location of the Titanic)
            var point = {
                type: "point", // autocasts as new Point()
                longitude: event.mapPoint.longitude,
                latitude: event.mapPoint.latitude
            };
            // Create a symbol for drawing the point
            var markerSymbol = {
                type: "simple-marker", // autocasts as new SimpleMarkerSymbol()
                color: [226, 119, 40],
                outline: { // autocasts as new SimpleLineSymbol()
                    color: [255, 255, 255],
                    width: 2
                }
            };
            // Create a graphic and add the geometry and symbol to it
            var pointGraphic = new Graphic({
                geometry: point,
                symbol: markerSymbol,
                popupTemplate: { // autocasts as new PopupTemplate()
                    title: "{Name}",
                    content: [{
                        type: "fields",
                        fieldInfos: [{
                            fieldName: "Name"
                        }, {
                            fieldName: "Owner"
                        }, {
                            fieldName: "Length"
                        }]
                    }]
                }
            });
            view.graphics.add(pointGraphic);
        });
        // Listen to the click event on the map view.
        view.on("add", function (event) {
            console.log("click event: ", event.mapPoint);
        });

        /***************************
         * Create a polygon graphic
         ***************************/

        // Create a polygon geometry
        var pointArray = new Multipoint({points: coordinates}); 
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

        // Add the graphics to the view's graphics layer
        view.graphics.addMany([polygonGraphic]);
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
        processData: false,  // Important!
        contentType: false,
        cache: false,
        data: data,
        success:(response) => {
            alert(response);
            document.getElementById("reportForm").reset();
        }
    });
});