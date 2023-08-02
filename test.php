<?php
include_once('head.php');
date_default_timezone_set('Asia/Seoul');
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

$posted = date("Y-m-d H:i:s");
$name = $_POST["name"];
$phone = substr($_POST["phone"], 0, 3) ."-" .substr($_POST["phone"], 3, 4) ."-" .substr($_POST["phone"], 7, 4);
$birth_date = $_POST["birth_date"];
$address = $_POST["address1"];
$location = $_POST["location"];
$recommender = $_POST["recommender"];
$recommender_name = $_POST["recommender_name"];
$apply_date = substr($_POST["apply_date"], 0 , 10);
$apply_time = $_POST["apply_time"];

$apply_locate = $_POST["apply_locate"];
$apply_name = $_POST["apply_name"];
$apply_address = $_POST["apply_address"];
$apply_subway = str_replace('<br>', " / " ,$_POST["apply_subway"]);;

$rdate = date('Y-m-d',strtotime($apply_date."-1 day"));

$rdate_val = substr($rdate, 0, 4) .substr($rdate, 5, 2) .substr($rdate, 8, 2);
echo $rdate_val;
;
?>
