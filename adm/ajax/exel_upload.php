<?php
include "Classes/PHPExcel.php";
include_once('../../db/dbconfig.php');

$objPHPExcel = new PHPExcel();

// 엑셀 데이터를 담을 배열을 선언한다.
$allData = array();
$site_path = $_SERVER["DOCUMENT_ROOT"];



if (isset($_FILES['excelFile']) && $_FILES['excelFile']['name'] != "") {
    $filename = $_FILES['excelFile']['name'];
    $file1 = $_FILES['excelFile'];
    $upload_directory = $site_path . '/data/exel/';
    $ext_str = "xls,xlsx";
    $allowed_extensions = explode(',', $ext_str);
    $max_file_size = 5242880;
    $ext1 = substr($file1['name'], strrpos($file1['name'], '.') + 1);

    // 확장자 체크
    if (!in_array($ext1, $allowed_extensions)) {
        echo "<script type='text/javascript'>";
        echo "alert('이미지 파일만 업로드 하실 수 있습니다.'); history.back()";
        echo "</script>";
    }
    // 파일 크기 체크
    if ($file1['size'] >= $max_file_size) {
        echo "<script type='text/javascript'>";
        echo "alert('5MB 이하의 파일만 업로드 가능합니다.'); history.back()";
        echo "</script>";
    }
    // 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
//    if(preg_match("/[\xA1-\xFE][\xA1-\xFE]/", $filename)) {
//        echo "<script type='text/javascript'>";
//        echo "alert('영문 파일만 업로드 가능합니다.'); history.back()";
//        echo "</script>";
//    }

    //파일명 변경
    $chg_file = date("Y-m-d H:i:s:u") .$file1['name'];
    $chg_file = str_replace(' ', '', $chg_file);
    if (move_uploaded_file($file1['tmp_name'], $upload_directory .  $chg_file)) {


        try {
            // 업로드한 PHP 파일을 읽어온다.
            $objPHPExcel = PHPExcel_IOFactory::load($upload_directory.$chg_file);
            $sheetsCount = $objPHPExcel -> getSheetCount();

            // 시트Sheet별로 읽기
            for($i = 0; $i < $sheetsCount; $i++) {
                $objPHPExcel -> setActiveSheetIndex($i);
                $sheet = $objPHPExcel -> getActiveSheet();
                $highestRow = $sheet -> getHighestRow();   			           // 마지막 행
                $highestColumn = $sheet -> getHighestColumn();	// 마지막 컬럼

                // 한줄읽기
                for($row = 1; $row <= $highestRow; $row++) {
                    // $rowData가 한줄의 데이터를 셀별로 배열처리 된다.
                    $rowData = $sheet -> rangeToArray("A" . $row . ":" . $highestColumn . $row, NULL, TRUE, FALSE);

                    // $rowData에 들어가는 값은 계속 초기화 되기때문에 값을 담을 새로운 배열을 선안하고 담는다.
                    $allData[$row] = $rowData[0];
                }
            }
        } catch(exception $e) {
            echo $e;
        }

        echo "<pre>";
        if($i == 1){
            if($allData[1][0] == '지원 번호' && $allData[1][1] == '생성일' && $allData[1][2] == '지원자 명' && $allData[1][3] == '연락처' && $allData[1][4] == '출생년도' && $allData[1][5] == '지원자 거주지' && $allData[1][6] == '희망 근무지' && $allData[1][7] == '희망 면접 일자' && $allData[1][8] == '면접 시간' && $allData[1][9] == '면접 일정 변경' && $allData[1][10] == '변경 신청일' && $allData[1][11] == '추천인 정보' && $allData[1][12] == '추천인 상세' && $allData[1][13] == '인재풀 등록' && $allData[1][14] == '결과' && $allData[1][15] == '아이피'){
            }
            else{
                echo "<script type='text/javascript'>";
                echo "alert('엑셀 항목이 일치하지 않습니다.'); history.back()";
                echo "</script>";
            }
        }
        for($i = 2; $i < count($allData)+1; $i++){


            $sql = "insert into contact_tbl
                (name, phone, birth_date, address, location,
                 recommender, recommender_name, agree, result_status,
                 consult_fk, manager_fk, writer_ip, write_date,
                 apply_num, apply_date, apply_time, apply_modify_yn, apply_modify_date)
            value
                (?, ?, ?, ?, ?, 
                ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?, ?, ?)";
            $db_conn->prepare($sql)->execute(
                [
                    $allData[$i][2],
                    $allData[$i][3],
                    $allData[$i][4],
                    $allData[$i][5],
                    $allData[$i][6],
                    $allData[$i][11],
                    $allData[$i][12],
                    $allData[$i][13],
                    $allData[$i][14],
                    0,
                    0,
                    $allData[$i][15],
                    $allData[$i][1],
                    $allData[$i][0],
                    $allData[$i][7],
                    $allData[$i][8],
                    $allData[$i][9],
                    $allData[$i][10],
                ]
            );
        }
        echo "</pre>";
        echo "<script type='text/javascript'>";
        echo "alert('등록을 완료했습니다.'); location.href='../apply_list.php?menu=1'";
        echo "</script>";
    }

}
?>
