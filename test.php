<?php
include_once('head.php');
date_default_timezone_set('Asia/Seoul');
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

$posted = date("Y-m-d H:i:s");

$n_year = str_replace('-', '', substr($posted,2, 8));;
$n_time = str_replace('-', '', substr($posted,11, 2)) .str_replace('-', '', substr($posted,14, 2));
$n_order = '';

$no_sql = "SELECT count(*) as cnt FROM contact_tbl where write_date >= DATE_ADD(NOW(), INTERVAL -30 MINUTE)";
$no_stt=$db_conn->prepare($no_sql);
$no_stt->execute();
$contact_cnt = $no_stt -> fetch();

$n_order = sprintf('%02d', $contact_cnt['cnt']);
$apply_no = $n_year.$n_time.$n_order;

//지원번호 중복체크
$noChk_sql = "SELECT count(*) as cnt FROM contact_tbl where apply_num = $apply_no";
$noChk_stt=$db_conn->prepare($noChk_sql);
$noChk_stt->execute();
$noChk_cnt = $noChk_stt -> fetch();
//echo $apply_no;
if($noChk_cnt['cnt'] > 0){
    $n_order = sprintf('%02d', ++$contact_cnt['cnt']);
    $apply_no = $n_year.$n_time.$n_order;
}

//print_r($n_year.$n_time.$n_order);



?>
