<?
    include_once('../head.php');

    $id = $_POST['id'];
    $counsel_text = $_POST['counsel_text'];
    $result_status = $_POST['result_status'];


    $modify_sql = "update contact_tbl
    set 
    counsel_text = '$counsel_text',
    result_status = '$result_status'
    where
    id = $id";

    $updateStmt = $db_conn->prepare($modify_sql);
    $updateStmt->execute();

    $count = $updateStmt->rowCount();

    echo "<script type='text/javascript'>";
    echo "alert('등록되었습니다.'); location.href='../apply_list.php'";
    echo "</script>";

?>
