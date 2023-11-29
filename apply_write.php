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
        echo "location.href = '/apply.php';";
        echo "</script>";
        exit;
    }



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



    if( isset( $_POST["agree"] ) ){
        $agree = $_POST["agree"];
    }else{
        $agree = "N";
    }

    if($agree == 1){
        $agree = "Y";
    }
    else{
        $agree = "N";
    }
    if($recommender == '(선택)'){
        $recommender = "선택안함";
    }
    $writer_ip = $_POST["writer_ip"];

    $n_year = str_replace('-', '', substr($posted,2, 8));
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

    $yoil = array("일","월","화","수","목","금","토");

    $apply_date_val = substr($apply_date, 0, 4) ."년 " .substr($apply_date, 5, 2) ."월 " .substr($apply_date, 8, 2) ."일 " .$yoil[date('w', strtotime($apply_date))] ."요일 " .substr($apply_time, 0, 2) ."시";

    $time = substr($apply_time, 0, 2);

    //2주동안 중복 신청자 체크
    $chk_sql = "SELECT COUNT(*) as cnt FROM contact_tbl WHERE name='$name' and phone='$phone' and write_date BETWEEN DATE_ADD(NOW(),INTERVAL -2 WEEK ) AND NOW()";
    $chk_stt=$db_conn->prepare($chk_sql);
    $chk_stt->execute();
    $cnt = $chk_stt -> fetch();

    if($cnt[0] > 0){
        echo "<script type='text/javascript'>";
        echo "alert('2주 이내 중복 지원은 할 수 없습니다.');";
        echo "dataLayer.push({";
        echo "'event' : 'apply-true'";
        echo "});";
        echo "try{";
        echo "setTimeout(function(){";
        echo "location.href = '/apply.php';";
        echo "}, 500);";
        echo "}catch(e){}";
        echo "</script>";
    }
    else{

        //운수사 정보 불러오기
        $l_sql = "SELECT * FROM locate_tbl WHERE name='$location'";
        $l_stt=$db_conn->prepare($l_sql);
        $l_stt->execute();
        $l = $l_stt -> fetch();




        $msg = "안녕하세요 ".$name."님\r\ni.M 택시 '지니' 채용팀 입니다.\r\n입사지원이 완료되었습니다.\r\n\r\n- 지원 번호: $apply_no\r\n- 희망 근무지 : $location\r\n- 위치 : $apply_address\r\n- 운수사 연락처 : ".$l['phone']."\r\n- 교통 : $apply_subway\r\n- 면접 일정 : $apply_date_val\r\n- 준비 서류\r\n① 이력서\r\n② 운전면허증 사본(1종 보통 이상)\r\n③ 운전경력 증명서(전체 경력)\r\n\r\n지원 정보 확인 및 면접 일정 변경은 하단 링크에서 확인 하실 수 있습니다.\r\nhttps://genie.imforyou.co.kr/apply_completion.php?applyNo=$apply_no\r\n\r\n지원해 주셔서 감사합니다.\r\n
        ";

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
        $_POST['receiver'] = $phone; // 수신번호
        $_POST['destination'] = $phone.'| 지니'; // 수신인 %고객명% 치환
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

        $msg2 = "안녕하세요 ".$name."님\r\ni.M 택시 '지니' 채용팀 입니다.\r\n신청하신 지니 채용 면접이 내일 ".$time."시에 진행 될 예정입니다.\r\n\r\n- 지원 번호: $apply_no\r\n- 희망 근무지 : $location\r\n- 위치 : $apply_address\r\n- 운수사 연락처 : ".$l['phone']."\r\n- 교통 : $apply_subway\r\n- 면접 일정 : $apply_date_val\r\n- 준비 서류\r\n① 이력서\r\n② 운전면허증 사본(1종 보통 이상)\r\n③ 운전경력 증명서(전체 경력)\r\n\r\n준비 서류 서류 지참하셔서 면접 시작 10분 전까지 도착해 주시기 바랍니다.\r\n\r\n감사합니다.";

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
        $_POST['receiver'] = $phone; // 수신번호
        $_POST['destination'] = '$phone|지니'; // 수신인 %고객명% 치환
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
        code : 0 성공, 나머지 숫자는 에러
        message : 결과 메시지
        */
        /*
        -----------------------------------------------------------------------------------
        알림톡 전송
        -----------------------------------------------------------------------------------
        버튼의 경우 템플릿에 버튼이 있을때만 버튼 파라메더를 입력하셔야 합니다.
        버튼이 없는 템플릿인 경우 버튼 파라메더를 제외하시기 바랍니다.
        */

        $rdate_kakao = date('YmdHis',strtotime($apply_date."-1 day"));
        $rdate_kakao_val = substr($rdate_kakao, 0, 8)."100000";



        $_apiURL = 'https://kakaoapi.aligo.in/akv10/alimtalk/send/';
        $_hostInfo = parse_url($_apiURL);
        $_port = (strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
        $_variables = array(
            'apikey' => 'msx3um4l58t21mo8zplfvp76ulctny2k',
            'userid' => 'jmpc1',
            'token' => $token,
            'senderkey' => '81bd75a4ea9f924b0c53be35539a10ca59b1dae8',
            'tpl_code' => 'TO_6506',
            'sender' => '010-3444-5450',
            'senddate' => $rdate_kakao_val,
            'receiver_1' => $phone,
            'recvname_1' => $name,
            'subject_1' => "아이.엠 택시 '지니'",
            'emtitle_1' => '면접일정 및 제출서류 안내',
            'message_1' => "안녕하세요 ".$name."님
i.M 택시 '지니' 채용팀 입니다.
신청하신 지니 채용 면접이 내일 ".$time."시에 진행 될 예정입니다.

- 지원 번호 : ".$apply_no."
- 희망 근무지 : ".$location."
- 운수사 연락처 : ".$l['phone']."
- 위치 : ".$l['address']."
- 교통 : ".$apply_subway."
- 면접 일정 : ".$apply_date_val."
- 준비 서류
  ① 이력서
  ② 운전면허증 사본(1종 보통 이상)
  ③ 운전경력 증명서(전체 경력)

준비 서류 지참하셔서 면접 시작 10분전까지 도착해 주시기 바랍니다.

감사합니다.",

            'button_1' => '{
"button": [{
"name": "채널 추가",
"linkType": "AC",
"linkTypeName": "채널 추가"
},
{"name": "나의 지원 확인",
"linkType": "WL",
"linkTypeName": "웹링크",
"linkPc": "https://genie.imforyou.co.kr/apply_completion.php?applyNo='.$apply_no.'",
"linkMo" : "https://genie.imforyou.co.kr/apply_completion.php?applyNo='.$apply_no.'"
}]
}' // 템플릿에 버튼이 없는경우 제거하시기 바랍니다.
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

// JSON 문자열 배열 변환
        $retArr = json_decode($ret);

        $kMid = "";
        foreach ($retArr as $key => $val) {
            if($key == 'info') {
                foreach ($val as $k => $v) {
                    if($k == 'mid') {
                        $kMid = $v;
                    }
                }
            }
        }


        $_apiURL = 'https://kakaoapi.aligo.in/akv10/alimtalk/send/';
        $_hostInfo = parse_url($_apiURL);
        $_port = (strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
        $_variables = array(
            'apikey' => 'msx3um4l58t21mo8zplfvp76ulctny2k',
            'userid' => 'jmpc1',
            'token' => $token,
            'senderkey' => '81bd75a4ea9f924b0c53be35539a10ca59b1dae8',
            'tpl_code' => 'TO_6505',
            'sender' => '010-3444-5450',
            'receiver_1' => $phone,
            'recvname_1' => $name,
            'subject_1' => "아이.엠 택시 '지니'",
            'emtitle_1' => '면접일정 및 제출서류 안내',
            'message_1' => "안녕하세요 ".$name."님
i.M 택시 '지니' 채용팀 입니다.
입사지원이 완료되었습니다.

- 지원 번호 : ".$apply_no."
- 희망 근무지 : ".$location."
- 운수사 연락처 : ".$l['phone']."
- 위치 : ".$l['address']."
- 교통 : ".$apply_subway."
- 면접 일정 : ".$apply_date_val."
- 준비 서류
  ① 이력서
  ② 운전면허증 사본(1종 보통 이상)
  ③ 운전경력 증명서(전체 경력)


지원해 주셔서 감사합니다.",

            'button_1' => '{
"button": [{
"name": "채널 추가",
"linkType": "AC",
"linkTypeName": "채널 추가"
},
{"name": "나의 지원 확인",
"linkType": "WL",
"linkTypeName": "웹링크",
"linkPc": "https://genie.imforyou.co.kr/apply_completion.php?applyNo='.$apply_no.'",
"linkMo" : "https://genie.imforyou.co.kr/apply_completion.php?applyNo='.$apply_no.'"
}]
}' // 템플릿에 버튼이 없는경우 제거하시기 바랍니다.
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

// JSON 문자열 배열 변환
        $retArr = json_decode($ret);



        /**** Response 예문 끝 ****/

        $sql = "insert into contact_tbl
            (name, phone, birth_date, address, location, recommender, recommender_name, agree, result_status,
             consult_fk, manager_fk, writer_ip, write_date, apply_num, apply_date, apply_time, apply_modify_yn, msg_id, k_msg_id)
        value
            (?, ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";

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
                $posted,
                $apply_no,
                $apply_date,
                $apply_time,
                'N',
                $msg_id,
                $kMid
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
        echo "dataLayer.push({";
        echo "'event' : 'apply-false'";
        echo "});";
        echo "try{";
        echo "setTimeout(function(){";
        echo "location.href = '/apply_completion.php?applyNo=$apply_no';";
        echo "}, 500);";
        echo "}catch(e){}";
        echo "</script>";
    }

    ?>
