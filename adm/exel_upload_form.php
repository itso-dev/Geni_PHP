<?php
    include_once('./head.php');
    include_once('./default.php');

?>

<link rel="stylesheet" type="text/css" href="./css/popup_form.css" rel="stylesheet" />

    <div class="page-header">
        <h4 class="page-title">엑셀 업로드</h4>
        <form name="manager_form" id="manager_form" method="post" enctype="multipart/form-data" action="./ajax/exel_upload.php">
            </div>
                <div class="input-wrap">
                    <p class="label-name">엑셀 파일 업로드*</p>
                    <input type="file" name="excelFile" class="form-control">
                    <small>엑셀 형식은 기존 항목값과 동일해야합니다. 동일하지 않을시,정상적으로 적용이 되지 않을 수 있습니다.</small><br>
                </div>
            </div>
                <div class="btn-wrap">
                    <input type="submit" class="submit" value="확인" />
                    <a href="./manager_list.php" class="go-back">목록</a>
                </div>
        </form>
    </div>
    <!-- box end -->
</div>
<!-- content-box-wrap end -->

<script type='text/javascript'>
    $( document ).ready(function() {
        $('#password').val('');
    });

</script>
