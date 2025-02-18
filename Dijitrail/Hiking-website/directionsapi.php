<!--<html>

<head>
    <title>Geolocation</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEFSlbwju1780EKkbqTDFkiCxzvFkY5rE&libraries=places"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEFSlbwju1780EKkbqTDFkiCxzvFkY5rE&callback=initMap&libraries=&v=weekly" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <style type="text/css">
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        #map-canvas {
            height: 100%;
            width: 100%;
        }
    </style>
    <script>
        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        infoWindow.setPosition(pos);
                        infoWindow.setContent("Location found.");
                        infoWindow.open(map);
                        map.setCenter(pos);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }

        }
        pointB = new google.maps.LatLng(50.8429, -0.1313),
            myOptions = {
                zoom: 7,
                center: pointA
            },
            map = new google.maps.Map(document.getElementById('map-canvas'), myOptions),
            // Instantiate a directions service.
            directionsService = new google.maps.DirectionsService,
            directionsDisplay = new google.maps.DirectionsRenderer({
                map: map
            }),
            markerA = new google.maps.Marker({
                position: pointA,
                title: "point A",
                label: "A",
                map: map
            }),
            markerB = new google.maps.Marker({
                position: pointB,
                title: "point B",
                label: "B",
                map: map
            });

        // get route from A to B
        calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB);





        function calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB) {
            directionsService.route({
                origin: pointA,
                destination: pointB,
                travelMode: google.maps.TravelMode.DRIVING
            }, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }

        initMap();
    </script>
</head>

<body>
    <div id="map-canvas"></div>
</body>

</html>-->
<!DOCTYPE html>
<html>

<head>
    <title>Place Autocomplete and Directions</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEFSlbwju1780EKkbqTDFkiCxzvFkY5rE&callback=initMap&callback=initMap&libraries=places&v=weekly" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.js" integrity="sha256-DrT5NfxfbHvMHux31Lkhxg42LY6of8TaYyK50jnxRnM=" crossorigin="anonymous"></script>
    <style type="text/css">
        /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
        
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        .custom-map-control-button {
            appearance: button;
            background-color: #fff;
            border: 0;
            border-radius: 2px;
            box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            margin: 10px;
            padding: 0 0.5em;
            height: 40px;
            font: 400 18px Roboto, Arial, sans-serif;
            overflow: hidden;
        }
        
        .custom-map-control-button:hover {
            background: #ebebeb;
        }
        
        .controls {
            margin-top: 10px;
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }
        
        #origin-input,
        #destination-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 200px;
        }
        
        #origin-input:focus,
        #destination-input:focus {
            border-color: #4d90fe;
        }
        
        #mode-selector {
            color: #fff;
            background-color: #4d90fe;
            margin-left: 12px;
            padding: 5px 11px 0px 11px;
        }
        
        #mode-selector label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }
        
        #panel {
            height: 100%;
            width: null;
            background-color: white;
            position: fixed;
            z-index: 1;
            overflow-x: hidden;
            transition: all .2s ease-out;
        }
        
        .open {
            width: 250px;
        }
        /* Styling for place details */
        
        .hero {
            width: 100%;
            height: auto;
            max-height: 166px;
            display: block;
        }
        
        .place,
        p {
            font-family: 'open sans', arial, sans-serif;
            padding-left: 18px;
            padding-right: 18px;
        }
        
        .details {
            color: darkslategrey;
        }
        
        a {
            text-decoration: none;
            color: cadetblue;
        }
    </style>
    <script>
        let infoPane;
        //initialise map
        function initMap() {
            var directionsDisplay = new google.maps.DirectionsRenderer;
            var directionsService = new google.maps.DirectionsService;

            infoPane = document.getElementById('panel');
            const map = new google.maps.Map(document.getElementById("map"), {
                mapTypeControl: false,
                center: {
                    lat: 53.350140,
                    lng: -6.266155

                },
                zoom: 7,

            });
            new AutocompleteDirectionsHandler(map);
            infoWindow = new google.maps.InfoWindow();
            const locationButton = document.createElement("button");
            locationButton.textContent = "Pan to Current Location";
            locationButton.classList.add("custom-map-control-button");
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
                locationButton
            );
            locationButton.addEventListener("click", () => {
                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            infoWindow.setPosition(pos);
                            infoWindow.setContent("Location found.");
                            infoWindow.open(map);
                            map.setCenter(pos);
                        },
                        () => {
                            handleLocationError(true, infoWindow, map.getCenter());
                        }
                    );
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            });

            const Bogoffrogs = {
                lat: 53.376475,
                lng: -6.079462
            };

            const contentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Bog of Frogs loop</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Bog of Frogs Loop</b> Starting at the DART  " +
                "(train) station in Howth village and following purple arrows " +
                "this walk takes the path all along the cliffs to beyond Red Rock, " +
                "passing the Baily Lighthouse, and then climbing to circle the Ben of Howth " +
                "to return along the old tramline. The views of Lambay Island and Ireland’s Eye as well " +
                "as Dublin Bay are spectacular. The cliffs are great for birds and grey seals may also be seen. " +
                "<p><b>Gear recommened</b> Hiking shoes are recommended as a lot of the walk is uphill  " +
                "<p><b>Time</b> 3 Hours " +
                "<p><b>Distance</b> 12 KM " +
                "<p><b>Ascent</b> 240 M " +
                "<p><b>Difficulty</b>Strenuous"
            "</div>" +
            "</div>";
            const infowindow = new google.maps.InfoWindow({
                content: contentString,
            });
            const marker = new google.maps.Marker({
                position: Bogoffrogs,
                map,
                title: "Bog of Frogs Loop",
            });
            marker.addListener("click", () => {
                infowindow.open(map, marker);
            });

            const Howthcliffwalk = {
                lat: 53.38882,
                lng: -6.07422
            };

            const HowthcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Howth cliff walk</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Howth Cliff Walk</b> Starting at the DART  " +
                "(train) station in Howth village and following green arrows " +
                "this walk takes the path along the cliffs climbing to ‘The Summit’ " +
                "and returning along a path parallel to the outward route. " +
                "The views of Lambay Island and Ireland’s Eye as well as Dublin Bay are spectacular. " +
                " The cliffs are great for birds and grey seals may also be seen " +
                "<p><b>Gear recommened</b> Hiking shoes are recommended as a lot of the walk is uphill  " +
                "<p><b>Time</b> 2 Hours " +
                "<p><b>Distance</b> 6 KM " +
                "<p><b>Ascent</b> 130 M " +
                "<p><b>Difficulty</b>Moderate"
            "</div>" +
            "</div>";
            const Howthinfowindow = new google.maps.InfoWindow({
                content: HowthcontentString,
            });
            const Howthmarker = new google.maps.Marker({
                position: Howthcliffwalk,
                map,
                title: "Howth Cliff Walk",
            });
            Howthmarker.addListener("click", () => {
                Howthinfowindow.open(map, Howthmarker);
            });

            const RoyalCanalGreenway = {
                lat: 53.381621,
                lng: -6.369103
            };

            const RCGcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Royal Canal Greenway</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Royal Canal Greenway</b> This route provides a short but pleasant walk along the banks of the Royal Canal on a smooth tarmacadam path  " +
                "<p><b>Time</b> 40 minutes " +
                "<p><b>Distance</b> 2.7 KM " +
                "<p><b>Ascent</b> 14 M " +
                "<p><b>Difficulty</b>Easy"
            "</div>" +
            "</div>";
            const RCGinfowindow = new google.maps.InfoWindow({
                content: RCGcontentString,
            });
            const RCGmarker = new google.maps.Marker({
                position: RoyalCanalGreenway,
                map,
                title: "Royal Canal Greenway",
            });
            RCGmarker.addListener("click", () => {
                RCGinfowindow.open(map, RCGmarker);
            });

            const LittlewoodForestWalk = {
                lat: 53.721819,
                lng: -6.524069
            };

            const LWcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Littlewood - Forest Walk</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Littlewood - Forest Walk</b> " +
                "This short level walk around the edge of the forest starts at the car park and " +
                "follows forest track and path through mixed conifer and broadleaf woodland. " +
                "Display boards highlight the diversity of the flora and fauna that can be seen. " +
                "<p><b>Time</b> 40 minutes " +
                "<p><b>Distance</b> 2 KM " +
                "<p><b>Ascent</b> 4 M " +
                "<p><b>Difficulty</b>Easy"
            "</div>" +
            "</div>";
            const LWinfowindow = new google.maps.InfoWindow({
                content: LWcontentString,
            });
            const LWmarker = new google.maps.Marker({
                position: LittlewoodForestWalk,
                map,
                title: "Littlewood - Forest Walk",
            });
            LWmarker.addListener("click", () => {
                LWinfowindow.open(map, LWmarker);
            });

            const TrimPorchField = {
                lat: 53.555351,
                lng: -6.789603
            };

            const TPFcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Trim Porch Field</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Trim Porch Field</b> " +
                "This is a short walk around trim castle guided by green arrows on a white background " +
                "<p><b>Time</b> 50 minutes " +
                "<p><b>Distance</b> 3.2 KM " +
                "<p><b>Ascent</b> 7 M " +
                "<p><b>Difficulty</b>Easy"
            "</div>" +
            "</div>";
            const TPFinfowindow = new google.maps.InfoWindow({
                content: TPFcontentString,
            });
            const TPFmarker = new google.maps.Marker({
                position: TrimPorchField,
                map,
                title: "Trim Porch Field Family / Buggy Friendly Walk",
            });
            TPFmarker.addListener("click", () => {
                TPFinfowindow.open(map, TPFmarker);
            });

            const GarinishLoop = {
                lat: 51.609706,
                lng: -10.154967
            };

            const GLcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Garnish Loop</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Garnish Loop </b> " +
                "This Loop starts from the Dursey Island cable car station following the purple arrows " +
                "over open hillside above Garinish Point, with fine views of Dursey Island, open sea, " +
                "Cod’s Head, and Ballydonegan Bay. The Loop then drops to Garinish, passing Garinish " +
                "and Long Islands, and takes laneways and minor roads back to the start. " +
                "<p><b>Time</b> 1.5 Hours " +
                "<p><b>Distance</b> 4 KM " +
                "<p><b>Ascent</b> 200 M " +
                "<p><b>Difficulty</b>Moderate"
            "</div>" +
            "</div>";
            const GLinfowindow = new google.maps.InfoWindow({
                content: GLcontentString,
            });
            const GLmarker = new google.maps.Marker({
                position: GarinishLoop,
                map,
                title: "Garinish Loop (Beara)",
            });
            GLmarker.addListener("click", () => {
                GLinfowindow.open(map, GLmarker);
            });

            const CaherMarshLoop = {
                lat: 52.151991,
                lng: -9.785345
            };

            const CMLcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Caher Marsh Loop</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Caher Marsh Loop </b> " +
                "This family friendly walk follows quiet country road, tracks and a harbour levee along the shore, " +
                "is ideal for birdwatchers and offers superb views of the surrounding area,  " +
                "including Castlemaine Harbour, Slieve Mish Mountains and the McGillycuddy Reeks." +
                "<p><b>Time</b> 1 Hours " +
                "<p><b>Distance</b> 3.9 KM " +
                "<p><b>Ascent</b> 11 M " +
                "<p><b>Difficulty</b>Moderate"
            "</div>" +
            "</div>";
            const CMLinfowindow = new google.maps.InfoWindow({
                content: CMLcontentString,
            });
            const CMLmarker = new google.maps.Marker({
                position: CaherMarshLoop,
                map,
                title: "Caher Marsh Loop",
            });
            CMLmarker.addListener("click", () => {
                CMLinfowindow.open(map, CMLmarker);
            });

            const SliabhLiagPilgrimPath = {
                lat: 54.648301,
                lng: -8.693326
            };

            const SLPPcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Sliabh Liag Pilgrim Path</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Sliabh Liag Pilgrim Path </b> " +
                "<p><b>Time</b> 1.5 Hours " +
                "<p><b>Distance</b> 8 km" +
                "<p><b>Ascent</b> 425 m " +
                "<p><b>Difficulty</b>Moderate"
            "</div>" +
            "</div>";
            const SLPPinfowindow = new google.maps.InfoWindow({
                content: SLPPcontentString,
            });
            const SLPPmarker = new google.maps.Marker({
                position: SliabhLiagPilgrimPath,
                map,
                title: "Sliabh Liag Pilgrim Path",
            });
            SLPPmarker.addListener("click", () => {
                SLPPinfowindow.open(map, SLPPmarker);
            });

            const MurriskLoop = {
                lat: 53.77811,
                lng: -9.639078
            };

            const MLcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Murrisk Loop</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Murrisk Loop</b> " +
                "This walk follows a combination of mountain path, " +
                "lane and footpath around the lower slopes of Croagh Patrick with spectacular views over Clew Bay as well " +
                "as local sites of historical and archaeological interest." +
                "<p><b>Time</b> 2.5 Hours " +
                "<p><b>Distance</b> 4.3 km" +
                "<p><b>Ascent</b> 114 m " +
                "<p><b>Difficulty</b>Moderate"
            "</div>" +
            "</div>";
            const MLinfowindow = new google.maps.InfoWindow({
                content: MLcontentString,
            });
            const MLmarker = new google.maps.Marker({
                position: MurriskLoop,
                map,
                title: "Murrisk Loop",
            });
            MLmarker.addListener("click", () => {
                MLinfowindow.open(map, MLmarker);
            });

            const Shannonbridge = {
                lat: 53.279465,
                lng: -8.04862
            };

            const SBcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Shannonbridge - River Shannon loop</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Shannonbridge - River Shannon loop</b> " +
                "The River Shannon Loop takes the walker north along the banks of the Shannon " +
                "and back by a quiet bog road and lane passing alongside a section of woodland " +
                "at the northern end..Features of interest include the imposing Napoleonic " +
                "Fortifications and the long bridge spanning the Shannon River." +
                "<p><b>Time</b> 2 Hours " +
                "<p><b>Distance</b> 5.2 km" +
                "<p><b>Ascent</b> 0 m " +
                "<p><b>Difficulty</b>Moderate"
            "</div>" +
            "</div>";
            const SBinfowindow = new google.maps.InfoWindow({
                content: SBcontentString,
            });
            const SBmarker = new google.maps.Marker({
                position: Shannonbridge,
                map,
                title: "Shannonbridge - River Shannon loop",
            });
            SBmarker.addListener("click", () => {
                SBinfowindow.open(map, SBmarker);
            });

            const LochDhoireBhileloop = {
                lat: 52.61987,
                lng: -7.68078
            };

            const LDBLcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Loch Dhoire Bhile loop</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Loch Dhoire Bhile loop</b> " +
                "Nestling under the Slieveardagh Hills this loop follows bog roadways and green " +
                "tracks along streams and lakeshore through a redeveloped area of old Bord na " +
                "Mona peatland which has become a wildlife sanctuary, particularly for birds. " +
                "There is wheelchair access to a shorter loop of the lake." +
                "<p><b>Time</b> 1.25 Hours " +
                "<p><b>Distance</b> 3 km" +
                "<p><b>Ascent</b> 130 m " +
                "<p><b>Difficulty</b>Easy"
            "</div>" +
            "</div>";
            const LDBLinfowindow = new google.maps.InfoWindow({
                content: LDBLcontentString,
            });
            const LDBLmarker = new google.maps.Marker({
                position: LochDhoireBhileloop,
                map,
                title: "Loch Dhoire Bhile loop",
            });
            LDBLmarker.addListener("click", () => {
                LDBLinfowindow.open(map, LDBLmarker);
            });

            const MullaghmeenMultiAccessTrail = {
                lat: 53.7488691,
                lng: -7.2731651
            };

            const MMATcontentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Mullaghmeen Multi Access Trail</h1>' +
                '<div id="bodyContent">' +
                "<p><b>Mullaghmeen Multi Access Trail</b> " +
                "This is a linear walk up a gentle tarmac path lined on both sides with young " +
                "broad-leafed trees to a viewing point to the north east " +
                "<p><b>Time</b> 15 Minutes " +
                "<p><b>Distance</b> 0.2 km" +
                "<p><b>Ascent</b> 130 m " +
                "<p><b>Difficulty</b>Multi-Access"
            "</div>" +
            "</div>";
            const MMATinfowindow = new google.maps.InfoWindow({
                content: MMATcontentString,
            });
            const MMATmarker = new google.maps.Marker({
                position: MullaghmeenMultiAccessTrail,
                map,
                title: "Mullaghmeen Multi Access Trail",
            });
            MMATmarker.addListener("click", () => {
                MMATinfowindow.open(map, MMATmarker);
            });


        }





        class AutocompleteDirectionsHandler {
            constructor(map) {
                this.map = map;
                this.originPlaceId = "";
                this.destinationPlaceId = "";
                this.travelMode = google.maps.TravelMode.WALKING;
                this.directionsService = new google.maps.DirectionsService();
                this.directionsRenderer = new google.maps.DirectionsRenderer();
                this.directionsRenderer.setMap(map);
                const originInput = document.getElementById("origin-input");
                const destinationInput = document.getElementById("destination-input");
                const modeSelector = document.getElementById("mode-selector");
                const originAutocomplete = new google.maps.places.Autocomplete(
                    originInput
                );
                // Specify just the place data fields that you need.
                originAutocomplete.setFields(["place_id"]);
                const destinationAutocomplete = new google.maps.places.Autocomplete(
                    destinationInput
                );
                // Specify just the place data fields that you need.
                destinationAutocomplete.setFields(["place_id"]);
                this.setupClickListener(
                    "changemode-walking",
                    google.maps.TravelMode.WALKING
                );
                this.setupClickListener(
                    "changemode-transit",
                    google.maps.TravelMode.TRANSIT
                );
                this.setupClickListener(
                    "changemode-driving",
                    google.maps.TravelMode.DRIVING
                );
                this.setupPlaceChangedListener(originAutocomplete, "ORIG");
                this.setupPlaceChangedListener(destinationAutocomplete, "DEST");
                this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(
                    originInput
                );
                this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(
                    destinationInput
                );
                this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(
                    modeSelector
                );
                this.getDistance();
            }

            getDistance(originPlaceId, destinationPlaceId) {
                    //Find the distance
                    var distanceService = new google.maps.DistanceMatrixService();
                    var srcLocation = [constructor.originInput]
                    var dstLocation = [constructor.destinationInput]
                    distanceService.getDistanceMatrix({
                            origins: srcLocation,
                            destinations: dstLocation,
                            travelMode: google.maps.TravelMode.WALKING,
                            unitSystem: google.maps.UnitSystem.METRIC,
                            durationInTraffic: true,
                            avoidHighways: false,
                            avoidTolls: false
                        },
                        function(response, status) {
                            if (status !== google.maps.DistanceMatrixStatus.OK) {
                                console.log('Error:', status);
                            } else {
                                console.log(response);
                                $("#distance").text(response.rows[0].elements[0].distance.text).show();
                                $("#duration").text(response.rows[0].elements[0].duration.text).show();
                            }
                        });
                }
                // Sets a listener on a radio button to change the filter type on Places
                // Autocomplete.
            setupClickListener(id, mode) {
                const radioButton = document.getElementById(id);
                radioButton.addEventListener("click", () => {
                    this.travelMode = mode;
                    this.route();
                });
            }
            setupPlaceChangedListener(autocomplete, mode) {
                autocomplete.bindTo("bounds", this.map);
                autocomplete.addListener("place_changed", () => {
                    const place = autocomplete.getPlace();

                    if (!place.place_id) {
                        window.alert("Please select an option from the dropdown list.");
                        return;
                    }

                    if (mode === "ORIG") {
                        this.originPlaceId = place.place_id;
                    } else {
                        this.destinationPlaceId = place.place_id;
                    }
                    this.route();
                });
            }
            route() {
                if (!this.originPlaceId || !this.destinationPlaceId) {
                    return;
                }
                const me = this;
                this.directionsService.route({
                        origin: {
                            placeId: this.originPlaceId
                        },
                        destination: {
                            placeId: this.destinationPlaceId
                        },
                        travelMode: this.travelMode,
                    },
                    (response, status) => {
                        if (status === "OK") {
                            me.directionsRenderer.setDirections(response);
                        } else {
                            window.alert("Directions request failed due to " + status);
                        }
                    }
                );
            }
            showPanel(marker) {
                // If infoPane is already open, close it
                if (infoPane.classList.contains("open")) {
                    infoPane.classList.remove("open");
                }

                infoPane.classList.add("open");
            }
        }
    </script>
</head>

<body>
    <div style="display: none">
        <input id="origin-input" class="controls" type="text" placeholder="Enter an origin location" />

        <input id="destination-input" class="controls" type="text" placeholder="Enter a destination location" />

        <div id="mode-selector" class="controls">
            <input type="radio" name="type" id="changemode-walking" checked="checked" />
            <label for="changemode-walking">Walking</label>

            <input type="radio" name="type" id="changemode-transit" />
            <label for="changemode-transit">Transit</label>

            <input type="radio" name="type" id="changemode-driving" />
            <label for="changemode-driving">Driving</label>


        </div>
    </div>

    <div id="panel"></div>
    <div id="map"></div>
</body>

</html>