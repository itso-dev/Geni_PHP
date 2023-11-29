<?php
include_once('head.php');

$applyNo  = $_GET['applyNo'];

//문의 정보
$contact_sql = "select * from contact_tbl where apply_num = '$applyNo'";
$contact_stt=$db_conn->prepare($contact_sql);
$contact_stt->execute();
$contact_row = $contact_stt -> fetch();

if($contact_row['apply_modify_yn'] == 'C'){
    echo "<script type='text/javascript'>";
    echo "alert('이미 취소된 지원입니다.'); location.href='/'";
    echo "</script>";

}

//지점 정보
$info_sql = "select * from locate_tbl where name = '" .$contact_row['location'] ."'";
$info_stt=$db_conn->prepare($info_sql);
$info_stt->execute();
$locate_row = $info_stt -> fetch();

$yoil = array("일","월","화","수","목","금","토");
$apply_date_val = substr($contact_row['apply_date'], 0, 4) ."년 " .substr($contact_row['apply_date'], 5, 2) ."월 " .substr($contact_row['apply_date'], 8, 2) ."일 " .$yoil[date('w', strtotime($contact_row['apply_date']))] ."요일 " .substr($contact_row['apply_time'], 0, 2) ."시";


?>


<link rel="stylesheet" type="text/css" href="css/apply.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<script src='https://www.google.com/recaptcha/api.js'></script>

<body>
    <div>
        <header id="apply-header">
            <img src="img/apply/logo.blue.png" />
        </header>

        <section id="apply-container">
            <article class="apply-page apply-completion max">
                <p class="title">i.M 정규직 드라이버
                    <span>
                        '지니’<br />
                        입사 지원이 완료되었습니다.
                    </span>
                </p>
                <div class="info-box">
                    <div class="inner">
                        <strong>지원정보</strong>
                        <ul>
                            <li>지원 번호: <?= $applyNo ?></li>
                            <li>희망 근무지: <?= $locate_row['name'] ?></li>
                            <li>위치: <?= $locate_row['address'] ?></li>
                            <li>교통: <br><?= nl2br($locate_row['subway']) ?></li>
                            <li>면접: <?= $apply_date_val ?></li>
                            <li>
                                준비 서류:<br>
                                ① 이력서<br>
                                ② 운전면허증 사본 (1종 보통 이상)<br>
                                ③ 운전경력 증명서 (전체 경력)<br>
                                &nbsp&nbsp*온라인 : 정부24<br>
                                &nbsp&nbsp*오프라인 : 경찰서 민원실 방문
                            </li>
                        </ul>
                    </div>
                </div>
                <p class="sub-title-bold">문의: (<?= $locate_row['name'] ?>) <?= $locate_row['phone'] ?></p>
                <p class="sub-title">
                    지원해주셔서 감사합니다.
                </p>
                <?php if($contact_row['apply_modify_yn'] == 'N'){ ?>
                <div class="c-btn-wrap">
                    <a class="btn" href="apply_modify_login.php">면접 일정 변경</a>
                </div>
                <?php } ?>
                <div class="c-btn-wrap">
                    <span class="btn" onclick="cancel();">지원 취소</span>
                    <form id="cancel-form" method="post" action="apply_cancel.php">
                        <input type="hidden" name="applyNo" value="<?= $applyNo ?>">
                    </form>
                </div>
                <div class="submit-btn" onclick="location.href='index.php'">
                    완료
                </div>
                <img src="img/apply/im.png" class="car" />
            </article>
        </section>
    </div>

    <script>
        window.onpageshow = function(event) {
            if ( event.persisted || (window.performance && window.performance.navigation.type == 2)) {
                // Back Forward Cache로 브라우저가 로딩될 경우 혹은 브라우저 뒤로가기 했을 경우
                location.href = "/";
            }
        }
        function cancel(){
            if (!confirm("지원을 취소하시겠습니까?")) {
                // 취소(아니오) 버튼 클릭 시 이벤트
                return false;
            } else {
                // 확인(예) 버튼 클릭 시 이벤트
                $("#cancel-form").submit();
            }
        }

    </script>

    <?php
    include_once('tale.php');
    ?>
