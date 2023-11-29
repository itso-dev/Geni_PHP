<?php

$posted = date("Y-m-d H:i:s");

$rdate_kakao = date('YmdHis',strtotime($posted."-1 day"));
$rdate_kakao_val = substr($rdate_kakao, 0, 8)."100000";

echo $rdate_kakao ."\n";
echo $rdate_kakao_val ;

?>
