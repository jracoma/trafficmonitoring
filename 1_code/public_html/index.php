<?php
/* Traffic Monitor Index Page
 * Display main user interface
 * Accept Inputs:
 * - Starting Location
 * - Destination Location
 * - Time of Departure
 * - Date (Optional)
 * - Set Alert
 * - Alert Method (Optional)
 * - Email Address/Phone Number
 * Calculates best route using Google Maps API and provides historical data */
include 'db_connect.php';
include 'functions.php';
date_default_timezone_set('America/New_York');
$nl = "<br /><br />";

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>Traffic Monitoring - Spring 2014</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.css" media="screen">
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
    	#mapCanvas {
    		width: 795px;
    		height: 580px;
    	}
    	body { padding-top: 0px; }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>
    	$(document).ready(function() {
    		$("#inputPanel").show();
    		$("#emailAddress").hide();
    		$("#phoneNumber").hide();

    		$("#getDirections").validate({
    			rules: {
    				gphoneNumber: {
    					required: true,
    					phoneUS: true
    				},
    				gemailAddress: {
    					required: true,
    					email: true
    				}
    			}
    		});

    		var swCorner = new google.maps.LatLng(40.461404, -74.50699);
    		var neCorner = new google.maps.LatLng(40.9948872, -73.6813134);
    		var bounds = new google.maps.LatLngBounds(swCorner, neCorner);

    		/* Verifies address in coverage area */
    		$("#gstartingLocation").focusout(function() {
    			$("#rstartingLocation").text($(this).val());
    			var address = document.getElementById('gstartingLocation').value;
    			$.when(getAddress(address, "#estartingLocation")).then(inCoverageArea).then(checkResults);
    		});
    		$("#gdestinationLocation").focusout(function() {
    			$("#rdestinationLocation").text($(this).val());
    			var address = document.getElementById('gdestinationLocation').value;
    			$.when(getAddress(address, "#edestinationLocation")).then(inCoverageArea).then(checkResults);
    		});

    		/* Remove When Done */
    		// $("#gdepartureTime").focusout(function() {
    		// 	$("#rdepartureTime").text($(this).val());
    		// });
    		// $("#gdepartureDate").focusout(function() {
    		// 	$("#rdepartureDate").text($(this).val());
    		// });
    		// $("#gemailAddress").focusout(function() {
    		// 	$("#remailAddress").text($(this).val());
    		// });
    		// $("#gphoneNumber").focusout(function() {
    		// 	$("#rphoneNumber").text($(this).val());
    		// });
    		/* To Here */

    		jQuery.validator.addMethod("phoneUS", function (phone_number, element) {
    			phone_number = phone_number.replace(/\s+/g, "");
    			return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
    		}, "Please specify a valid phone number");

    		$("#getDirections").keydown(function(e) {
    			if (e.which == 13) {
    				event.preventDefault();
    			}
    		});

    		$("#getDirections").submit(function(event) {
    			var address = document.getElementById('gstartingLocation').value;
    			event.preventDefault();

    			var check = 0;

    			$.when($.when(getAddress(address, "#estartingLocation")).then(inCoverageArea).then(function(results) {
    				if (results[0] == true) {
    					address = document.getElementById('gdestinationLocation').value;

    					$.when(getAddress(address, "#edestinationLocation")).then(inCoverageArea).then(function(results) {
    						if (results[0] == true) {
    							calcRoute();
    						} else {
    							$(results[1]).show();
    						}
    					});
    				} else {
    					$(results[1]).show();

    					$.when(getAddress(address, "#edestinationLocation")).then(inCoverageArea).then(function(results1) {
    						if (results1[0] == false) {
    							$(results1[1]).show();
    						}
    					});
    				}
    			}));
    		});

    		$("#reset").click(function() {
    			$("#inputPanel").show();
    			$("#directionsPanel").hide();
    		});
    	});

    	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
    		xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		var latValues = [40.9948872, 40.94153888, 40.88819056, 40.83484224, 40.78149392, 40.7281456, 40.67479728, 40.62144896, 40.56810064, 40.51475232, 40.461404];
		var lngValues = [-74.50699, -74.42442234, -74.34185468, -74.25928702, -74.17671936, -74.0941517, -74.01158404, -73.92901638, -73.84644872, -73.76388106, -73.6813134];

		var map;
		var geocoder = new google.maps.Geocoder();
		var directionsDisplay;
		var directionsService = new google.maps.DirectionsService();

		function initialize() {
			directionsDisplay = new google.maps.DirectionsRenderer();
			var mapOptions = {
				zoom: 10,
				mapTypeControl: false,
				panControl: false,
				scaleControl: false,
				zoomControl: true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL,
					position: google.maps.ControlPosition.TOP_LEFT
				},
				center: new google.maps.LatLng(40.728145600000005, -74.0941517)
			};
			map = new google.maps.Map(document.getElementById('mapCanvas'),
				mapOptions);

			directionsDisplay.setMap(map);
			directionsDisplay.setPanel(document.getElementById('directionsUnit'));

			var swCorner = new google.maps.LatLng(40.461404, -74.50699);
			var neCorner = new google.maps.LatLng(40.9948872, -73.6813134);
			var bounds = new google.maps.LatLngBounds();
			// bounds.extend(swCorner);
			// bounds.extend(neCorner);
			// map.fitBounds(bounds);

		    /* Bounding Box:
		     * NW: 40.9948872, -74.50699
		     * NE: 40.9948872, -73.6813134
		     * SE: 40.461404, -73.6813134
		     * SW: 40.461404, -74.50699 */
		     var CoverageAreaCoords = [
		     new google.maps.LatLng(40.9948872, -74.50699),
		     new google.maps.LatLng(40.9948872, -73.6813134),
		     new google.maps.LatLng(40.461404, -73.6813134),
		     new google.maps.LatLng(40.461404, -74.50699)
		     ];
		     var CoverageArea = new google.maps.Polygon({
		     	path: CoverageAreaCoords,
		     	geodesic: true,
		     	strokeColor: '#000000',
		     	strokeOpacity: 1.0,
		     	strokeWeight: 2,
		     	fillColor: '#FFFFFF'
		     });
		     CoverageArea.setMap(map);
		 }

		 google.maps.event.addDomListener(window, 'load', initialize);

		 /* Validator for startingLocation & destinationLocation */
		 function checkResults(results) {
		 	// $("#debuggo").text(results[0] + " " + results[1]);
		 	if (results[0] == false) {
		 		$(results[1]).show();
		 	} else {
		 		$(results[1]).hide();
		 	}
		 }

		 /* Geocode address */
		 function getAddress(startingLocation, location) {
		 	var deferred = $.Deferred();
		 	var geocoder = new google.maps.Geocoder();
		 	geocoder.geocode({
		 		address: startingLocation
		 	}, function(results, status) {
		 		if (status == google.maps.GeocoderStatus.OK) {
		 			return deferred.resolve(results[0].geometry.location, location);
		 		}
		 		return deferred.promise();
		 	});

		 	return deferred.promise();
		 }

		 /* Verifies location inside coverage area */
		 function inCoverageArea(address, location) {
		 	var swCorner = new google.maps.LatLng(40.461404, -74.50699);
		 	var neCorner = new google.maps.LatLng(40.9948872, -73.6813134);
		 	var bounds = new google.maps.LatLngBounds(swCorner, neCorner);
		 	return [bounds.contains(address), location];
		 }

		 /* Calculate route between two locations */
		 function calcRoute() {
		 	var start = document.getElementById('gstartingLocation').value;
		 	var end = document.getElementById('gdestinationLocation').value;

		 	var request = {
		 		origin: start,
		 		destination: end,
		 		travelMode: google.maps.TravelMode.DRIVING,
		 		provideRouteAlternatives: true
		 	};

		 	directionsService.route(request, function(response, status) {
		 		if (status == google.maps.DirectionsStatus.OK) {
		 			$("#inputPanel").hide();
		 			$("#directionsPanel").show();
		 			console.log(response.routes.length);
		 			// $("#debuggo").text(response.routees.length);
		 			directionsDisplay.setDirections(response);
		 		}
		 	});
		 }

		 /* Toggle between Email/Phone Entries */
		 function toggleMethod() {
		 	$(document).ready(function() {
				$("#ralertMethod").text($("#galertMethod").val()); // Debug
				if ($("#galertMethod").val() == 'text') {
					$("#emailAddress").hide();
					$("#phoneNumber").show();
				} else {
					$("#phoneNumber").hide();
					$("#emailAddress").show();
				}
			});
		 }

		 /* Enable AlertMethod option */
		 function toggleAlert() {
		 	$(document).ready(function() {
		 		if ($("#gsaveAlert").is(":checked")) {
					$("#rsaveAlert").toggle(); // Debug
					$("#galertMethod").prop('disabled', false);
					$("#emailAddress").show();
					$("#ralertMethod").text($("#galertMethod").val()); // Debug
				} else {
					$("#rsaveAlert").toggle(); // Debug
					$("#ralertMethod").text(''); // Debug
					$("#emailAddress").hide();
					$("#phoneNumber").hide();
					$("#galertMethod").prop('disabled', true);
				}
			});
		 }

		</script>
	</head>

	<body>
		<!-- Begin Navbar -->
		<div class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a href="../" class="navbar-brand">Traffic Monitoring</a>
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar-main">
					<ul class="nav navbar-nav">
						<li>
							<a href="../help/">Alerts</a>
						</li>
						<li>
							<a href="http://news.bootswatch.com">More Stuff</a>
						</li>
					</ul>

					<ul class="nav navbar-nav navbar-right">
						<li><a href="http://builtwithbootstrap.com/" target="_blank">Signup</a></li>
						<li><a href="http://builtwithbootstrap.com/" target="_blank">Login</a></li>
					</ul>

				</div>
			</div>
		</div>
		<!-- End Navbar -->

		<!-- Main Body -->

		<!-- Top Row - GetDirections & Map -->
		<div class="container">
			<div class="jumbotron">
				<div class="bs-docs-grid">

					<div class="row">
						<div class="col-md-12">
							<h2>Get Directions</h2>
							<div style="float: left; width: 65%; margin: 0 auto;" id="mapCanvas"></div>
							<div style="display: none; float: right; width: 30%; margin: 0 auto;" id="inputPanel">
								<form class="form-horizontal" name="getDirections" id="getDirections" method="GET" role="form">
									<label for="gstartingLocation">Starting Location:</label>
									<input type="text" class="form-control" id="gstartingLocation" name="startingLocation" placeholder="Address/Zip Code" minlength="5" required />
									<label id="estartingLocation" class="error" style="display:none">Please enter a location in the coverage area.</label>
									<label for="gdestinationLocation">Destination Location:</label>
									<input type="text" class="form-control" id="gdestinationLocation" name="destinationLocation" placeholder="Address/Zip Code" minlength="5" required />
									<label id="edestinationLocation" class="error" style="display:none">Please enter a location in the coverage area.</label>
									<label for="gdepartureTime">Departure Time:</label>
									<?php
									$now = getNearest10();
									echo '<input type="time" class="form-control" id="gdepartureTime" name="departureTime" value="' . $now . '" step="600"/>';
									?>
									<label for="gdepartureDate">Departure Date: <small><i>+10 Days Max</i></small></label>
									<?php
									$today = Date('Y-m-d');
									$plus10 = Date('Y-m-d', strtotime("+14 days"));
									echo '<input type="date" class="form-control" id="gdepartureDate" name="departureDate" value="' . $today . '" min="' . $today . '" max="' . $plus10 . '"/>';
									?>
									<div style="float: left; width: 32%; margin: 0 auto;">
										<label for="gsaveAlert">Set Alert?</label>
										<input type="checkbox" id="gsaveAlert" name="saveAlert" value="saveAlert" onchange="toggleAlert();">
									</div>
									<div style="float: right; width: 68%; margin: 0 auto;">
										<label for="galertMethod">Alert Method:</label>
										<select id="galertMethod" onchange="toggleMethod();" disabled>
											<option value="email">Email</option>
											<option value="text">Text Message</option>
										</select>
									</div>
									<div id="emailAddress">
										<label for="gemailAddress">Email Address:</label>
										<input class="form-control" id="gemailAddress" name="gemailAddress" placeholder="Email Address">
									</div>
									<div id="phoneNumber">
										<label for="gphoneNumber">Phone Number:</label>
										<input class="form-control" id="gphoneNumber" name="gphoneNumber" placeholder="Phone Number">
									</div>
									<div style="height: 25px;"></div>
									<div class="text-right">
										<button type="submit" class="btn btn-primary btn-sm">Submit</button>
									</div>
								</form>
							</div>
							<div class="row" style="display: none; float: right; width: 30%; margin: 0 auto;" id="directionsPanel">
								<div id="directionsUnit"></div>
								<div class="text-right"><input type="button" class="btn btn-primary btn-sm" id="reset" value="Reset"></div>
							</div>
						</div>
					</div>

					<!-- <div style="height: 20px;"></div>
					<div class="row">
						<div class="col-md-4 col-md-offset-4 text-center">
							***FOR DEBUGGING***<br><br>
							Starting Location: <div id="rstartingLocation"></div>
							Destination Location: <div id="rdestinationLocation"></div>
							Departure Time: <div id="rdepartureTime"></div>
							Departure Date: <div id="rdepartureDate"></div>
							<div id="rsaveAlert" style="display:none">Save Alert?: Yes</div>
							Alert Method: <div id="ralertMethod"></div>
							Email Address: <div id="remailAddress"></div>
							DEBUG: <div id="debuggo"></div>
						</div>
					</div> -->
				</div>
			</div>
		</div>
		<!-- End Top Row - GetDirections & Map -->

		<!-- End Main Body -->
		<!-- Bootstrap core JavaScript ================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" language="javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
		<script type="text/javascript" language="javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
	</body>
	</html>