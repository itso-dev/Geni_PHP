<?php
include "Classes/PHPExcel.php";
include_once('../../db/dbconfig.php');

$type = "";
if(isset($_GET['type'])){
    $type = $_GET['type'];
}
if($type == 'all'){
    $list_sql = "select * from contact_tbl order by id desc";
    $list_stt=$db_conn->prepare($list_sql);
    $list_stt->execute();
} else {
    $chk =$_POST['chk'];
    $chk_count = count($_POST['chk']);


    $list_sql = "select * from contact_tbl
                            where
                    id IN (" . implode(',', array_map('intval', $chk)) .")
                            order by id";
    $list_stt=$db_conn->prepare($list_sql);
    $list_stt->execute();


}

$phpExcel = new PHPExcel();

$phpExcel->setActiveSheetIndex(0);
$phpExcel->getActiveSheet()
    ->setCellValue("A1", "지원 번호")
    ->setCellValue("B1", "생성일")
    ->setCellValue("C1", "지원자 명")
    ->setCellValue("D1", "연락처")
    ->setCellValue("E1", "출생년도")
    ->setCellValue("F1", "지원자 거주지")
    ->setCellValue("G1", "희망 근무지")
    ->setCellValue("H1", "희망 면접 일자")
    ->setCellValue("I1", "면접 시간")
    ->setCellValue("J1", "면접 일정 변경")
    ->setCellValue("K1", "변경 신청일")
    ->setCellValue("L1", "추천인 정보")
    ->setCellValue("M1", "추천인 상세")
    ->setCellValue("N1", "인재풀 등록")
    ->setCellValue("O1", "결과")
    ->setCellValue("P1", "아이피");

$line = 2;
$recommender = "";
while($list_row=$list_stt->fetch()) {

    if( $list_row['recommender'] == '선택안함'){
        $recommender = "";
    } else{
        $recommender = $list_row['recommender'];
    }

    $phpExcel->setActiveSheetIndex(0)
        ->setCellValue("A".$line, $list_row['apply_num'])
        ->setCellValue("B".$line, $list_row['write_date'])
        ->setCellValue("C".$line, $list_row['name'])
        ->setCellValue("D".$line, $list_row['phone'])
        ->setCellValue("E".$line, $list_row['birth_date'])
        ->setCellValue("F".$line,$list_row['address'])
        ->setCellValue("G".$line,$list_row['location'])
        ->setCellValue("H".$line,$list_row['apply_date'])
        ->setCellValue("I".$line,$list_row['apply_time'])
        ->setCellValue("J".$line,$list_row['apply_modify_yn'])
        ->setCellValue("K".$line,$list_row['apply_modify_date'])
        ->setCellValue("L".$line,$recommender)
        ->setCellValue("M".$line,$list_row['recommender_name'])
        ->setCellValue("N".$line,$list_row['agree'])
        ->setCellValue("O".$line,$list_row['result_status'])
        ->setCellValue("P".$line,$list_row['writer_ip']);
    $line++;
}

header('Content-Type: application/vnd.ms-excel');
header( "Content-Disposition: attachment; filename = i.M지니_".date('Y-m-d H:i:s') .".xls" );     //filename = 저장되는 파일명을 설정합니다.
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
$objWriter->save('php://output');

?>
