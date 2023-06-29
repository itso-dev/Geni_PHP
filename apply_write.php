<?php
include_once('head.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

$secretKey = "6Lf0d90mAAAAALdfOB6-x61SMRrm8AlEvzUwMtK-";
$url = "https://www.google.com/recaptcha/api/siteverify";
$post = array(
    "secret" => $secretKey,
    "response" => $_POST['g-recaptcha-response'],
    "remoteip" => $_SERVER['REMOTE_ADDR']
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close ($ch);
$rst = json_decode($result, true);

if($rst['success'] != 1){
    alert("비정상적인 접근입니다.");
    exit;
}

$posted = date("Y-m-d H:i:s");
$name = $_POST["name"];
$phone = $_POST["phone"];
$birth_date = $_POST["birth_date"];
$address = $_POST["address1"].' '.$_POST["address2"];
$location = $_POST["location"];
$recommender = $_POST["recommender"];
$recommender_name = $_POST["recommender_name"];
$agree = $_POST["agree"];

$writer_ip = $_POST["writer_ip"];

$sql = "insert into contact_tbl
            (name, phone, birth_date, address, location, recommender, recommender_name, agree, result_status,
             consult_fk, manager_fk, writer_ip, write_date)
        value
            (?, ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?)";

$db_conn->prepare($sql)->execute(
    [
        $name,
        $phone,
        $birth_date,
        $address,
        $location,
        $recommender,
        $recommender_name,
        $agree,
        '대기',
        0,
        0,
        $writer_ip,
        $posted
    ]
);


$contact_cnt_sql = "insert into contact_log_tbl
                                  (contact_cnt,  reg_date)
                             value
                                  (? ,?)";


$db_conn->prepare($contact_cnt_sql)->execute(
    [1, $posted]
);

echo "<script type='text/javascript'>";
echo "alert('등록 되었습니다.');";
echo "try{";
echo "setTimeout(function(){";
echo "location.href = '/index.php';";
echo "}, 500);";
echo "}catch(e){}";
echo "</script>";


?>
