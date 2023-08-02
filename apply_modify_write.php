<script>
window.onpageshow = function(event) {
    if ( event.persisted || (window.performance && window.performance.navigation.type == 2)) {
        // Back Forward Cache로 브라우저가 로딩될 경우 혹은 브라우저 뒤로가기 했을 경우
        location.href="/";
    }
</scrip>

<?php
    include_once('head.php');
    date_default_timezone_set('Asia/Seoul');
    error_reporting( E_ALL );
    ini_set( "display_errors", 1 );

    session_start();
    $captcha = $_POST['g-recaptcha'];
    $secretKey = '6LeIUPcmAAAAAOmmC7uHCV0ehulrrbqDHKztxIUk';
    $ip = $_SERVER['REMOTE_ADDR'];

    $data = array(
        'secret' => $secretKey,
        'response' => $captcha,
        'remoteip' => $ip
    );

    $url = "https://www.google.com/recaptcha/api/siteverify";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseKeys = json_decode($response, true);

    if ($responseKeys["success"]) {
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('비정상적인 접근입니다.');";
        echo "location.href = '/';";
        echo "</script>";
        exit;
    }


    $id = $_POST["id"];
    $posted = date("Y-m-d H:i:s");
    $location = $_POST["location"];
    $apply_date = substr($_POST["apply_date"], 0 , 10);
    $apply_time = $_POST["apply_time"];
    $apply_locate = $_POST["apply_locate"];
    $apply_name = $_POST["apply_name"];
    $apply_address = $_POST["apply_address"];
    $apply_subway = str_replace('<br>', " / " ,$_POST["apply_subway"]);;


    //고객 정보
    $info_sql = "select * from contact_tbl where id = $id";
    $info_stt=$db_conn->prepare($info_sql);
    $info_stt->execute();
    $info = $info_stt -> fetch();


    $yoil = array("일","월","화","수","목","금","토");
    $time = substr($apply_time, 0, 2);

    $apply_date_val = substr($apply_date, 0, 4) ."년 " .substr($apply_date, 5, 2) ."월 " .substr($apply_date, 8, 2) ."일 " .$yoil[date('w', strtotime($apply_date))] ."요일 " .substr($apply_time, 0, 2) ."시";


        $msg = "안녕하세요 ".$info['name']."님\r\ni.M 택시 '지니' 채용팀 입니다.\r\n면접 일정 변경이 완료되었습니다.\r\n\r\n- 지원 번호: ".$info['apply_num']."\r\n- 희망 근무지 : $location\r\n- 위치 : $apply_address\r\n- 교통 : $apply_subway\r\n- 변경 면접 일정 : $apply_date_val\r\n- 준비 서류\r\n① 이력서\r\n② 운전면허증 사본(1종 보통 이상)\r\n③ 운전경력 증명서(전체 경력)\r\n\r\n지원해 주셔서 감사합니다.\r\n";

        /**************** 문자전송하기 예제 필독항목 ******************/
        /* 동일내용의 문자내용을 다수에게 동시 전송하실 수 있습니다
        /* 대량전송시에는 반드시 컴마분기하여 1천건씩 설정 후 이용하시기 바랍니다. (1건씩 반복하여 전송하시면 초당 10~20건정도 발송되며 컨텍팅이 지연될 수 있습니다.)
        /* 전화번호별 내용이 각각 다른 문자를 다수에게 보내실 경우에는 send 가 아닌 send_mass(예제:curl_send_mass.html)를 이용하시기 바랍니다.

        /****************** 인증정보 시작 *****************/
        $sms_url = "https://apis.aligo.in/send/"; // 전송요청 URL
        $sms['user_id'] = "jinmobility"; // SMS 아이디
        $sms['key'] = "72bsh9eyy1mtat2askdj30czfgjw2jnl";//인증키
        /****************** 인증정보 끝 *******************/

        /****************** 전송정보 설정시작 ****************/
        $_POST['msg'] = $msg; // 메세지 내용 : euc-kr로 치환이 가능한 문자열만 사용하실 수 있습니다. (이모지 사용불가능)
        $_POST['receiver'] = $info['phone']; // 수신번호
        $_POST['destination'] = $info['phone'].'| 지니'; // 수신인 %고객명% 치환
        $_POST['sender'] ="16887722"; // 발신번호
        $_POST['rdate'] = ''; // 예약일자 - 20161004 : 2016-10-04일기준
        $_POST['rtime'] = ''; // 예약시간 - 1930 : 오후 7시30분
        $_POST['testmode_yn'] = ''; // Y 인경우 실제문자 전송X , 자동취소(환불) 처리
        $_POST['subject'] = '[i.M 지니]]'; //  LMS, MMS 제목 (미입력시 본문중 44Byte 또는 엔터 구분자 첫라인)
// $_POST['image'] = '/tmp/pic_57f358af08cf7_sms_.jpg'; // MMS 이미지 파일 위치 (저장된 경로)
        $_POST['msg_type'] = 'LMS'; //  SMS, LMS, MMS등 메세지 타입을 지정
// ※ msg_type 미지정시 글자수/그림유무가 판단되어 자동변환됩니다. 단, 개행문자/특수문자등이 2Byte로 처리되어 SMS 가 LMS로 처리될 가능성이 존재하므로 반드시 msg_type을 지정하여 사용하시기 바랍니다.
        /****************** 전송정보 설정끝 ***************/

        $sms['msg'] = stripslashes($_POST['msg']);
        $sms['receiver'] = $_POST['receiver'];
        $sms['destination'] = $_POST['destination'];
        $sms['sender'] = $_POST['sender'];
        $sms['rdate'] = $_POST['rdate'];
        $sms['rtime'] = $_POST['rtime'];
        $sms['testmode_yn'] = empty($_POST['testmode_yn']) ? '' : $_POST['testmode_yn'];
        $sms['title'] = $_POST['subject'];
        $sms['msg_type'] = $_POST['msg_type'];
// 만일 $_FILES 로 직접 Request POST된 파일을 사용하시는 경우 move_uploaded_file 로 저장 후 저장된 경로를 사용하셔야 합니다.
        if(!empty($_FILES['image']['tmp_name'])) {
            $tmp_filetype = mime_content_type($_FILES['image']['tmp_name']);
            if($tmp_filetype != 'image/png' && $tmp_filetype != 'image/jpg' && $tmp_filetype != 'image/jpeg') $_POST['image'] = '';
            else {
                $_savePath = "./".uniqid(); // PHP의 권한이 허용된 디렉토리를 지정
                if(move_uploaded_file($_FILES['file']['tmp_name'], $_savePath)) {
                    $_POST['image'] = $_savePath;
                }
            }
        }
// 이미지 전송 설정
        if(!empty($_POST['image'])) {
            if(file_exists($_POST['image'])) {
                $tmpFile = explode('/',$_POST['image']);
                $str_filename = $tmpFile[sizeof($tmpFile)-1];
                $tmp_filetype = mime_content_type($_POST['image']);
                if ((version_compare(PHP_VERSION, '5.5') >= 0)) { // PHP 5.5버전 이상부터 적용
                    $sms['image'] = new CURLFile($_POST['image'], $tmp_filetype, $str_filename);
                    curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, true);
                } else {
                    $sms['image'] = '@'.$_POST['image'].';filename='.$str_filename. ';type='.$tmp_filetype;
                }
            }
        }
        /*****/
        $host_info = explode("/", $sms_url);
        $port = $host_info[0] == 'https:' ? 443 : 80;

        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_PORT, $port);
        curl_setopt($oCurl, CURLOPT_URL, $sms_url);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ret = curl_exec($oCurl);
        curl_close($oCurl);

        $retArr = json_decode($ret); // 결과배열
