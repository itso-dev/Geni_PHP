<?php
include_once('head.php');
include_once('interview_ajax/apply_locate_ajax.php');
$id = "";
$name = "";
if(!isset($_GET['id'])){
    echo "<script type='text/javascript'>";
    echo "alert('비정상적인 접근입니다.'); location.href='/'";
    echo "</script>";
}else{
    $id = $_GET['id'];
}

function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
    opcache_reset();
}

$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
$isMobile = "";
if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])){
    $isMobile = true;
}else{
    $isMobile = false;
}

?>
<title>i.M 지니 입사지원</title>

<link rel="stylesheet" type="text/css" href="css/apply.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="js/reservation.js"></script>


<script>
    let openSelect = false;
    function onSelect(event) {
        let selectList = document.querySelector('.select-list-wrap');

        if (openSelect) {
            selectList.classList.remove('flex');
            openSelect = false;
        } else {
            selectList.classList.add('flex');
            openSelect = true;
        }
    };
    function clickSelect(event) {
        let selectList = document.querySelector('.select-list-wrap');
        if (!event.target.classList.contains('select-wrap') && !event.target.classList.contains('arr')) {
            selectList.classList.remove('flex');
        } else {
            selectList.classList.add('flex');
        }
    }

    /* 입력 오류 */
    function onInput(obj, id) {
        let addressInput = document.querySelector('.address-input');

        if (id == 'name' && obj.value == '') {
            obj.placeholder = '성명을 입력해 주세요.';
            obj.classList.add('error-input')
        } else if (id == 'phone' && obj.value == '') {
            obj.placeholder = '연락처를 입력해 주세요.';
            obj.classList.add('error-input')
        } else if (id == 'birth' && obj.value == '') {
            obj.placeholder = '출생년도를 입력해 주세요.';
            obj.classList.add('error-input')
        } else if (id == 'address' && obj.value == '') {
            obj.placeholder = '거주지를 입력해 주세요.';
            addressInput.classList.add('error-input')
        } else if (id == 'address2' && obj.value == '') {
            obj.placeholder = '';
            obj.classList.add('error-input')
        } else {
            obj.classList.remove('error-input')
        }
    }

    /* 체크박스 하나만 선택 */
    function checkOnlyOne(element, name) {
        const checkboxes = document.getElementsByName(name);
        checkboxes.forEach((cb) => {
            cb.checked = false;
        })
        element.checked = true;
    }

    /* select 클릭 시 선택 & 값 변경 */
    const clickOption = (target) => {
        const recommender = document.getElementsByName('recommender');
        recommender[0].value = target.value;
        $("#reco-text").text(target.value);
    }
    const clickOptionFlow = (target) => {
        const flow = document.getElementsByName('flow');
        flow[0].value = target.value;
        $("#flow-text").text(target.value);
    }

    /* 주소 */
    function findAddr() {
        new daum.Postcode({
            oncomplete: function (data) {
                var roadAddr = data.roadAddress; // 도로명 주소 변수
                var jibunAddr = data.jibunAddress; // 지번 주소 변수

                if (roadAddr !== '') {
                    document.getElementById("address1").value = roadAddr;
                }
                else if (jibunAddr !== '') {
                    document.getElementById("address1").value = jibunAddr;
                }
            }
        }).open();
    }
</script>

