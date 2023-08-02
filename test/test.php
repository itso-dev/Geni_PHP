<?php
include_once('../db/dbconfig.php');


$time_sql = "SELECT 
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '2023-08-16' and apply_time = '14:00' and location = 'JM 2') as time1,
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '2023-08-16' and apply_time = '15:00' and location = 'JM 2') as time2,
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '2023-08-16' and apply_time = '16:00' and location = 'JM 2') as time3,
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '2023-08-16' and apply_time = '17:00' and location = 'JM 2') as time4
                  FROM contact_tbl";



$time_stt = $db_conn->prepare($time_sql);
$time_stt->execute();
$row = $time_stt->fetch();

$time1 = "chk";
$time2 = "chk";
$time3 = "chk";
$time4 = "chk";
if($row['time1'] >= 6)                                                                                                                                                                                                {
    $time1 = "block";
}
if($row['time2'] >= 6){
    $time2 = "block";
}
if($row['time3'] >= 6){
    $time3 = "block";
}
if($row['time4'] >= 6){
    $time4 = "block";
}
?>

<p class="time <? echo $time1 ?>">14:00</p>
<p class="time <? echo $time2 ?>">15:00</p>
<p class="time <? echo $time3 ?>">16:00</p>
<p class="time <? echo $time4 ?>">17:00</p>

<script>
    $(".time.chk").click(function (){
        var time = $(this).text();
        $(".time.chk").removeClass("choice");
        $(this).addClass("choice");
        $("input[name=apply_time]").val(time);

    });
</script>
