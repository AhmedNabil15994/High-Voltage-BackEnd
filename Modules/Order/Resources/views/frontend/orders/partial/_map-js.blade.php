{{-- <script>
    (g => {
        var h, a, k, p = "The Google Maps JavaScript API",
            c = "google",
            l = "importLibrary",
            q = "__ib__",
            m = document,
            b = window;
        b = b[c] || (b[c] = {});
        var d = b.maps || (b.maps = {}),
            r = new Set,
            e = new URLSearchParams,
            u = () => h || (h = new Promise(async (f, n) => {
                await (a = m.createElement("script"));
                e.set("libraries", [...r] + "");
                for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                e.set("callback", c + ".maps." + q);
                a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                d[q] = f;
                a.onerror = () => h = n(Error(p + " could not load."));
                a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                m.head.append(a)
            }));
        d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
            d[l](f, ...n))
    })
    ({
        key: "{{ env('GOOGLE_MAPS_API_KEY') }}",
        v: "beta"
    });
</script>

<script>
    async function initMap1() {
        // Request needed libraries.
        const {
            Map,
            InfoWindow
        } = await google.maps.importLibrary("maps");
        const {
            AdvancedMarkerElement
        } = await google.maps.importLibrary("marker");
        const map = new Map(document.getElementById("cGoogleMap"), {
            center: {
                lat: 29.378586,
                lng: 47.990341
            },
            zoom: 14,
            mapId: "4504f8b37365c3d0",
        });
        const infoWindow = new InfoWindow();
        const draggableMarker = new AdvancedMarkerElement({
            map,
            position: {
                lat: 29.378586,
                lng: 47.990341
            },
            gmpDraggable: true,
            title: "{{ __('Choose your location') }}",
        });

        draggableMarker.addListener("dragend", (event) => {
            const position = draggableMarker.position;

            $('#c_latitude').val(position.lat);
            $('#c_longitude').val(position.lng);

            infoWindow.close();
            /* infoWindow.setContent(
                `{{ __('Your location coordinates') }}: ${position.lat}, ${position.lng}`
            );
            infoWindow.open(draggableMarker.map, draggableMarker); */
        });
    }

    async function initMap2() {
        // Request needed libraries.
        const {
            Map,
            InfoWindow
        } = await google.maps.importLibrary("maps");
        const {
            AdvancedMarkerElement
        } = await google.maps.importLibrary("marker");
        const map = new Map(document.getElementById("eGoogleMap"), {
            center: {
                lat: {{ $address->latitude ?? '29.378586' }} ,
                lng: {{ $address->longitude ?? '47.990341' }}
            },
            zoom: {{ isset($address->latitude) ? 20 : 14}},
            mapId: "4504f8b37365c3d0",
        });
        const infoWindow = new InfoWindow();
        const draggableMarker = new AdvancedMarkerElement({
            map,
            position: {
                lat: {{ $address->latitude ?? '29.378586' }},
                lng: {{ $address->longitude ?? '47.990341' }}
            },
            gmpDraggable: true,
            title: "{{ __('Choose your location') }}",
        });

        draggableMarker.addListener("dragend", (event) => {
            const position = draggableMarker.position;

            $('#e_latitude').val(position.lat);
            $('#e_longitude').val(position.lng);

            infoWindow.close();
            /* infoWindow.setContent(
                // `{{ __('The address has been selected') }}`
                `{{ __('Your location coordinates') }}: ${position.lat}, ${position.lng}`
            );
            infoWindow.open(draggableMarker.map, draggableMarker); */
        });
    }

    initMap1();
    initMap2();
</script> --}}

