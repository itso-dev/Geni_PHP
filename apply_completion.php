<?php
include_once('head.php');
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
                <p class="sub-title">
                    채용 담당자가 근무일 기준 3일 이내로 면접 진행 관련해서 안내를 드릴 예정입니다.<br /><br class="mo-br768" />
                    감사합니다.
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
