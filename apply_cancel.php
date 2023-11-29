<?php
include_once('head.php');
date_default_timezone_set('Asia/Seoul');
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
$applyNo  = $_POST['applyNo'];

//문의 정보
$contact_sql = "select * from contact_tbl where apply_num = '$applyNo'";
$contact_stt=$db_conn->prepare($contact_sql);
$contact_stt->execute();
$contact_row = $contact_stt -> fetch();
$posted = date("Y-m-d H:i:s");

$msg_id = $contact_row['msg_id'];



//카카오 알림
/*
        -----------------------------------------------------------------------------------
        알림톡 토큰 생성
        -----------------------------------------------------------------------------------
        API호출 URL의 유효시간을 결정하며 URL 의 구성중 "30"은 요청의 유효시간을 의미하며, "s"는 y(년), m(월), d(일), h(시), i(분), s(초) 중 하나이며 설정한 시간내에서만 토큰이 유효합니다.
        운영중이신 보안정책에 따라 토큰의 유효시간을 특정 기간만큼 지정할 경우 매번 호출할 필요없이 해당 유효시간내에 재사용 가능합니다.
        주의하실 점은 서버를 여러대 운영하실 경우 토큰은 서버정보를 포함하므로 각 서버에서 생성된 토큰 문자열을 사용하셔야 하며 토큰 문자열을 공유해서 사용하실 수 없습니다.
        */

$_apiURL	  =	'https://kakaoapi.aligo.in/akv10/token/create/30/s/';
$_hostInfo	=	parse_url($_apiURL);
$_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
$_variables	=	array(
    'apikey' => 'msx3um4l58t21mo8zplfvp76ulctny2k',
    'userid' => 'jmpc1'
);

$oCurl = curl_init();
curl_setopt($oCurl, CURLOPT_PORT, $_port);
curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
curl_setopt($oCurl, CURLOPT_POST, 1);
curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

$ret = curl_exec($oCurl);
$error_msg = curl_error($oCurl);
curl_close($oCurl);

// 리턴 JSON 문자열 확인
//  print_r($ret . PHP_EOL);

// JSON 문자열 배열 변환
$retArr = json_decode($ret);

// 결과값 출력
//  print_r($retArr);
$token = "";
foreach ($retArr as $key => $val) {
    if($key == 'token'){
        $token = $val;
    }
}


/*
-----------------------------------------------------------------------------------
예약문자 취소
-----------------------------------------------------------------------------------
API를 통해 예약한 내역을 전송취소할 수 있습니다.
예약취소는 발송전 5분이전의 문자만 가능합니다.
*/

$_apiURL	  =	'https://kakaoapi.aligo.in/akv10/cancel/';
$_hostInfo	=	parse_url($_apiURL);
$_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
$_variables	=	array(
    'apikey' => 'msx3um4l58t21mo8zplfvp76ulctny2k',
    'userid' => 'jmpc1',
    'token'  => $token,
    'mid'    => $contact_row['k_msg_id']
);

$oCurl = curl_init();
curl_setopt($oCurl, CURLOPT_PORT, $_port);
curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
curl_setopt($oCurl, CURLOPT_POST, 1);
curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

$ret = curl_exec($oCurl);
$error_msg = curl_error($oCurl);
curl_close($oCurl);


// JSON 문자열 배열 변환
$retArr = json_decode($ret);



/*
code : 0 성공, 나머지 숫자는 에러
message : 결과 메시지
*/

//기존 예약 취소
/**************** 예약취소 예제 필독항목 ******************/
/* 예약대기중인 문자를 취소하실 수 있습니다.
/* 최대 발송5분전까지만 취소가 가능합니다.
/****************** 인증정보 시작 ******************/
$sms_url = "https://apis.aligo.in/cancel/"; // 전송요청 URL
$sms['user_id'] = "jinmobility"; // SMS 아이디
$sms['key'] = "72bsh9eyy1mtat2askdj30czfgjw2jnl";//인증키
/****************** 인증정보 끝 ********************/

/****************** 취소정보 설정시작 ****************/
$sms['mid'] = $msg_id ; // 취소할 메세지ID (필수입력)
/****************** 취소정보 설정끝 ***************/

$host_info = explode("/", $sms_url);
$port = $host_info[0] == 'https:' ? 443 : 80;

$oCurl = curl_init();
curl_setopt($oCurl, CURLOPT_PORT, $port);
curl_setopt($oCurl, CURLOPT_URL, $sms_url);
curl_setopt($oCurl, CURLOPT_POST, 1);
curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

// CURL 접속이 안되는 경우 CURL 옵션안내를 참조 하셔서 옵션을 출력하여 확인해 주세요.
// http://phpdoc.me/manual/kr/function.curl-setopt.php
$ret = curl_exec($oCurl);
curl_close($oCurl);
$retArr = json_decode($ret); // 결과배열
// print_r($retArr); - 결과값 출력

// print_r($retArr); // Response 출력 (연동작업시 확인용)

/**** Response 항목 안내 ****
// result_code : 전송성공유무 (성공:1 / 실패: -100 부터 -999)
// message : 실패시 상세사유 내용이 포함됩니다
// cancel_date : 취소일자
/**** Response 예문 끝 ****/


/*** 에러코드 ****
-801 : 메세지ID 미입력
-802 : 메세지ID 오류
-803 : 예약대기중인 문자 없음
-804 : 발송 5분전까지만 취소가능
-805 : 전송완료로 취소불가
-809 : 기타오류
/*****/


$modify_sql = "update contact_tbl
                       set 
                  location = '',
                  phone = '',
                  birth_date = '',
                  location = '',
                  address = '',
                  agree = '',
                  apply_date = NULL,
                  apply_time = '',
                  apply_modify_yn = 'C',
                  apply_modify_date = '$posted'
                       where
                  apply_num = '$applyNo'";


$updateStmt = $db_conn->prepare($modify_sql);
$updateStmt->execute();

echo "<script type='text/javascript'>";
echo "alert('등록하신 지원이 취소되었습니다.'); location.href='/'";
echo "</script>";

?>
