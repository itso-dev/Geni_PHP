<?php
    include_once('./head.php');
    include_once('./default.php');

    $today_date = date("Y-m-d H:i:s");

    // 오늘 조회수
    $today_view_sql = "SELECT COUNT(view_cnt) FROM view_log_tbl WHERE DATE(reg_date) = DATE(NOW());";
    $today_view_stt=$db_conn->prepare($today_view_sql);
    $today_view_stt->execute();
    $today_view = $today_view_stt -> fetch();
    // 전체 조회수
    $total_view_sql = "SELECT COUNT(view_cnt) FROM view_log_tbl";
    $total_view_stt=$db_conn->prepare($total_view_sql);
    $total_view_stt->execute();
    $total_view = $total_view_stt -> fetch();

    // 오늘 문의건수
    $today_contact_sql = "SELECT COUNT(contact_cnt) FROM contact_log_tbl WHERE DATE(reg_date) = DATE(NOW())";
    $today_contact_stt=$db_conn->prepare($today_contact_sql);
    $today_contact_stt->execute();
    $today_contact = $today_contact_stt -> fetch();
    // 전체 문의건수
    $total_contact_sql = "SELECT COUNT(contact_cnt) FROM contact_log_tbl";
    $total_contact_stt=$db_conn->prepare($total_contact_sql);
    $total_contact_stt->execute();
    $total_contact = $total_contact_stt -> fetch();

    // 전체 대기자 수
    $total_waiting_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%대기%'";
    $total_waiting_stt=$db_conn->prepare($total_waiting_sql);
    $total_waiting_stt->execute();
    $total_waiting = $total_waiting_stt -> fetch();
    // 오늘 대기자 수
    $today_waiting_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%대기%' AND DATE(write_date) = DATE(NOW())";
    $today_waiting_stt=$db_conn->prepare($today_waiting_sql);
    $today_waiting_stt->execute();
    $today_waiting = $today_waiting_stt -> fetch();

    // 전체 진행자 수
    $total_processing_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%면접 예정%'";
    $total_processing_stt=$db_conn->prepare($total_processing_sql);
    $total_processing_stt->execute();
    $total_processing = $total_processing_stt -> fetch();
    // 오늘 진행자 수
    $today_processing_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%면접 예정%' AND DATE(write_date) = DATE(NOW())";
    $today_processing_stt=$db_conn->prepare($today_processing_sql);
    $today_processing_stt->execute();
    $today_processing = $today_processing_stt -> fetch();

    // 전체 완료자 수
    $total_finish_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%최종 합격%'";
    $total_finish_stt=$db_conn->prepare($total_finish_sql);
    $total_finish_stt->execute();
    $total_finish = $total_finish_stt -> fetch();
    // 오늘 완료자 수
    $today_finish_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%최종 합격%' AND DATE(write_date) = DATE(NOW())";
    $today_finish_stt=$db_conn->prepare($today_finish_sql);
    $today_finish_stt->execute();
    $today_finish = $today_finish_stt -> fetch();

    $stDate = "";
    $edDate = "";

    if (isset($_POST["stDate"]) && isset($_POST["edDate"])) {
        $stDate = $_POST["stDate"];
        $edDate = $_POST["edDate"];
        //문의 건수
        $search_contact_sql = "SELECT COUNT(contact_cnt) FROM contact_log_tbl WHERE reg_date BETWEEN '$stDate' AND '$edDate';";
        $search_contact_stt=$db_conn->prepare($search_contact_sql);
        $search_contact_stt->execute();
        $search_contact = $search_contact_stt -> fetch();
        //조회수
        $search_view_sql = "SELECT COUNT(view_cnt) FROM view_log_tbl WHERE reg_date BETWEEN '$stDate' AND '$edDate';";
        $search_view_stt=$db_conn->prepare($search_view_sql);
        $search_view_stt->execute();
        $search_view = $search_view_stt -> fetch();
        // 대기자 수
        $search_waiting_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%대기%' AND write_date BETWEEN '$stDate' AND '$edDate'";
        $search_waiting_stt=$db_conn->prepare($search_waiting_sql);
        $search_waiting_stt->execute();
        $search_waiting = $search_waiting_stt -> fetch();
        // 진행자 수
        $search_processing_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%진행%' AND write_date BETWEEN '$stDate' AND '$edDate'";
        $search_processing_stt=$db_conn->prepare($search_processing_sql);
        $search_processing_stt->execute();
        $search_processing = $search_processing_stt -> fetch();
        // 완료 수
        $search_finish_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%완료%' AND write_date BETWEEN '$stDate' AND '$edDate'";
        $search_finish_stt=$db_conn->prepare($search_finish_sql);
        $search_finish_stt->execute();
        $search_finish = $search_finish_stt -> fetch();


    } else {
        $stDate = "";
        $edDate = "";

    }

    // 담당자 쿼리
    $admin_sql = "select * from admin_tbl order by id";
    $admin_stt=$db_conn->prepare($admin_sql);
    $admin_stt->execute();

?>

