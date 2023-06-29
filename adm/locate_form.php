<?php
include_once('./head.php');
include_once('./default.php');

$type = $_GET['type'];

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = '';
}

$locate = '';
$locate_detail = '';
$name = '';
$address = '';

if ($type == 'modify') {
    // 리스트에 출력하기 위한 sql문
    $admin_sql = "select * from locate_tbl where id = $id";
    $admin_stt = $db_conn->prepare($admin_sql);
    $admin_stt->execute();
    $row = $admin_stt->fetch();

    $locate = $row['locate'];
    $locate_detail = $row['locate_detail'];
    $name = $row['name'];
    $address = $row['address'];

}
?>

<link rel="stylesheet" type="text/css" href="./css/popup_form.css" rel="stylesheet" />

<div class="page-header">
    <h4 class="page-title">지점 관리</h4>
    <form name="popup_form" id="popup_form" method="post" enctype="multipart/form-data"
          action="./ajax/locate_setting.php">
        <input type="hidden" name="id" value="<?= $id ?>" />
        <input type="hidden" name="type" value="<?= $type ?>" />
        <div>
            <div class="input-wrap">
                <p class="label-name">지역*</p>
                <select class="form-control" id="locate" name="locate" style="max-width: 300px">
                    <option value="동부">동부</option>
                    <option value="서부">서부</option>
                    <option value="중부">중부</option>
                    <option value="남부">남부</option>
                    <option value="북부">북부</option>
                </select>
            </div>
            <div class="input-wrap">
                <p class="label-name">세부 지역*</p>
                <select class="form-control locate_detail" id="locate_detail1" name="locate_detail" style="max-width: 300px">
                    <option value="강동">강동</option>
                    <option value="송파">송파</option>
                </select>
            </div>
            <hr>
            <div class="input-wrap">
                <p class="label-name">지점명*</p>
                <input type="text" name="name" class="form-control" value="<?= $name ?>">
            </div>
            <hr>
            <div class="input-wrap">
                <p class="label-name">주소*</p>
                <input type="text" name="address" class="form-control" value="<?= $address ?>">
            </div>
            <hr>
        </div>
        <div class="btn-wrap">
            <input type="submit" class="submit" value="확인" required />
            <a href="locate_form.php                                                            " class="go-back">목록</a>
        </div>
    </form>
</div>
<!-- box end -->
</div>
<!-- content-box-wrap end -->


<script type="text/javascript">
    $( document ).ready(function() {
        $("#locate").change(function() {
            $(".locate_detail").empty();
            var option = "";
            switch ($(this).val()){
                case "동부":
                    option ="<option value='강동'>강동</option>" +
                        "<option value='송파'>송파</option>";
                    break;
                case "서부":
                    option ="<option value='강서'>강서</option>" +
                        "<option value='마포'>마포</option>" +
                        "<option value='서대문'>서대문</option>" +
                        "<option value='은평'>은평</option>";
                    break;
                case "중부":
                    option ="<option value='성동'>성동</option>";
                    break;
                case "남부":
                    option ="<option value='구로'>구로</option>" +
                        "<option value='영등포'>영등포</option>" +
                        "<option value='동작'>동작</option>";
                    break;
                case "북부":
                    option ="<option value='도봉'>도봉</option>"
                    break;
            }
            $("#locate_detail1").append(option);
        });
    });

</script>
