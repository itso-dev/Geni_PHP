<?php
    //동부
    //강동
    $locate_sql1 = "SELECT 
                      (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '강동') AS cnt,
                      locate_tbl.* 
                    FROM 
                      locate_tbl 
                    WHERE 
                      locate_detail = '강동' 
                    ORDER BY 
                      id;";
    $locate_stt1 = $db_conn->prepare($locate_sql1);
    $locate_stt1->execute();
    //강동
    $locate_sql2 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '송파') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '송파' 
                        ORDER BY 
                          id;";
    $locate_stt2 = $db_conn->prepare($locate_sql2);
    $locate_stt2->execute();
    //서부
    //강서
    $locate_sql3 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '강서') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '강서' 
                        ORDER BY 
                          id;";
    $locate_stt3 = $db_conn->prepare($locate_sql3);
    $locate_stt3->execute();
    //마포
    $locate_sql4 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '마포') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '마포' 
                        ORDER BY 
                          id;";
    $locate_stt4 = $db_conn->prepare($locate_sql4);
    $locate_stt4->execute();
    //서대문
    $locate_sql5 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '서대문') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '서대문' 
                        ORDER BY 
                          id;";
    $locate_stt5 = $db_conn->prepare($locate_sql5);
    $locate_stt5->execute();
    //은평
    $locate_sql6 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '은평') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '은평' 
                        ORDER BY 
                          id;";
    $locate_stt6 = $db_conn->prepare($locate_sql6);
    $locate_stt6->execute();
    //중부
    //성동
    $locate_sql7 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '성동') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '성동' 
                        ORDER BY 
                          id;";
    $locate_stt7 = $db_conn->prepare($locate_sql7);
    $locate_stt7->execute();
    //남부
    //구로
    $locate_sql8 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '구로') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '구로' 
                        ORDER BY 
                          id;";
    $locate_stt8 = $db_conn->prepare($locate_sql8);
    $locate_stt8->execute();
    //동작
    $locate_sql9 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '동작') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '동작' 
                        ORDER BY 
                          id;";
    $locate_stt9 = $db_conn->prepare($locate_sql9);
    $locate_stt9->execute();
    //영등포
    $locate_sql10 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '영등포') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '영등포' 
                        ORDER BY 
                          id;";
    $locate_stt10 = $db_conn->prepare($locate_sql10);
    $locate_stt10->execute();
    //북부
    //도봉
    $locate_sql11 = "SELECT 
                          (SELECT COUNT(*) FROM locate_tbl WHERE locate_detail = '도봉') AS cnt,
                          locate_tbl.* 
                        FROM 
                          locate_tbl 
                        WHERE 
                          locate_detail = '도봉' 
                        ORDER BY 
                          id;";
    $locate_stt11 = $db_conn->prepare($locate_sql11);
    $locate_stt11->execute();

?>
