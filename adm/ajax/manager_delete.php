<?
    include_once('../head.php');

    $chk_count = count($_POST['chk']);

    for ($i = 0; $i < $chk_count; $i ++) {
        $id = $_POST['chk'][$i];

        $delete_sql = "delete from admin_tbl
        where
           id = $id";

        $deleteStmt = $db_conn->prepare($delete_sql);
        $deleteStmt->execute();

        $count = $deleteStmt->rowCount();
    }




          echo "<script type='text/javascript'>";
          echo "alert('삭제 되었습니다.'); location.href='../manager_list.php?menu=4&'";
          echo "</script>";
?>