<body>
    <div onclick="clickSelect(event)">
        <header id="apply-header">
            <img src="img/apply/logo.blue.png" />
        </header>

        <section id="apply-container">
            <form name="contact_form" id="contact_form" method="post" action="apply_modify_write.php" onsubmit='return frmSubmit();'>
                <input type="hidden" name="id" value="<?= $id ?>" />
                <article class="apply-page apply-input max">
                    <div class="field-wrap">
                        <input type="hidden" class="locationYn" name="locationYn" value="">
                        <p class="field-title">1. 희망 근무지<span>(필수)</span><b class="star">*</b></p>
                        <p class="field-text">*먼저 희망 권역을 선택 후, 해당 권역의 운수사를 선택해 주세요.</p>
                        <div class="locate-tab">
                            <div class="tab active">동부</div>
                            <div class="tab">서부</div>
                            <div class="tab">중부</div>
                            <div class="tab">남부</div>
                            <div class="tab">북부</div>
                        </div>
                        <div class="locate-tab-container">
                            <div class="tab-wrap">
                               <table>
                                   <thead>
                                     <tr>
                                         <td>지역</td>
                                         <td></td>
                                         <td>운수사</td>
                                         <td class="pc">위치</td>
                                         <td class="pc">인근 지하철역</td>
                                         <td class="mobile">위치/인근 지하철역</td>
                                     </tr>
                                   </thead>
                                   <tbody>
                                    <!--    강동    -->
                                   <?php
                                   $row = 0;
                                   while ($list1 = $locate_stt1->fetch()) {
                                   $row++;
                                   $cnt = $list1['cnt'];
                                   $name = $list1['name'];
                                   $address = $list1['address'];
                                   $subway = nl2br($list1['subway']);
                                   if($isMobile){
                                       $cnt = $cnt*2;
                                   }
                                   ?>
                                     <tr>
                                         <?php if($row == 1){ ?>
                                        <td class="center" rowspan="<?= $cnt ?>">강동</td>
                                         <?php }  ?>
                                        <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                            <input type="checkbox" name="location"
                                                   onclick="locateChk(this, '강동', '<?= $name ?>', '<?= $address ?>')"
                                                   value="<?= $name ?>">
                                            <span class="readonly"><?= $subway ?></span>
                                        </td>
                                        <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                         <td class="address"><?= $address ?></td>
                                        <td class="subway pc"><?= $subway ?></td>
                                     </tr>
                                     <tr class="mobile">
                                       <td><?= $subway ?></td>
                                     </tr>
                                   <?php } ?>
                                    <!--    송파    -->
                                   <?php
                                   $row = 0;
                                   while ($list2 = $locate_stt2->fetch()) {
                                       $row++;
                                       $cnt = $list2['cnt'];
                                       $name = $list2['name'];
                                       $address = $list2['address'];
                                       $subway = nl2br($list2['subway']);
                                       if($isMobile){
                                           $cnt = $cnt*2;
                                       }
                                       ?>
                                       <tr>
                                           <?php if($row == 1){ ?>
                                               <td class="center" rowspan="<?= $cnt ?>">송파</td>
                                           <?php }  ?>
                                           <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                               <input type="checkbox" name="location"
                                                      onclick="locateChk(this, '송파', '<?= $name ?>', '<?= $address ?>')"
                                                      value="<?= $name ?>">
                                               <span class="readonly"><?= $subway ?></span>
                                           </td>
                                           <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                           <td class="address"><?= $address ?></td>
                                           <td class="subway pc"><?= $subway ?></td>
                                       </tr>
                                       <tr class="mobile">
                                           <td><?= $subway ?></td>
                                       </tr>
                                   <?php } ?>
                                   </tbody>
                               </table>
                            </div>
                            <div class="tab-wrap">
                                <table>
                                    <thead>
                                    <tr>
                                        <td>지역</td>
                                        <td></td>
                                        <td>운수사</td>
                                        <td class="pc">위치</td>
                                        <td class="pc">인근 지하철역</td>
                                        <td class="mobile">위치/인근 지하철역</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!--    강서    -->
                                    <?php
                                    $row = 0;
                                    while ($list3 = $locate_stt3->fetch()) {
                                        $row++;
                                        $cnt = $list3['cnt'];
                                        $name = $list3['name'];
                                        $address = $list3['address'];
                                        $subway = nl2br($list3['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">강서</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '강서', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    <!--    마포    -->
                                    <?php
                                    $row = 0;
                                    while ($list4 = $locate_stt4->fetch()) {
                                        $row++;
                                        $cnt = $list4['cnt'];
                                        $name = $list4['name'];
                                        $address = $list4['address'];
                                        $subway = nl2br($list4['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">마포</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '마포', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    <!--    서대문    -->
                                    <?php
                                    $row = 0;
                                    while ($list5 = $locate_stt5->fetch()) {
                                        $row++;
                                        $cnt = $list5['cnt'];
                                        $name = $list5['name'];
                                        $address = $list5['address'];
                                        $subway = nl2br($list5['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">서대문</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '서대문', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    <!--    은평    -->
                                    <?php
                                    $row = 0;
                                    while ($list6 = $locate_stt6->fetch()) {
                                        $row++;
                                        $cnt = $list6['cnt'];
                                        $name = $list6['name'];
                                        $address = $list6['address'];
                                        $subway = nl2br($list6['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">은평</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '은평', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-wrap">
                                <table>
                                    <thead>
                                    <tr>
                                        <td>지역</td>
                                        <td></td>
                                        <td>운수사</td>
                                        <td class="pc">위치</td>
                                        <td class="pc">인근 지하철역</td>
                                        <td class="mobile">위치/인근 지하철역</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!--    성동    -->
                                    <?php
                                    $row = 0;
                                    while ($list7 = $locate_stt7->fetch()) {
                                        $row++;
                                        $cnt = $list7['cnt'];
                                        $name = $list7['name'];
                                        $address = $list7['address'];
                                        $subway = nl2br($list7['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">성동</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '성동', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-wrap">
                                <table>
                                    <thead>
                                    <tr>
                                        <td>지역</td>
                                        <td></td>
                                        <td>운수사</td>
                                        <td class="pc">위치</td>
                                        <td class="pc">인근 지하철역</td>
                                        <td class="mobile">위치/인근 지하철역</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!--    구로    -->
                                    <?php
                                    $row = 0;
                                    while ($list8 = $locate_stt8->fetch()) {
                                        $row++;
                                        $cnt = $list8['cnt'];
                                        $name = $list8['name'];
                                        $address = $list8['address'];
                                        $subway = nl2br($list8['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">구로</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '구로', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    <!--    동작    -->
                                    <?php
                                    $row = 0;
                                    while ($list9 = $locate_stt9->fetch()) {
                                        $row++;
                                        $cnt = $list9['cnt'];
                                        $name = $list9['name'];
                                        $address = $list9['address'];
                                        $subway = nl2br($list9['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">동작</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '동작', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    <!--    영등포    -->
                                    <?php
                                    $row = 0;
                                    while ($list10 = $locate_stt10->fetch()) {
                                        $row++;
                                        $cnt = $list10['cnt'];
                                        $name = $list10['name'];
                                        $address = $list10['address'];
                                        $subway = nl2br($list10['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">영등포</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '영등포', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-wrap">
                                <table>
                                    <thead>
                                    <tr>
                                        <td>지역</td>
                                        <td></td>
                                        <td>운수사</td>
                                        <td class="pc">위치</td>
                                        <td class="pc">인근 지하철역</td>
                                        <td class="mobile">위치/인근 지하철역</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!--    도봉    -->
                                    <?php
                                    $row = 0;
                                    while ($list11 = $locate_stt11->fetch()) {
                                        $row++;
                                        $cnt = $list11['cnt'];
                                        $name = $list11['name'];
                                        $address = $list11['address'];
                                        $subway = nl2br($list11['subway']);
                                        if($isMobile){
                                            $cnt = $cnt*2;
                                        }
                                        ?>
                                        <tr>
                                            <?php if($row == 1){ ?>
                                                <td class="center" rowspan="<?= $cnt ?>">도봉</td>
                                            <?php }  ?>
                                            <td class="center" <?php if($isMobile) echo "rowspan='2'" ?>>
                                                <input type="checkbox" name="location"
                                                       onclick="locateChk(this, '도봉', '<?= $name ?>', '<?= $address ?>')"
                                                       value="<?= $name ?>">
                                                <span class="readonly"><?= $subway ?></span>
                                            </td>
                                            <td class="name" <?php if($isMobile) echo "rowspan='2'" ?>><?= $name ?></td>
                                            <td class="address"><?= $address ?></td>
                                            <td class="subway pc"><?= $subway ?></td>
                                        </tr>
                                        <tr class="mobile">
                                            <td><?= $subway ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="field-wrap">
                        <p class="field-title">2. 희망 면접 일정 <span>(필수)</span><b class="star">*</b></p>
                        <div class="field-input-text-wrap interview-field">
                            <p class="field-text">*면접 일정 변경은 1회에 한해 가능하며, 불참 시 불합격 처리 됩니다.</p>
                            <div class="interview-container">
                                <div class="info-wrap item">
                                    <p class="label">운수사를 확인해주세요.</p>
                                    <span class="label-s">지역</span>
                                    <p id="locate-val" class="val"></p>
                                    <input type="hidden" name="apply_locate" />
                                    <span class="label-s">운수사 명</span>
                                    <p id="name-val" class="val"></p>
                                    <input type="hidden" name="apply_name" />
                                    <span class="label-s">위치</span>
                                    <p id="address-val" class="val"></p>
                                    <input type="hidden" name="apply_address" />
                                    <span class="label-s">인근 지하철역</span>
                                    <p id="subway-val" class="val"></p>
                                    <input type="hidden" name="apply_subway" />
                                </div>
                                <div class="calender-wrap item">
                                    <input type="hidden" name="apply_date" value="" />
                                    <p class="label">날짜를 선택해 주세요.</p>
                                    <div class="calender-area">
                                        <table class="Calendar">
                                            <thead>
                                            <tr class="thead">
                                                <td colspan="7">
                                                    <div class="date-wrap">
                                                        <span id="calYear" ></span>년
                                                        <span id="calMonth" ></span>월
                                                    </div>
                                                    <div class="btn-wrap">

                                                        <img class="btn" src="img/apply/left.png" onClick="prevCalendar();" />
                                                        <img class="btn" src="img/apply/right.png" onClick="nextCalendar();" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="week">
                                                <td>일</td>
                                                <td>월</td>
                                                <td>화</td>
                                                <td>수</td>
                                                <td>목</td>
                                                <td>금</td>
                                                <td>토</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="time-wrap item">
                                    <input type="hidden" name="apply_time" value="" />
                                    <p class="label">시간을 선택해 주세요.</p>
                                    <div class="time-list">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="g-recaptcha" name="g-recaptcha">
                    <input class="submit-btn" type="submit" value="면접 일정 변경 신청">
                </article>
            </form>
        </section>
    </div>

    <script src='https://www.google.com/recaptcha/api.js?render=6LeIUPcmAAAAAKknvdvB6rUxzAeGwrQrm3tGMnrV'></script>
    <script>


        grecaptcha.ready(function() {
            grecaptcha.execute('6LeIUPcmAAAAAKknvdvB6rUxzAeGwrQrm3tGMnrV', {action: 'submit'}).then(function(token) {
                document.getElementById('g-recaptcha').value = token;
            });
        });

        window.onpageshow = function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type == 2)) {
                // Back Forward Cache로 브라우저가 로딩될 경우 혹은 브라우저 뒤로가기 했을 경우
                location.href = "/";
            }
        }

        $("input[name=recommender_name]").click(function (){
            if($("input[name=recommender]").val() == "(선택)"){
                alert("추천인을 선택해주세요.");
                $("input[name=recommender]").focus();
            }
            else{
                $(this).attr("readonly", false);
            }
        });

        $(".locate-tab .tab").click(function (){
            var idx = $(this).index();

            $(".locate-tab .tab").removeClass('active');
            $(this).addClass('active');

            $(".locate-tab-container .tab-wrap").hide();
            $(".locate-tab-container .tab-wrap").eq(idx).fadeIn(400);


        });

        function locateChk(element, locate, name, address) {
            $("input[name=location]").prop('checked', false);
            $(element).prop('checked', true);
            $(".locationYn").val(name);
            var subway = $(element).siblings(".readonly").html();
            $("#locate-val").text(locate);
            $("#name-val").text(name);
            $("#address-val").text(address);
            $("#subway-val").html(subway);

            $("input[name=apply_locate]").val(locate);
            $("input[name=apply_name]").val(name);
            $("input[name=apply_address]").val(address);
            $("input[name=apply_subway]").val(subway);
        }

        function maxLengthCheck(object){
            if (object.value.length > object.maxLength){
                object.value = object.value.slice(0, object.maxLength);
            }
        }
        function frmSubmit(){
            if(!$("input[name=locationYn]").val()) {
                alert("희망 근무지를 체크해주세요");
                return false;
            }
            if(!$("input[name=apply_date]").val()){
                alert("면접 날짜를 선택해주세요.")
                $(".interview-field").focus();
                return false;
            }
            if(!$("input[name=apply_time]").val()){
                alert("면접 시간을 선택해주세요.")
                $(".interview-field").focus();
                return false;
            }
            document.contact_form.submit()
        }
    </script>

    <?php
    include_once('tale.php');
    ?>