<link rel="stylesheet" type="text/css" href="css/home.css" rel="stylesheet" />

    <div class="page-header">
        <h4 class="page-title">로그 관리</h4>
        <div class="content-container">
           <div class="content-wrap">
               <p class="tit">전체</p>
               <div class="view-wrap">
                   <div class="item">
                       <p class="name">방문자 수</p>
                       <p class="cnt"><?=number_format($total_view[0])?></p>
                   </div>
                   <div class="item">
                       <p class="name">지원자</p>
                       <p class="cnt"><?=number_format($total_contact[0])?></p>
                   </div>
                   <div class="item">
                       <p class="name">면접 대기자</p>
                       <p class="cnt"><?=number_format($total_waiting[0])?></p>
                   </div>
                   <div class="item">
                       <p class="name">면접자</p>
                       <p class="cnt"><?=number_format($total_processing[0])?></p>
                   </div>
                   <div class="item">
                       <p class="name">합격자<p>
                       <p class="cnt"><?=number_format($total_finish[0])?></p>
                   </div>
               </div>
           </div>
            <div class="content-wrap">
                <p class="tit">오늘</p>
                <div class="view-wrap">
                    <div class="item">
                        <p class="name">방문자 수</p>
                        <p class="cnt"><?=number_format($today_view[0])?></p>
                    </div>
                    <div class="item">
                        <p class="name">지원자</p>
                        <p class="cnt"><?=number_format($today_contact[0])?></p>
                    </div>
                    <div class="item">
                        <p class="name">면접 대기자</p>
                        <p class="cnt"><?=number_format($today_waiting[0])?></p>
                    </div>
                    <div class="item">
                        <p class="name">면접자</p>
                        <p class="cnt"><?=number_format($today_processing[0])?></p>
                    </div>
                    <div class="item">
                        <p class="name">합격자<p>
                        <p class="cnt"><?=number_format($today_finish[0])?></p>
                    </div>
                </div>
            </div>
            <div class="content-wrap">
                <p class="tit">선택 조회</p>
                <form name="search_form" id="search_form" method="post" action="index.php?menu=0">
                    <div class="choice-wrap">
                        <div class="item">
                            <p>시작 날짜</p>
                            <input type="date" name="stDate" class="date form-control" value="<?= $stDate ?>"/>
                        </div>
                        <div class="item">
                            <p>종료 날짜</p>
                            <input type="date" name="edDate" class="date form-control" value="<?= $edDate ?>"/>
                        </div>
                        <div class="item">
                            <input type="submit" value="검색하기" />
                        </div>
                    </div>
                </form>
                <div class="result">
                    <?php if($stDate != ""){ ?>
                    <p class="tit">검색 결과</p>
                        <div class="view-wrap">
                        <div class="item">
                            <p class="name">방문자 수</p>
                            <p class="cnt"><?=number_format($search_view[0])?></p>
                        </div>
                        <div class="item">
                            <p class="name">지원자</p>
                            <p class="cnt"><?=number_format($search_contact[0])?></p>
                        </div>
                        <div class="item">
                            <p class="name">면접 대기자</p>
                            <p class="cnt"><?=number_format($search_waiting[0])?></p>
                        </div>
                        <div class="item">
                            <p class="name">면접자</p>
                            <p class="cnt"><?=number_format($search_processing[0])?></p>
                        </div>
                            <div class="item">
                                <p class="name">합격자<p>
                                <p class="cnt"><?=number_format($search_finish[0])?></p>
                            </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="content-wrap">
                <p class="tit">이용중인 부가서비스</p>
                <div class="form-check form-switch ">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" onclick="return false;" readonly>
                    <label class="form-check-label" for="flexSwitchCheckDefault">챗봇 서비스</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked onclick="return false;" readonly>
                    <label class="form-check-label" for="flexSwitchCheckDefault">알림 문자 서비스</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" onclick="return false;" readonly>
                    <label class="form-check-label" for="flexSwitchCheckDefault">법정의무교육</label>
                    <a href="service_send.php?sid=2&menu=10" class="go-link">신청하기</a>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" onclick="return false;" readonly>
                    <label class="form-check-label" for="flexSwitchCheckDefault">임직원 역량 강화 교육 및 컨설팅</label>
                    <a href="service_send.php?sid=2&menu=10" class="go-link">신청하기</a>
                </div>
            </div>
            <div class="content-wrap">
                <p class="tit">담당자 현황</p>

                <div class="admin-list">
                    <div class="item">
                        <p class="admin-name">이름</p>
                        <p class="admin-name">담당 건수</p>
                        <p class="admin-name">성사 건수</p>
                    </div>
                    <?php
                    while($list_row=$admin_stt->fetch()){

                        $admin_cnt_sql = "
                            SELECT COUNT(DISTINCT c.id) as manager, (
                            
                            SELECT COUNT(DISTINCT cc.id) FROM admin_tbl as aa, contact_tbl as cc WHERE  cc.manager_fk = " .$list_row['id']. " AND cc.result_status like '%완료%'
            
                            ) as result  FROM admin_tbl as a, contact_tbl as c where c.manager_fk = " .$list_row['id'];

                        $admin_cnt_stt=$db_conn->prepare($admin_cnt_sql);
                        $admin_cnt_stt->execute();
                        $admin_cnt = $admin_cnt_stt -> fetch();

                    ?>
                    <div class="item">
                        <p class="val"><?=$list_row['login_name']?></p>
                        <p class="val"><?=$admin_cnt[0]?></p>
                        <p class="val"><?=$admin_cnt[1]?></p>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- box end -->
</div>
<!-- content-box-wrap end -->

<script type='text/javascript'>

</script>