<?php $google_places_key =  env('GOOGLE_MAPS_API_KEY') ;?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{$google_places_key}}&libraries=places&language=ar"></script>
<script type="text/javascript">


    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
        (position) => {
            console.log(position)
            var map; var marker;
            var geocoder  = new google.maps.Geocoder();
            // infoWindow.setPosition(pos);
            // infoWindow.setContent("Location found.");
            // infoWindow.open(map);
            // var x= position.coords.latitude;
            myLatlng= new google.maps.LatLng( position.coords.latitude,  position.coords.longitude);
                var mapOptions = {
                    zoom: 14,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

            map = new google.maps.Map(document.getElementById("store_map"), mapOptions);
            marker = new google.maps.Marker({
                map: map,
                position: myLatlng,
                draggable: true
            });
            $('#lat').val(marker.getPosition().lat());
            $('#lng').val(marker.getPosition().lng());

            /*start search box*/
            // Create the search box and link it to the UI element.
            var input = document.getElementById('store-search');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();
                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    marker.setPosition(place.geometry.location);
                    // $('#address').val(place.formatted_address);
                    $('#lat').val(place.geometry.location.lat());
                    $('#lng').val(place.geometry.location.lng());
                    if(place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    }else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
            /*end search box*/
            google.maps.event.addListener(marker, 'dragend', function() {
                geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                    // if (status == google.maps.GeocoderStatus.OK) {
                        // if (results[0]) {
                            // $('#address').val(results[0].formatted_address);
                            $('#lat').val(marker.getPosition().lat());
                            $('#lng').val(marker.getPosition().lng());
                        // }
                    // }
                });
            });
        },(error) => {
            var message = '{{ __('order::frontend.orders.open_location') }}';
            alert(message);
            // function initMap(){
                var map; var marker;
                var myLatlng  = new google.maps.LatLng(29.2259, 47.5892);
                var geocoder  = new google.maps.Geocoder();
                var mapOptions = {
                    zoom: 6,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                map = new google.maps.Map(document.getElementById("store_map"), mapOptions);
                marker = new google.maps.Marker({
                    map: map,
                    position: myLatlng,
                    draggable: true
                });

        /*start search box*/
                // Create the search box and link it to the UI element.
                var input = document.getElementById('store-search');
                var searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
                });
                searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();
                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                    }
                    marker.setPosition(place.geometry.location);
                    // $('#address').val(place.formatted_address);
                    $('#lat').val(place.geometry.location.lat());
                    $('#lng').val(place.geometry.location.lng());
                    if(place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                    }else {
                    bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
                });
        /*end search box*/
                google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                        // if (status == google.maps.GeocoderStatus.OK) {
                            // if (results[0]) {
                                // $('#address').val(results[0].formatted_address);
                                $('#lat').val(marker.getPosition().lat());
                                $('#lng').val(marker.getPosition().lng());
                            // }
                        // }
                    });
                });

            // }
            // google.maps.event.addDomListener(window, 'load', initMap);

        }
    );

}else{
    // function initMap(){
		var map; var marker;
		var myLatlng  = new google.maps.LatLng(29.2259, 47.5892);
		var geocoder  = new google.maps.Geocoder();
		var mapOptions = {
		    zoom: 6,
		    center: myLatlng,
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		map = new google.maps.Map(document.getElementById("store_map"), mapOptions);
		marker = new google.maps.Marker({
		    map: map,
		    position: myLatlng,
		    draggable: true
		});

/*start search box*/
        // Create the search box and link it to the UI element.
        var input = document.getElementById('store-search');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();
          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            marker.setPosition(place.geometry.location);
		    // $('#address').val(place.formatted_address);
		    $('#lat').val(place.geometry.location.lat());
		    $('#lng').val(place.geometry.location.lng());
            if(place.geometry.viewport) {
              bounds.union(place.geometry.viewport);
            }else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
/*end search box*/
		google.maps.event.addListener(marker, 'dragend', function() {
		    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
		        // if (status == google.maps.GeocoderStatus.OK) {
		            // if (results[0]) {
		                // $('#address').val(results[0].formatted_address);
		                $('#lat').val(marker.getPosition().lat());
		                $('#lng').val(marker.getPosition().lng());
		            // }
		        // }
		    });
		});

    // }
	// google.maps.event.addDomListener(window, 'load', initMap);


}

</script>
