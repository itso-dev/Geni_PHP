<?php
include_once('head.php');

?>

<link rel="stylesheet" type="text/css" href="css/apply.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/apply_add.css" rel="stylesheet" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
      integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="js/reservation.js"></script>

<body>
<section id="apply-container">
    <form name="contact_form" id="contact_form" method="post" action="apply_write_bk.php" onsubmit='return frmSubmit();'>
        <div class="calender-wrap">
            <table class="Calendar">
                <thead>
                <tr class="thead">
                    <td colspan="7">
                        <div class="date-wrap">
                            <span id="calYear" ></span>년
                            <span id="calMonth" ></span>월
                        </div>
                        <div class="btn-wrap">

                            <img class="btn" src="img/apply/left.png" onClick="prevCalendar();" />
                            <img class="btn" src="img/apply/right.png" onClick="nextCalendar();" />
                        </div>
                    </td>
                </tr>
                <tr class="week">
                    <td>일</td>
                    <td>월</td>
                    <td>화</td>
                    <td>수</td>
                    <td>목</td>
                    <td>금</td>
                    <td>토</td>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </div>

    </form>
</section>
</body>


<?php
include_once('tale.php');
?>
