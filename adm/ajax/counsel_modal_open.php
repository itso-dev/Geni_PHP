<?
    include_once('../../db/dbconfig.php');

    $wr_id = $_POST['wr_id'];

    // 리스트에 출력하기 위한 sql문
    $modal_sql = "select * from contact_tbl where id = $wr_id";
    $modal_stt=$db_conn->prepare($modal_sql);
    $modal_stt->execute();
    $row = $modal_stt -> fetch();
?>

<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form name="madal_form" id="madal_form" method="post" action="./ajax/contact_insert.php">
        <input type="hidden" name="id" value="<?= $row[0] ?>" />
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $row[1] ?>님 상담내역</h5>
                </div>
                <div class="modal-body">
                    <div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>이름</th>
                                    <th class="text-center">연락처</th>
                                    <th class="text-center">결과</th>
                                    <th class="text-center">지원일</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="modal_id text-center" id="modal_id"><?= $row[0] ?></td>
                                    <td class="modal_name" id="modal_name"><?= $row[1] ?></td>
                                    <td class="modal_subject text-center" id="modal_subject"><?= $row[2] ?></td>
                                    <td class="modal_result text-center" id="modal_result"><?= $row['result_status'] ?></td>
                                    <td class="modal_datetime text-center" id="modal_datetime"><?= $row['write_date'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="modal_counsel_content_result">결과</label>
                            <select class="custom-select modal_counsel_result" id="modal_counsel_result" name="result_status">
                                <option value="대기" <? if($row['result_status'] == "대기") echo "selected"?>>대기</option>
                                <option value="지원 취소" <? if($row['result_status'] == "지원 취소") echo "selected"?>>지원 취소</option>
                                <option value="면접 예정" <? if($row['result_status'] == "면접 예정") echo "selected"?>>면접 예정</option>
                                <option value="면접 불참" <? if($row['result_status'] == "면접 불참") echo "selected"?>>면접 불참</option>
                                <option value="1차 합격" <? if($row['result_status'] == "1차 합격") echo "selected"?>>1차 합격</option>
                                <option value="최종 합격" <? if($row['result_status'] == "최종 합격") echo "selected"?>>최종 합격</option>
                                <option value="불합격" <? if($row['result_status'] == "불합격") echo "selected"?>>불합격</option>
                                <option value="블랙" <? if($row['result_status'] == "블랙") echo "selected"?>>블랙</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="counsel_content_body" class="col-form-label">상담내용</label>
                    </div>
                    <div>
                        <textarea name="counsel_text"><?= $row['counsel_text'] ?></textarea>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-primary" value="저장"></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="modal-close">닫기</button>
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>

<script>
    $( document ).ready(function() {
        $('#modal-close').click(function(){
            $('#contactModal').modal('hide');
        })
    });
</script>
