<?php
    include_once('../db/dbconfig.php');
    error_reporting( E_ALL );
    ini_set( "display_errors", 1 );

    $apply_num = $_POST['apply_no'];

    $login_sql = "select * from contact_tbl WHERE apply_num = '$apply_num'";
    $login_stt=$db_conn->prepare($login_sql);
    $login_stt->execute();


    if($row = $login_stt->fetch()){
        if($row['apply_modify_yn'] == 'Y'){
            echo "<script type='text/javascript'>";
            echo "alert('면접 일정 변경은 1회만 가능합니다.'); location.href='/'";
            echo "</script>";
        }

        echo "<script type='text/javascript'>";
        echo "location.href='../apply_modify.php?id=".$row['id']."&name=".$row['name']."'";
        echo "</script>";
    }else{
        echo "<script type='text/javascript'>";
        echo "alert('등록된 지원번호가 없습니다.'); location.href='../apply_modify_login.php'";
        echo "</script>";
    }

?>
