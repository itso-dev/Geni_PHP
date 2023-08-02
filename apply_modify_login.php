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
            <article class="apply-page apply-login max">
                <p class="title">
                    지원 번호
                </p>
                <form name="login_form" id="login_form" method="post" action="interview_ajax/apply_login.php">
                    <input class="login-input" type="text" name="apply_no" placeholder="지원번호를 입력하세요." required />
                    <input class="login-submit" type="submit" value="완료" />
                </form>

            </article>
        </section>
    </div>
<?php
include_once('tale.php');
?>
