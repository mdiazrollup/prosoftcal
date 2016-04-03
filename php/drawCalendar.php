<?php
include('calendar.php');
$calendar = new Calendar($_POST["starttime"],$_POST["totaldays"],$_POST["countrycode"]);
echo($calendar->draw());
?>