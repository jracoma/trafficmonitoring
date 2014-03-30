Traffic Monitoring
Group #13: Xuan Li, Chih-Ting Cho, Ting-Chieh Huang, Jonathan Hong, Jan Racoma, Kevin Cundey

Project Website:
	https://sites.google.com/site/452trafficmonitoring/

Traffic Monitoring Website
	http://www.jracoma.com/trafficmonitor/

1_code:
public_html:
css/									// Built from bootstrap files
	bootstrap.min.css
	bootstrap.css.map
	bootstrap.css
	bootstrap-theme.min.css
	bootstrap-theme.css.map
	bootstrap-theme.css
fonts/									// Built from bootstrap files
	glyphicons-halflings-regular.woff
	glyphicons-halflings-regular.ttf
	glyphicons-halflings-regular.svg
	glyphicons-halflings-regular.eot
js/										// Built from bootstrap files
	bootstrap.min.js
	bootstrap.js
theme.css								// Built from bootstrap file
index.php								// Main webpage UI
functions.php							// PHP functions
db_connect.php 							// DB Connection Configuration
TrafficParser.php 						// Parses traffic information
TrafficDataTest.php 					// Test output of traffic data
WeatherParser.php 						// Parses weather information

4_data_collection:
db_connect.php 							// DB Connection Configuration
WeatherParser.php 						// Parses weather information
TrafficParser.php 						// Parses traffic information
trafficData.sql 						// Table trafficData dump
weatherData.sql 						// Table weatherData dump

INSTRUCTIONS
Place inside web server with PHP and MySQL enabled. Parsers were scheduled using the cPanel UI and scheduling server to run the PHP files hourly. Both parsers use db_connect.php to interface with the MySQL database and send/receive queries for storing information. trafficData.sql and weatherData.sql contain some of the data collected thus far for viewing.

5_documentation:
Flyer.pdf
Individual Contributions.pdf
Technical Documentation.pdf
User Documentation.pdf