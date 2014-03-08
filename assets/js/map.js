/* -------------------------------------------------------------------------------------------------------------------------- */
/* ----------------------------------------------------- Map functions ------------------------------------------------------ */
/* -------------------------------------------------------------------------------------------------------------------------- */
// Create a map in the "map" div, set the view to a given place and zoom
var map = L.map('map').setView([46.72480037466717, 2.669677734375], 13).on('click', onMapClick2);

function onMapClick2(e) {
    angular.element($('#step3')).scope().onMapClick2(e)
}


document.getElementById('map').oncontextmenu = function () { // Unable right click on the map
    return false;
}

// Add an OpenStreetMap tile layer
var tileLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var ExpandControl = L.Control.extend({
    options: {
        position: 'topright',
        expandText: "",
        expandTitle: "Agrandir la carte"
    },
    onAdd: function (t) {
        var e = "leaflet-control-expand",
            i = L.DomUtil.create("div", e + " leaflet-bar");
        return this._map = t,
        this._expandButton = this._createButton(this.options.expandText, this.options.expandTitle, e, i, this._expand, this),
        t.on("_expand", this._updateDisabled, this), i
    },
    _expand: function () {
        $('nav').fadeOut(300);
        setTimeout(function () {
            $('div.content').animate({
                width: '100%'
            }, 300, function () {
                $('div#map').animate({
                    height: '420px'
                }, 300, function () {
                    expandCtrl.removeFrom(map);
                    map.addControl(shrinkCtrl);
                })
            });
            setTimeout(function () {
                map.invalidateSize(false);
            }, 200);
        }, 300);
    },
    _createButton: function (t, e, i, n, s, a) {
        var r = L.DomUtil.create("a", i, n);
        r.innerHTML = t, r.href = "#", r.title = e;
        var h = L.DomEvent.stopPropagation;
        return L.DomEvent.on(r, "click", h).on(r, "mousedown", h).on(r, "dblclick", h).on(r, "click", L.DomEvent.preventDefault).on(r, "click", s, a).on(r, "click", this._refocusOnMap, a), r
    }
});

var ShrinkControl = L.Control.extend({
    options: {
        position: 'topright',
        expandText: "",
        expandTitle: "Rétrécir la carte"
    },
    onAdd: function (t) {

        var e = "leaflet-control-shrink",
            i = L.DomUtil.create("div", e + " leaflet-bar");
        return this._map = t,
        this._expandButton = this._createButton(this.options.expandText, this.options.expandTitle, e, i, this._shrink, this), i
    },
    _shrink: function () {
        $('div#map').animate({
            height: '300px'
        }, 300, function () {
            $('div.content').animate({
                width: '72.65625%',

            }, 300, function () {
                $('nav').fadeIn(300, function () {
                    shrinkCtrl.removeFrom(map);
                    map.addControl(expandCtrl);
                });

            });
        });
    },
    _createButton: function (t, e, i, n, s, a) {
        var r = L.DomUtil.create("a", i, n);
        r.innerHTML = t, r.href = "#", r.title = e;
        var h = L.DomEvent.stopPropagation;
        return L.DomEvent.on(r, "click", h).on(r, "mousedown", h).on(r, "dblclick", h).on(r, "click", L.DomEvent.preventDefault).on(r, "click", s, a).on(r, "click", this._refocusOnMap, a), r
    }
});

var expandCtrl = new ExpandControl();
var shrinkCtrl = new ShrinkControl();
map.addControl(expandCtrl);








//$('.my-custom-control').append('<a href="#" id="leaflet-control-expand" href="#" title="Étendre la carte"></a>');

//$(document.body).on('click', '#leaflet-control-expand', function () {
//    $('nav').fadeOut(300);
//    $(this).replaceWith('<a href="#" id="leaflet-control-shrink" href="#" title="Rétrécir la carte"></a>');
//    setTimeout(function () {
//        $('div.content').animate({
//            width: '100%'
//        }, 300)
//
//    }, 300);
//    map.invalidateSize(false);
//});
//$(document.body).on('click', '#leaflet-control-shrink', function () {
//    $(this).replaceWith('<a href="#" id="leaflet-control-expand" href="#" title="Étendre la carte"></a>');
//
//    $('div.content').animate({
//        width: '72.65625%'
//    }, 300)
//    setTimeout(function () {
//
//        $('nav').fadeIn(300);
//    }, 300);
//    map.invalidateSize(false);
//});




