<?
    include_once('../head.php');

    $posted = date("Y-m-d H:i:s");

    $phone = $_POST['phone'];
    $desc = $_POST['desc'];



    //수정
      $modify_sql = "update pay_service_tbl
           set 
      phone = '$phone',
      message = '$desc'
           where
      id = 1";

        echo $modify_sql;

      $updateStmt = $db_conn->prepare($modify_sql);
      $updateStmt->execute();

      echo "<script type='text/javascript'>";
      echo "alert('저장되었습니다.'); location.href='../sms_service.php'";
      echo "</script>";


?>
