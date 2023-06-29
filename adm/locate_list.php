<?php
include_once('./head.php');
include_once('./default.php');

// 리스트에 출력하기 위한 sql문
$admin_sql = "select * from locate_tbl order by id";
$admin_stt = $db_conn->prepare($admin_sql);
$admin_stt->execute();
?>

<div class="page-header">
    <h4 class="page-title">지점 관리</h4>
    <div class="btn_fixed_top">
        <button type="button" onclick="location.href='./locate_form.php?menu=11&type=insert'"
                class="btn btn-danger btn-sm">지점 추가</button>
    </div>
</div>
<!-- page-header end -->

<div class="page-body">
    <div class="table-responsive">
        <table class="table border-bottom" style="min-width: 800px;">
            <thead>
            <tr class="text-center">
                <th scope="col" class="text-center">번호</th>
                <th scope="col">지역명</th>
                <th scope="col">세부 지역명</th>
                <th scope="col">지점명</th>
                <th scope="col">주소</th>
                <th scope="col" class="text-center">관리</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $is_data = 0;
            while ($list_row = $admin_stt->fetch()) {
                $is_data = 1;
                ?>
                <tr class="text-center">
                    <td>
                        <?= $list_row['id'] ?>
                    </td>
                    <td>
                        <?= $list_row['locate'] ?>
                    </td>
                    <td>
                        <?= $list_row['locate_detail'] ?>
                    </td>
                    <td>
                        <?= $list_row['name'] ?>
                    </td>
                    <td>
                        <?= $list_row['address'] ?>
                    </td>
                    <td>
                        <a href="./locate_form.php?menu=11&type=modify&id=<?= $list_row['id'] ?>"
                           class="btn btn_03">수정</a>
                        <a href="./ajax/locate_delete.php?id=<?= $list_row['id'] ?>"
                           onclick="return confirm('선택한 지점을 삭제하시겠습니까?');" class="btn btn_03">삭제</a>
                    </td>
                </tr>
            <?php } ?>
            <?php if ($is_data != 1) { ?>
            <tr>
                <td colspan="20" class="text-center text-dark bg-light">등록된 지점이 없습니다.</td>
            </tr>
            </tbody>
            <?php } ?>
        </table>
    </div>
</div>

</div>
<!-- box end -->
</div>
<!-- content-box-wrap end -->
