<?
    include_once('../head.php');

    $posted = date("Y-m-d H:i:s");

    $id = $_POST['id'];
    $type = $_POST['type'];
    $locate = $_POST['locate'];
    $locate_detail = $_POST['locate_detail'];
    $name = $_POST['name'];
    $address = $_POST['address'];

     //입력
    if($type == 'insert'){

          $insert_sql = "insert into locate_tbl
                              (locate, locate_detail, name,  address, regdate)
                         value
                              (?, ?, ?, ?, ?)";


          $db_conn->prepare($insert_sql)->execute(
               [$locate, $locate_detail, $name,
                   $address, $posted]);

          echo "<script type='text/javascript'>";
          echo "alert('등록 되었습니다.'); location.href='../locate_list.php?menu=11&'";
          echo "</script>";
     }

    //수정
    if($type == 'modify'){

          $modify_sql = "update locate_tbl
               set 
          locate = '$locate',
          locate_detail = '$locate_detail',
          name = '$name',
          address = '$address',
          regdate = '$posted'
               where
          id = $id";

          $updateStmt = $db_conn->prepare($modify_sql);
          $updateStmt->execute();

          $count = $updateStmt->rowCount();


          echo "<script type='text/javascript'>";
          echo "alert('수정을 완료했습니다.'); location.href='../locate_list.php?menu=11'";
          echo "</script>";

    }


?>
