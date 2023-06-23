<?php
include_once('head.php');

//Popup
$popup_sql = "select * from popup_tbl where `end_date` > NOW() order by id ";
$popup_stt = $db_conn->prepare($popup_sql);
$popup_stt->execute();

$today = date("Y-m-d H:i:s");
$view_sql = "insert into view_log_tbl
                              (view_cnt,  reg_date)
                         value
                              (? ,?)";

$db_conn->prepare($view_sql)->execute(
    [1, $today]
);
?>

<link rel="stylesheet" type="text/css" href="css/index.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<script src='https://www.google.com/recaptcha/api.js'></script>


<!-- layer popup -->
<?
$arr = array();
$left_count = 0;
$top = 10;
$top2 = 10;
$z_index = 9999;
while ($popup = $popup_stt->fetch()) {
    $arr[] = $popup['id'];
    ?>
    <div class="layer-popup pc"
        style="display: block; width: 80%; max-width: <?= $popup['width'] ?>px; height: <?= $popup['height'] ?>px; top: 10%; left: 5%; z-index: <?= $z_index ?>;">
        <div id="agreePopup<?= $popup['id'] ?>" class="agree-popup-frame">
            <img src="data/popup/<?= $popup['file_name'] ?>" style=" height:calc(<?= $popup['height'] ?>px - 36px);"
                alt="<?= $popup['popup_name'] ?>">
            <div class="show-chk-wrap">
                <a href="javascript:todayClose('agreePopup<?= $popup['id'] ?>', 1);" class="today-x-btn">오늘하루닫기</a>
                <a class="close-popup x-btn">닫기</a>
            </div>
        </div>
    </div>

    <div class="layer-popup mobile"
        style="display: block; width: 80%; max-width: <?= $popup['width_mobile'] ?>px; height: <?= $popup['height_mobile'] ?>px; top: 10%; left: 10%; z-index: <?= $z_index ?>;">
        <div id="agreePopup_mo<?= $popup['id'] ?>" class="agree-popup-frame">
            <img src="data/popup/<?= $popup['file_name_mobile'] ?>" style=" height:calc(<?= $popup['height'] ?>px - 36px);"
                alt="<?= $popup['popup_name'] ?>">
            <div class="show-chk-wrap">
                <a href="javascript:todayClose('agreePopup_mo<?= $popup['id'] ?>', 1);" class="today-x-btn">오늘하루닫기</a>
                <a class="close-popup x-btn">닫기</a>
            </div>
        </div>
    </div>
    <?
    $z_index -= 1;
    $top += 10;
    $top2 += 15;
}
?>

<script>
    // * today popup close
    $(document).ready(function () {
        <?
        for ($i = 0; $i < count($arr); $i++) {
            ?>
            todayOpen('agreePopup<?= $arr[$i] ?>');
            todayOpen('agreePopup_mo<?= $arr[$i] ?>');
        <? } ?>
        $(".close-popup").click(function () {
            $(this).parent().parent().hide();
        });
    });

    // 창열기
    function todayOpen(winName) {
        var blnCookie = getCookie(winName);
        var obj = eval("window." + winName);
        console.log(blnCookie);
        if (blnCookie != "expire") {
            $('#' + winName).show();
        } else {
            $('#' + winName).hide();
        }
    }
    // 창닫기
    function todayClose(winName, expiredays) {
        setCookie(winName, "expire", expiredays);
        var obj = eval("window." + winName);
        $('#' + winName).hide();
    }

    // 쿠키 가져오기
    function getCookie(name) {
        var nameOfCookie = name + "=";
        var x = 0;
        while (x <= document.cookie.length) {
            var y = (x + nameOfCookie.length);
            if (document.cookie.substring(x, y) == nameOfCookie) {
                if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                    endOfCookie = document.cookie.length;
                return unescape(document.cookie.substring(y, endOfCookie));
            }
            x = document.cookie.indexOf(" ", x) + 1;
            if (x == 0)
                break;
        }
        return "";
    }
    // 24시간 기준 쿠키 설정하기
    // 만료 후 클릭한 시간까지 쿠키 설정
    function setCookie(name, value, expiredays) {
        var todayDate = new Date();
        todayDate.setDate(todayDate.getDate() + expiredays);
        document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }
</script>
<div class="floating-header">
    <img id="header-logo" class="logo" src="img/main/logo-white.png">
    <span class="title">지니 지원</span>
    <a class="link" href="">아이.엠 소개</a>
</div>
<div class="area1" data-parallax="scroll" data-image-src="img/main/main-bg.png">
    <img class="logo" src="img/main/logo-white.png">
    <p class="small">이동시장이 바뀌고 있다.</p>
    <p class="big">이제 당신이 움직일 타이밍!</p>
    <span class="tag"># 정규직 드라이버 ‘지니’ 채용 중</span>
    <img class="wave" src="img/main/wave.png">
</div>
<div class="area2">
    <div class="row">
        <div class="text">
            <img class="text1" src="img/main/area2-text1.png" data-aos="fade-up" data-aos-duration="1000">
        </div>
        <div class="img">
            <span class="tag">#초보자도 쉽게 적응</span>
            <span class="tag">#안정적인 정규직 직장</span>
            <span class="tag">#사납금이 없는 택시</span>
        </div>
    </div>
    <div class="row">
        <div class="img">
            <img class="car" src="img/main/area2-car.png">
        </div>
        <div class="text">
            <img class="text2" src="img/main/area2-text2.png" data-aos="fade-up" data-aos-duration="1000">
        </div>
    </div>
</div>



<script type="text/javascript">
    AOS.init();


    $(document).ready(function () {
        var scrollTop = $(window).scrollTop();
        if (scrollTop == 0) {
            $("#header-logo").attr("src", "img/main/logo-white.png")
            $(".floating-header").removeClass("fixed");
        } else {
            $("#header-logo").attr("src", "img/main/logo-blue.png")
            $(".floating-header").addClass("fixed");
        }
    });
    document.addEventListener('scroll', function() {
        detectTop();
    });
    function detectTop() {
        var scrollTop = $(window).scrollTop();
        if (scrollTop == 0) {
            $("#header-logo").attr("src", "img/main/logo-white.png")
            $(".floating-header").removeClass("fixed");
            return true;
        } else {
            $("#header-logo").attr("src", "img/main/logo-blue.png")
            $(".floating-header").addClass("fixed");
            return false;
        }
    }
</script>




<?php
include_once('tale.php');
?>
