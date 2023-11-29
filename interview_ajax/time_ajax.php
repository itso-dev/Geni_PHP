<?php
    include_once('../db/dbconfig.php');

    $date = $_POST['date'];
    $locate = $_POST['locate'];


    $locate_sql = "SELECT * FROM locate_tbl
                            WHERE name = '$locate'";

    $locate_stt= $db_conn->prepare($locate_sql);
    $locate_stt-> execute();
    $locate_info = $locate_stt->fetch();


    $search_sql = "SELECT * FROM calender_tbl
                        WHERE fk_locate = ".$locate_info['id']." AND DATE(date) = '$date'";

    $search_stt= $db_conn->prepare($search_sql);
    $search_stt-> execute();

    $time_sql = "";
    $firstTime = true; // 첫 번째 시간대 여부를 나타내는 변수
     $isEmpty = false;
    if ($row = $search_stt->fetch()) {
        $time_sql .= "SELECT ";
        if ($row['time1'] != 1) {
            $time_sql .= "(SELECT COUNT(*) FROM contact_tbl WHERE apply_date = '$date' and apply_time = '10:00' and location = '$locate') as time1";
            $firstTime = false; // time1이 추가되었으므로 첫 번째 시간대가 아니게 됩니다.
        }
        if ($row['time2'] != 1) {
            if (!$firstTime) {
                $time_sql .= ", ";
            }
            $time_sql .= "(SELECT COUNT(*) FROM contact_tbl WHERE apply_date = '$date' and apply_time = '11:00' and location = '$locate') as time2";
            $firstTime = false;
        }
        if ($row['time3'] != 1) {
            if (!$firstTime) {
                $time_sql .= ", ";
            }
            $time_sql .= "(SELECT COUNT(*) FROM contact_tbl WHERE apply_date = '$date' and apply_time = '12:00' and location = '$locate') as time3";
            $firstTime = false;
        }
        if ($row['time4'] != 1) {
            if (!$firstTime) {
                $time_sql .= ", ";
            }
            $time_sql .= "(SELECT COUNT(*) FROM contact_tbl WHERE apply_date = '$date' and apply_time = '13:00' and location = '$locate') as time4";
            $firstTime = false;
        }
        if ($row['time5'] != 1) {
            if (!$firstTime) {
                $time_sql .= ", ";
            }
            $time_sql .= "(SELECT COUNT(*) FROM contact_tbl WHERE apply_date = '$date' and apply_time = '14:00' and location = '$locate') as time5";
            $firstTime = false;
        }
        if ($row['time6'] != 1) {
            if (!$firstTime) {
                $time_sql .= ", ";
            }
            $time_sql .= "(SELECT COUNT(*) FROM contact_tbl WHERE apply_date = '$date' and apply_time = '15:00' and location = '$locate') as time6";
        }
        if($row['time1'] == 1 && $row['time2'] == 1 && $row['time3'] == 1 && $row['time4'] == 1 && $row['time5'] == 1 && $row['time6'] == 1){
            $time_sql .= "* FROM contact_tbl";
            $isEmpty = true;
        }
    }
    else{
        $time_sql = "SELECT 
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '$date' and apply_time = '10:00' and location = '$locate') as time1,
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '$date' and apply_time = '11:00' and location = '$locate') as time2,
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '$date' and apply_time = '13:00' and location = '$locate') as time3,
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '$date' and apply_time = '14:00' and location = '$locate') as time4,
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '$date' and apply_time = '15:00' and location = '$locate') as time5,
                (SELECT COUNT(*)  FROM contact_tbl WHERE apply_date = '$date' and apply_time = '16:00' and location = '$locate') as time6
                  FROM contact_tbl";
    }

    $time_stt = $db_conn->prepare($time_sql);
    $time_stt->execute();
    $val = $time_stt->fetch();

    $time1 = "chk";
    $time2 = "chk";
    $time3 = "chk";
    $time4 = "chk";
    $time5 = "chk";
    $time6 = "chk";
    if(isset($val['time1'])){
        if($val['time1'] >= 6){
            $time1 = "block";
        }
    } else{
        $time1 = "block";
    }
    if(isset($val['time2'])){
        if($val['time2'] >= 6){
            $time2 = "block";
        }
    } else{
        $time2 = "block";
    }
    if(isset($val['time3'])){
        if($val['time3'] >= 6){
            $time3 = "block";
        }
    } else{
        $time3 = "block";
    }
    if(isset($val['time4'])){
        if($val['time4'] >= 6){
            $time4 = "block";
        }
    } else{
        $time4 = "block";
    }
    if(isset($val['time5'])){
        if($val['time5'] >= 6){
            $time5 = "block";
        }
    } else{
        $time5 = "block";
    }
    if(isset($val['time6'])){
        if($val['time6'] >= 6){
            $time6 = "block";
        }
    } else{
        $time6 = "block";
    }

?>

<p class="time <? echo $time1 ?>">10:00</p>
<p class="time <? echo $time2 ?>">11:00</p>
<p class="time <? echo $time3 ?>">13:00</p>
<p class="time <? echo $time4 ?>">14:00</p>
<p class="time <? echo $time5 ?>">15:00</p>
<p class="time <? echo $time6 ?>">16:00</p>



<script>
    $(".time.chk").click(function (){
       var time = $(this).text();
       $(".time.chk").removeClass("choice");
       $(this).addClass("choice");
        $("input[name=apply_time]").val(time);

    });
</script>
<style>
    .empty{
        color: #b52626;
        font-size: 20px;
        font-weight: 500;
    }
</style>
