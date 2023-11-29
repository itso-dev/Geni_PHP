<?php
include_once('head.php');
include_once('interview_ajax/apply_locate_ajax.php');

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
    // function locateChk(element, locate, name, address) {
    //     const checkboxes = document.getElementsByName("location");
    //     checkboxes.forEach((cb) => {
    //         cb.checked = false;
    //     })
    //     element.checked = true;
    //
    //     const test = element.nextSibling;
    //     alert(test);
    //     $(".locationYn").val('1');
    //
    //     $("#locate-val").text(locate);
    //     $("#name-val").text(name);
    //     $("#address-val").text(address);
    //     // $("#subway-val").text(subway);
    //
    // }


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
        <form name="contact_form" id="contact_form" method="post" action="apply_write.php" onsubmit='return frmSubmit();'>
            <input type="hidden" name="writer_ip" value="<?= get_client_ip() ?>" />
            <article class="apply-page max">
                <p class="title">i.M 정규직 드라이버<br class="mo-br768" /> <span>'지니’ 입사 지원</span></p>
                <p class="sub-title">
                    좋은 시간을 타세요. i.M Good Ti.Me!<br />
                    i.M과 함께 프리미엄 이동 서비스를 선도하실 '지니'님을 모십니다.
                </p>
                <p class="sub-title">
                    i.M은 '지니' 님들께 업계 최고 수준의 급여를 제공하고<br />
                    쾌적한 근무환경 조성을 위해 노력하고 있습니다.
                </p>
                <img src="img/apply/im.png" class="car" />
            </article>
            <div class="line"></div>
            <article class="apply-page max">
                <p class="terms-title">
                    <strong>개인정보 수집, 이용, 위탁</strong>에<br class="mo-br768" /> 동의하시나요?
                </p>

                <div class="terms-box">
                    ※ i.M과 제휴한 <span>법인택시회사 소속으로 근무</span>하며, 법인택시회사와 근로계약서 작성하게 됩니다.<br />
                    ※ 법인택시회사 상황에 따라 면접 일정은 상이하며, 최종 채용여부는 법인택시회사가 판단합니다.<br />
                    ※ i.M과 제휴한 법인택시회사는 &lt;개인정보보호법&gt; 등 개인정보 관련 법령에 따라 구직자의 개인 정보를 안전하게 보관 및 관리하고 있습니다.<br />
                    당사는 구직자의 개인정보를 수집 • 이용하고자 하는 경우에는 &lt;개인정보보호법&gt;에 따라 적법한 동의 절차를 걸쳐야 하며.<br />
                    이에 아래의 내용을 확 후 본인의 개인정보를 수집 • 이용하는데 동의해야 합니다.<br />
                    본 동의는 서비스를 이용하는 최소한의 개인정보를 수집하며, 동의 거부 시 서비스 이용에 제한됩니다.<br />
                    <br />
                    <p class="tit">[필수] 개인정보 수집, 이용, 위탁 동의</p>
                    (주)진모빌리티는 다음과 같이 최소한의 범위 내에서 개인정보를 수집 및 이용합니다.<br />
                    - 수집 항목 : 이름, 휴대전화번호, 생년월일, 주소, 희망 근무지<br />
                    - 수집 및 이용 목적 : 개인 식별 및 본인 확인, 입사 지원, 자격 확인, 문자메시지 등을 통한 채용전형 안내,
                    고지사항 전달, 데이터 분석, 문의 및 상담<br />
                    - 보유 및 이용 기간 : 채용 전형 완료 시까지<br />
                    - 위탁기관 : (주)진모빌리티, i.M과 제휴한 법인택시회사, 채용 지원 유관기관, 채용 마케팅 운영사<br />
                    - 위탁내용: 채용전형 문의 및 채용설명회, 행사 등 관련 안내<br />

                    위 개인정보 수집, 이용, 위탁에 동의하지 않으실 수 있으며, 동의하지 않으실 경우 지원서 작성이 제한됩니다.
                </div>
                <label class="input-checkbox-wrap">
                    <input type="checkbox" class="input-checkbox" required />
                    <p>동의합니다.</p>
                </label>
            </article>
            <div class="line"></div>
            <article class="apply-page apply-input max">
                <div class="field-wrap">
                    <p class="field-title">1. 지원자 성명 <span>(필수)</span><b class="star">*</b></p>
                    <div class="field-input-text-wrap">
                        <p class="field-text">*띄어쓰기 없이 정확하게 입력해 주세요.</p>
                        <div class="input-wrap">
                            <input type="text" id="name" pattern="[a-zA-Z가-힣]+" name="name" oninput="onInput(this, 'name'); validateInput(this);" minlength="2"
                                   onchange="onInput(this, 'name')" class="text-input" placeholder="나지니" required />
                        </div>
                    </div>
                </div>
                <div class="field-wrap">
                    <p class="field-title">2. 지원자 연락처 <span>(필수)</span><b class="star">*</b></p>
                    <div class="field-input-text-wrap">
                        <p class="field-text">*숫자만 입력해 주세요.<br>
                            *연락처를 잘못 입력하는 사례가 많이 발생하고 있습니다. 연락처가 정확한지 다시 한 번 확인해 주세요!</p>
                        <div class="input-wrap">
                            <input type="number" name="phone" maxlength="11" oninput="onInput(this, 'phone'); this.value=this.value.replace(/[^0-9]/g,''); maxLengthCheck(this);" pattern=".{11,11}"
                                   onchange="onInput(this, 'phone')" class="text-input" placeholder="01012345678"
                                   required />
                        </div>
                    </div>
                </div>
                <div class="field-wrap">
                    <p class="field-title">3. 출생년도 <span>(필수)</span><b class="star">*</b></p>
                    <div class="field-input-text-wrap">
                        <p class="field-text">*숫자만 입력해 주세요.</p>
                        <div class="input-wrap">
                            <input type="number" name="birth_date" maxlength="4" oninput="onInput(this, 'birth'); maxLengthCheck(this); this.value=this.value.replace(/[^0-9]/g,'');" pattern=".{4,4}"
                                   onchange="onInput(this, 'birth')" class="text-input" placeholder="1990" required />
                        </div>
                    </div>
                </div>

                <div class="field-wrap">
                    <p class="field-title">4. 지원자 거주지 <span>(필수)</span><b class="star">*</b></p>
                    <div class="field-input-text-wrap">
                        <p class="field-text">*지역을 알려주시면 가까운 근무지를 안내해 드립니다.</p>
                        <div class="address-btn" onclick="findAddr()">주소 찾기</div>
                        <div class="input-wrap">
                            <div class="address-input">
                                <input type="text" id="address1" name="address1" oninput="onInput(this, 'address')"
                                       onchange="onInput(this, 'address')" class="text-input"
                                       placeholder="지번 또는 도로명 주소" required />
                                <img src="img/apply/confirm.png" class="confirm" />
                            </div>
                        </div>

                    </div>
                </div>

                <div class="field-wrap">
                    <input type="hidden" class="locationYn" name="locationYn" value="">
                    <p class="field-title">5. 희망 근무지<span>(필수)</span><b class="star">*</b></p>
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
                    <p class="field-title">6. 희망 면접 일정 <span>(필수)</span><b class="star">*</b></p>
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
                <div class="field-wrap">
                    <p class="field-title">7. 추천인 <span class="ch">(선택)</span></p>
                    <div class="field-input-text-wrap">
                        <p class="field-text">*추천인이 있는 경우만 작성해 주세요</p>
                        <div class="input-select">
                            <div class="select-wrap" onclick="onSelect(event)">
                                <span id="reco-text">(선택)</span>
                                <input type="hidden" readonly name="recommender" class="recommender-data"
                                       value="(선택)" />
                                <img src="img/apply/arr.png" class="arr" />
                            </div>
                            <div class="select-wrap select-list-wrap">
                                <input class="list-item" type="text" readonly name="recom-item" value="i.M 지니(드라이버)"
                                       onclick="clickOption(this)" />
                                <input class="list-item" type="text" readonly name="recom-item" value="운수사 내근직원"
                                       onclick="clickOption(this)" />
                                <input class="list-item" type="text" readonly name="recom-item" value="hy 프레시 매니저"
                                       onclick="clickOption(this)" />
                                <input class="list-item" type="text" readonly name="recom-item" value="서치펌"
                                       onclick="clickOption(this)" />
                            </div>
                        </div>
                        <div class="input-wrap">
                            <input type="text" name="recommender_name" class="text-input" placeholder="성명 또는 상호명" minlength="2" readonly />
                        </div>
                    </div>
                </div>
            </article>
            <div class="line"></div>
            <article class="apply-page notice max">
                <p class="terms-title">
                    인재풀 등록 및 채용 안내 받기
                </p>

                <div class="terms-box">
                    i.M 인재풀에 등록하는 경우 상시 채용 대상자로 분류되어 향후 발생하는 다양한 채용 정보를 문자 및 전화로 안내드립니다.<br />
                    i.M은 상시 채용 진행을 위해 앞서 입력하신 개인정보를 인재풀에 등록하며, 지원일로부터 3년 보관 후 지체없이 파기합니다.<br />
                    인재풀 등록 및 상시 채용 안내를 위한 개인정보 수집 및 이용에 동의하지 않을 수 있으며, 거부시 인재풀 등록 및 상시 채용 안내를 받아보실 수 없습니다.
                </div>
                <label class="input-checkbox-wrap">
                    <input type="checkbox" name="agree" value="1" class="input-checkbox"
                           onclick="checkOnlyOne(this, 'agree')" />
                    <p>동의합니다.</p>
                </label>
                <label class="input-checkbox-wrap">
                    <input type="checkbox" name="agree" value="0" class="input-checkbox"
                           onclick="checkOnlyOne(this, 'agree')" />
                    <p>동의하지 않습니다.</p>
                </label>
                <input type="hidden" id="g-recaptcha" name="g-recaptcha">
                <!--                    <input class="submit-btn" type="submit" value="제출하기" class="g-recaptcha" data-sitekey="6LeIUPcmAAAAAKknvdvB6rUxzAeGwrQrm3tGMnrV" data-callback='frmSubmit' data-action='submit'  />-->
                <input class="submit-btn" type="submit" value="제출하기" />
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
    function validateInput(inputElement) {
        const inputValue = inputElement.value;
        const pattern = /^[a-zA-Z가-힣]*$/;

        if (!pattern.test(inputValue)) {
            inputElement.value = inputValue.replace(/[^a-zA-Z가-힣]/g, '');
        }
    }
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
        if(!$("input[name=name]").val()) {
            alert("이름을 입력해주세요");
            return false;
        }
        if(!$("input[name=locationYn]").val()) {
            alert("희망 근무지를 체크해주세요");
            return false;
        }
        if(!$("input[name=phone]").val()){
            alert("상담하시려는 분의 연락처를 입력해주세요");
            return false;
        }
        if($("input[name=birth_date]").val().length < 4 ){
            alert("출생년도 4자리를 정확히 입력해주세요");
            $("input[name=birth_date]").focus();
            return false;
        }
        if($("input[name=phone]").val().length < 11 ){
            alert("연락처 번호 11자리를 정확히 입력해주세요")
            $("input[name=phone]").focus();
            return false;
        }
        if($("input[name=recommender]").val() != "(선택)"){
            if(!$("input[name=recommender_name]").val()){
                alert("추천인의 성명 또는 상호명을 입력해주세요");
                return false;
            }
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