var greenIcon = L.icon({
    iconUrl: base_url + "assets/images/green-marker.png",
    iconRetinaUrl: base_url + "assets/images/green-marker-x2.png",
    shadowUrl: base_url + "assets/images/marker-shadow.png",
    shadowRetinaUrl: base_url + "assets/images/marker-shadow-x2.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

var redIcon = L.icon({
    iconUrl: base_url + "assets/images/red-marker.png",
    iconRetinaUrl: base_url + "assets/images/red-marker-x2.png",
    shadowUrl: base_url + "assets/images/marker-shadow.png",
    shadowRetinaUrl: base_url + "assets/images/marker-shadow-x2.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});



var app = angular.module('RandOSM-CreateHike', ['ngAnimate']);

app.controller('StepsCtrl', function ($scope) {
    $scope.steps = [];

    $scope.onMapClick2 = function (e) {
        var marker = L.marker(e.latlng, {
            draggable: 'true'
        }).on('contextmenu', $scope.onMarkerRightClick).on('mouseover', onMarkerHover).on('dragend', refreshLine);

        newStep = {
            "marker": marker,
            "comment": ""
        };

        $scope.$apply($scope.steps.push(newStep));

        var el = $('#steps'),
            curHeight = el.height(),
            autoHeight = el.css('height', 'auto').height();
        el.height(curHeight).animate({
            height: autoHeight
        }, 200, "swing");

        angular.forEach($scope.steps, function (value) {
            value.marker.addTo(map);
        });

        refreshLine();
    }


    function onMarkerHover() {
        var m = this;
        angular.forEach($scope.steps, function (value) {
            if (value.marker.getLatLng() == m.getLatLng()) {
                var index = ($scope.steps.indexOf(value)) + 1;
                m.bindPopup("<strong>Étape n<sup>o</sup>" + index + "</strong>").openPopup();
            }
        });
    }


    $scope.onMarkerRightClick = function (e) {
        var m = this;
        angular.forEach($scope.steps, function (value) {
            if (value.marker.getLatLng() == m.getLatLng()) {
                map.removeLayer(value.marker);
                var i = $scope.steps.indexOf(value);
                $scope.$apply($scope.steps.splice(i, 1));

                var el = $('#steps'),
                    h = (el.height()) - 80;
                el.delay(950).animate({
                    height: h
                }, 50, "swing");
            }
        });
        refreshLine();
    }


    function refreshLine() {
        if (typeof polyline != 'undefined') {
            map.removeLayer(polyline);
        }
        var markersPositions = new Array();
        for (var i = 0; i < $scope.steps.length; i++) {
            markersPositions[i] = $scope.steps[i].marker.getLatLng();
        }
        polyline = L.polyline(markersPositions, {
            color: 'red'
        }).addTo(map);
    }

    /* -------------------------------------------------------------------------------------------------------------------------- */
    /* ------------------------------------------- Hike sending to database functions ------------------------------------------- */
    /* -------------------------------------------------------------------------------------------------------------------------- */

    $scope.sendLength = function () { // Calcul de la distance totale de la randonnée et la met dans le champ "distance" de l'étape 4.
        var totalLength = 0;
        if ($scope.steps[0]) {
            for (var i = 0; i < $scope.steps.length - 1; i++) {
                totalLength += $scope.steps[i].marker._latlng.distanceTo($scope.steps[i + 1].marker._latlng);
            }
            totalLength = Math.round(totalLength / 10) / 100; // Résultat en Kilomètres et arrondi à deux chiffres après la virgule.
        }
        document.getElementById('length').value = totalLength;
    }


    $scope.postInfos = function () {
        console.log("----- postInfos() -----");
        upload();
        if ($scope.steps[0]) {
            // Récupère les données utiles pour chaque étape de la randonnée
            $scope.markers = [];
            angular.forEach($scope.steps, function (value) {
                marker = {
                    "lat": value.marker._latlng.lat,
                    "lng": value.marker._latlng.lng,
                    "comment": value.comment
                };
                $scope.markers.push(marker);
            });
        }


        var dataString = $("#create-hike-form").serialize();

        var data = {
            dataString: dataString,
            markers: JSON.stringify($scope.markers)
        };
        console.log("--------------------- on fait la requete ajax ---------------------");
        $.ajax({
            url: base_url + "create_hike/create",
            data: data,
            type: 'post',
            success: function (data) {
                //  console.log(data);
                document.location = base_url + "my_hikes";
            },
            error: function (data) {
                alert("Erreur :/")
                console.log("ajax error :");
                console.log(data);
            }
        });
    }
});



function buildTmpZip() {
    console.log("----- buildTmpZip() -----");
    $.ajax({
        url: base_url + "create_hike/build_tmp_zip",
        type: 'post'
    });
}