<?php
include_once('head.php');

$locate  = $_GET['locate'];
$applyDate  = $_GET['applyDate'];
$applyNo  = $_GET['applyNo'];
//문자 내용
$info_sql = "select * from locate_tbl where name = '$locate'";
$info_stt=$db_conn->prepare($info_sql);
$info_stt->execute();
$row = $info_stt -> fetch();

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
                            <li>희망 근무지: <?= $row['name'] ?></li>
                            <li>위치: <?= $row['address'] ?></li>
                            <li>교통: <br><?= nl2br($row['subway']) ?></li>
                            <li>면접: <?= $applyDate ?></li>
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
                <p class="sub-title-bold">문의: (<?= $row['name'] ?>) <?= $row['phone'] ?></p>
                <p class="sub-title">
                    지원해주셔서 감사합니다.
                </p>
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
                location.href="/";
            }
    </script>

    <?php
    include_once('tale.php');
    ?>
