<?php
include_once('./head.php');
include_once('./default.php');

    // 리스트에 출력하기 위한 sql문
    $sms_sql = "select * from pay_service_tbl where id = 1";
    $sms_stt=$db_conn->prepare($sms_sql);
    $sms_stt->execute();
    $row = $sms_stt -> fetch();


?>

<link rel="stylesheet" type="text/css" href="css/sms_service.css" rel="stylesheet" />

<div class="page-header">
    <h4>알림 문자 설정</h4>
    <form name="sms_form" id="sms_form" method="post" action="./ajax/sms_setting.php">
        <div class="form-container">
<!--            <div class="input-container">-->
<!--                <p class="label">알림 문자를 받을 전화번호를 입력하세요.</p>-->
<!--                <input type="text" name="phone" class="form-control" placeholder="반드시 010-0000-0000 의 형태로 입력해주세요." value="--><?//= $row[1] ?><!--" required>-->
<!--            </div>-->
            <div class="input-container">
                <p class="label">보내고 싶은 문자 내용을 작성해 주세요.</p>
                <textarea name="desc" class="form-control"><?= $row[2] ?></textarea>
                <small>글 내용 앞에는 "(지원자 이름)님." 이 먼저 나옵니다.</small>
            </div>
            <div class="input-container">
                <input type="submit" value="등록">
            </div>
        </div>
    </form>
</div>
<!-- box end -->
</div>
<!-- content-box-wrap end -->

<script type='text/javascript'>


</script>
