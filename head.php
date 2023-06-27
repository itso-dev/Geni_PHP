<?php
include_once('./db/dbconfig.php');


//사이트 정보 쿼리
$site_info_sql = "select * from site_setting_tbl";
$site_info_stt=$db_conn->prepare($site_info_sql);
$site_info_stt->execute();
$site = $site_info_stt -> fetch();

?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta property="og:title" content="<?=$site[1]?>" />
    <meta property="og:description" content="<?=$site[2]?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <title><?=$site[1]?></title>

    <!-- <link rel="stylesheet" type="text/css" href="css/common.css" rel="stylesheet" /> -->

    <!-- CSS only -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <link href="https://webfontworld.github.io/SCoreDream/SCoreDream.css" rel="stylesheet">
    <script src="js/parallax.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


</head>

<body>
<div id="wrapper">
    <div id="container">
