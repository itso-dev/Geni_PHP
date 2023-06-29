<?
    include_once('../head.php');

    $id = $_GET['id'];

    $delete_sql = "delete from locate_tbl
    where
       id = $id";

    $deleteStmt = $db_conn->prepare($delete_sql);
    $deleteStmt->execute();

    $count = $deleteStmt->rowCount();


      echo "<script type='text/javascript'>";
      echo "alert('삭제 되었습니다.'); location.href='../locate_list.php?menu=11'";
      echo "</script>";
?>
