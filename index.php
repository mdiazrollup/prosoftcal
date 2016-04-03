<!DOCTYPE html>
<html class="">
<head>
	<title>Prosoft Calendar</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link href="css/styles.css" rel="stylesheet">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
 	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 	<script src="js/functions.js"></script>
</head>
<body>
	<section class="container instructions">
		<h2>Introduce Star Date, Number of days and Country code to draw your calendar</h2>
		<form id="calendar-form">
			<div class="groupfield start-date">
				<p class="title">Start Date</p>
				<p><input type="text" id="startdate"></p>
			</div>
			<div class="groupfield days-number">
				<p class="title">Number of Days</p>
				<p><input type="text" id="daysnumber"></p>
			</div>
			<div class="groupfield country-code">
				<p class="title">Country Code</p>
				<p><input type="text" id="countrycode" maxlength="2"></p>
			</div>
			<div class="groupfield">
				<input type="button" class="submit-btn" id="submit-cal" value="Get Calendar">
			</div>
		</form>
	</section>
	<section class="container calendar" id="result">
	</section>
</body>
</html>
