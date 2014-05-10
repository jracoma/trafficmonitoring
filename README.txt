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
index.html								// Index page to register/login
functions.php							// PHP functions
db_connect.php 							// DB Connection Configuration
ManageAlerts.php 						// UI for managing alerts
Map.php 								// Main webpage UI
TrafficData.php 						// Map interface to MySQL for queries
TrafficDataTest.php 					// Test output of traffic data
TrafficParser.php 						// Parses traffic information
UserDataTest.php 						// Test output of users
WeatherParser.php 						// Parses weather information

access-contolled.php 					// Login/Register files
change-pwd.php
changed-pwd.html
confirmreg.php
login.php
logout.php
register.php
reset-pwd-link-sent.html
reset-pwd-req.php
resetpwd.php
thank-you-regd.html
thank-you.html

alerts_manage.php 						// Alerts files
alerts_page.php
delete_alerts.php
insert_alerts.php
mail_functions.php
send_alerts.php

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
Slides.pdf
Group 13 - Project Proposal.pdf
Group 13 - First Report.pdf
Group 13 - Second Report.pdf
Group 13 - Third Report.pdf
Individual Contributions.pdf
Technical Documentation.pdf
User Documentation.pdf