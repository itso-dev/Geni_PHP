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

<div class="area3">
    <img class="banner" src="img/main/i.M.png">

    <div class="geni-wrap">
        <img class="people" src="img/main/people.png">

        <div class="text-wrap">
            <img class="geni-title" src="img/main/geni.png">
            <p class="sub-title1">언제 어디서나 <br class="mobile" />쾌적하고 안전하게!</p>
            <p class="sub-title2">
                i.M 만의 체계적인 교육과 지원을 받으며,<br />
                고객에게 프라이빗한 이동 서비스를 제공하는<br />
                <strong>프리미엄 정규직 드라이버</strong>입니다.
            </p>
        </div>
    </div>

    <div class="three-keywords">
        <div class="keyword">
            <p class="title">안전</p>
            <img class="geni-title" src="img/main/safety.png">
            <p class="text">
                고객을 목적지까지<br />
                안전하게 모시는 것이<br />
                가장 중요합니다.
            </p>
        </div>
        <div class="keyword">
            <p class="title">정숙</p>
            <img class="geni-title" src="img/main/silent.png">
            <p class="text">
                고객이 목적지까지<br />
                프라이빗한 시간을<br />
                가질 수 있도록 정숙합니다.
            </p>
        </div>
        <div class="keyword">
            <p class="title">청결</p>
            <img class="geni-title" src="img/main/clean.png">
            <p class="text">
                쾌적한 차량 내부와<br />
                깔끔한 드라이버의 모습으로<br />
                고객을 맞이합니다.
            </p>
        </div>
    </div>


    <div class="click-btn">
        <p class="click"># 일</p>
        <p># 급여</p>
        <p># 복지</p>
        <p># 비전</p>
    </div>

    <div class="service-wrap">
        <p class="title">
            지니는 앱 호출, 일반 탑승, 예약 운행 등 다양한 형태로 고객을 만나고,<br />
            고객이 원하는 목적지까지 <span>안전하고 편안한 이동 서비스를 제공</span>합니다.
        </p>

        <div class="service">
            <div class="service-item">
                <img class="" src="img/main/service1.png">
                <p class="sub-tit1">APP Service</p>
                <p class="tit">앱 호출 영업</p>
                <p class="sub-tit2">AI 자동배차 시스템</p>
            </div>
            <div class="service-item">
                <img class="" src="img/main/service2.png">
                <p class="sub-tit1">On the Road</p>
                <p class="tit">배회 영업</p>
                <p class="sub-tit2">택시표시등 설치로<br />
                    길에서 손님 탑승 가능</p>
            </div>
            <div class="service-item">
                <img class="" src="img/main/service3.png">
                <p class="sub-tit1">Reservation</p>
                <p class="tit">예약 영업</p>
                <p class="sub-tit2">공항, 시간대절 등</p>
            </div>
        </div>

        <div class="work-info">
            <div class="work">
                <p class="title">근무 형태</p>
                <div class="text-wrap">
                    <p class="text">
                        <strong>im26</strong> (주 6일제 / 월 26일)
                        <br />
                        <strong>im22</strong> (주 5일제 / 월 22일)
                    </p>
                </div>
            </div>

            <div class="work">
                <p class="title">근무 시간</p>
                <div class="text-wrap">
                    <ul class="text">
                        <li>
                            주간 or 야간 근무 중 선택 가능
                        </li>
                        <li>
                            일 10시간 운행<br />
                            (근무 6시간 40분, 휴게 3시간 20분)
                        </li>
                    </ul>
                </div>
            </div>

            <div class="work">
                <p class="title">임시 택시운전자격 제도</p>
                <div class="text-wrap">
                    <p class="text">
                        택시운전자격이 없어도<Br />
                        1종 운전면허만 있다면<Br />
                        3개월간 운행 가능한<Br />
                        임시 택시운전자격 부여
                    </p>
                </div>
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
        document.addEventListener('scroll', function () {
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