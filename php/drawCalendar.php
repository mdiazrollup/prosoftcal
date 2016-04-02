<?php
include('calendar.php');
//printf($_POST["starttime"]);
$calendar = new Calendar($_POST["starttime"],$_POST["totaldays"],$_POST["countrycode"]);
echo($calendar->draw());
?>