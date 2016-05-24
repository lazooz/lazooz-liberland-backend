
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
function initialize() {
  var mapOptions = {
    zoom: 8,
    center: new google.maps.LatLng(-25.363882,131.044922)
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

  var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
  var myLatln2 = new google.maps.LatLng(-25.403882,131.044922);

  var marker = new google.maps.Marker({
	    position: myLatlng,
	    map: map,
	    title:"Hello World!"
	});

  var marker2 = new google.maps.Marker({
	    position: myLatlng2,
	    map: map,
	    title:"Hello World!"
	});
  
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>

    <div id="map-canvas"></div>