// print_r($retArr); // Response 출력 (연동작업시 확인용)

        /**** Response 항목 안내 ****
        // result_code : 전송성공유무 (성공:1 / 실패: -100 부터 -999)
        // message : success (성공시) / reserved (예약성공시) / 그외 (실패상세사유가 포함됩니다)
        // msg_id : 메세지 고유ID = 고유값을 반드시 기록해 놓으셔야 sms_list API를 통해 전화번호별 성공/실패 유무를 확인하실 수 있습니다
        // error_cnt : 에러갯수 = receiver 에 포함된 전화번호중 문자전송이 실패한 갯수
        // success_cnt : 성공갯수 = 이동통신사에 전송요청된 갯수
        // msg_type : 전송된 메세지 타입 = SMS / LMS / MMS (보내신 타입과 다른경우 로그로 기록하여 확인하셔야 합니다)
        /**** Response 예문 끝 ****/


        //예약 문자
        $rdate = date('Y-m-d',strtotime($apply_date."-1 day"));

        $rdate_val = substr($rdate, 0, 4) .substr($rdate, 5, 2) .substr($rdate, 8, 2);
        $time_val = str_replace(':', "" ,$apply_time);;

        $msg2 = "안녕하세요 ".$info['name']."님\r\ni.M 택시 '지니' 채용팀 입니다.\r\n신청하신 지니 채용 면접이 내일 ".$time."시에 진행 될 예정입니다.\r\n\r\n- 지원 번호: ".$info['apply_num']."\r\n- 희망 근무지 : $location\r\n- 위치 : $apply_address\r\n- 교통 : $apply_subway\r\n- 면접 일정 : $apply_date_val\r\n- 준비 서류\r\n① 이력서\r\n② 운전면허증 사본(1종 보통 이상)\r\n③ 운전경력 증명서(전체 경력)\r\n\r\n준비 서류 서류 지참하셔서 면접 시작 10분 전까지 도착해 주시기 바랍니다.\r\n\r\n감사합니다.";

        /**************** 문자전송하기 예제 필독항목 ******************/
        /* 동일내용의 문자내용을 다수에게 동시 전송하실 수 있습니다
        /* 대량전송시에는 반드시 컴마분기하여 1천건씩 설정 후 이용하시기 바랍니다. (1건씩 반복하여 전송하시면 초당 10~20건정도 발송되며 컨텍팅이 지연될 수 있습니다.)
        /* 전화번호별 내용이 각각 다른 문자를 다수에게 보내실 경우에는 send 가 아닌 send_mass(예제:curl_send_mass.html)를 이용하시기 바랍니다.

        /****************** 인증정보 시작 *****************/
        $sms_url = "https://apis.aligo.in/send/"; // 전송요청 URL
        $sms['user_id'] = "jinmobility"; // SMS 아이디
        $sms['key'] = "72bsh9eyy1mtat2askdj30czfgjw2jnl";//인증키
        /****************** 인증정보 끝 *******************/

        /****************** 전송정보 설정시작 ****************/
        $_POST['msg'] = $msg2; // 메세지 내용 : euc-kr로 치환이 가능한 문자열만 사용하실 수 있습니다. (이모지 사용불가능)
        $_POST['receiver'] = $info['phone']; // 수신번호
        $_POST['destination'] = $info['phone'].'|지니'; // 수신인 %고객명% 치환
        $_POST['sender'] = "16887722"; // 발신번호
        $_POST['rdate'] = $rdate_val; // 예약일자 - 20161004 : 2016-10-04일기준
        $_POST['rtime'] = '1000'; // 예약시간 - 1930 : 오후 7시30분
        $_POST['testmode_yn'] = ''; // Y 인경우 실제문자 전송X , 자동취소(환불) 처리
        $_POST['subject'] = '[i.M 지니]]'; //  LMS, MMS 제목 (미입력시 본문중 44Byte 또는 엔터 구분자 첫라인)
        // $_POST['image'] = '/tmp/pic_57f358af08cf7_sms_.jpg'; // MMS 이미지 파일 위치 (저장된 경로)
        $_POST['msg_type'] = 'LMS'; //  SMS, LMS, MMS등 메세지 타입을 지정
        // ※ msg_type 미지정시 글자수/그림유무가 판단되어 자동변환됩니다. 단, 개행문자/특수문자등이 2Byte로 처리되어 SMS 가 LMS로 처리될 가능성이 존재하므로 반드시 msg_type을 지정하여 사용하시기 바랍니다.
        /****************** 전송정보 설정끝 ***************/

        $sms['msg'] = stripslashes($_POST['msg']);
        $sms['receiver'] = $_POST['receiver'];
        $sms['destination'] = $_POST['destination'];
        $sms['sender'] = $_POST['sender'];
        $sms['rdate'] = $_POST['rdate'];
        $sms['rtime'] = $_POST['rtime'];
        $sms['testmode_yn'] = empty($_POST['testmode_yn']) ? '' : $_POST['testmode_yn'];
        $sms['title'] = $_POST['subject'];
        $sms['msg_type'] = $_POST['msg_type'];
        // 만일 $_FILES 로 직접 Request POST된 파일을 사용하시는 경우 move_uploaded_file 로 저장 후 저장된 경로를 사용하셔야 합니다.
        if(!empty($_FILES['image']['tmp_name'])) {
            $tmp_filetype = mime_content_type($_FILES['image']['tmp_name']);
            if($tmp_filetype != 'image/png' && $tmp_filetype != 'image/jpg' && $tmp_filetype != 'image/jpeg') $_POST['image'] = '';
            else {
                $_savePath = "./".uniqid(); // PHP의 권한이 허용된 디렉토리를 지정
                if(move_uploaded_file($_FILES['file']['tmp_name'], $_savePath)) {
                    $_POST['image'] = $_savePath;
                }
            }
        }
        // 이미지 전송 설정
        if(!empty($_POST['image'])) {
            if(file_exists($_POST['image'])) {
                $tmpFile = explode('/',$_POST['image']);
                $str_filename = $tmpFile[sizeof($tmpFile)-1];
                $tmp_filetype = mime_content_type($_POST['image']);
                if ((version_compare(PHP_VERSION, '5.5') >= 0)) { // PHP 5.5버전 이상부터 적용
                    $sms['image'] = new CURLFile($_POST['image'], $tmp_filetype, $str_filename);
                    curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, true);
                } else {
                    $sms['image'] = '@'.$_POST['image'].';filename='.$str_filename. ';type='.$tmp_filetype;
                }
            }
        }
        /*****/
        $host_info = explode("/", $sms_url);
        $port = $host_info[0] == 'https:' ? 443 : 80;

        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_PORT, $port);
        curl_setopt($oCurl, CURLOPT_URL, $sms_url);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ret = curl_exec($oCurl);
        curl_close($oCurl);

        $retArr = json_decode($ret); // 결과배열
        // print_r($retArr); // Response 출력 (연동작업시 확인용)
        $msg_id = "";
        foreach ($retArr as $key => $val) {
            if ($key == 'msg_id') {
                $msg_id = $val;
            }
        }

        /**** Response 예문 끝 ****/


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
        $sms['mid'] = $info['msg_id'] ; // 취소할 메세지ID (필수입력)
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
                  location = '$location',
                  apply_date = '$apply_date',
                  apply_time = '$apply_time',
                  apply_modify_yn = 'Y',
                  apply_modify_date = '$posted',
                  msg_id = '$msg_id'
                       where
                  id = $id";


        $updateStmt = $db_conn->prepare($modify_sql);
        $updateStmt->execute();


        echo "<script type='text/javascript'>";
        echo "alert('변경 완료 되었습니다.');";
        echo "try{";
        echo "setTimeout(function(){";
        echo "location.href = '/apply_completion.php?locate=$location&applyDate=$apply_date_val&applyNo=".$info['apply_num']."';";
        echo "}, 500);";
        echo "}catch(e){}";
        echo "</script>";


?>
