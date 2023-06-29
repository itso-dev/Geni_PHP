<?php
include_once('head.php');

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
?>

<link rel="stylesheet" type="text/css" href="css/apply.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="https://www.google.com/recaptcha/enterprise.js?render=6Lf0d90mAAAAALdfOB6-x61SMRrm8AlEvzUwMtK-"></script>

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
            <form name="contact_form" id="contact_form" method="post" action="apply_write.php">
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
                                <input type="text" name="name" oninput="onInput(this, 'name')"
                                    onchange="onInput(this, 'name')" class="text-input" placeholder="나지니" required />
                            </div>
                        </div>
                    </div>
                    <div class="field-wrap">
                        <p class="field-title">2. 지원자 연락처 <span>(필수)</span><b class="star">*</b></p>
                        <div class="field-input-text-wrap">
                            <p class="field-text">*숫자만 입력해 주세요.</p>
                            <div class="input-wrap">
                                <input type="number" name="phone" oninput="onInput(this, 'phone')"
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
                                <input type="number" name="birth_date" oninput="onInput(this, 'birth')"
                                    onchange="onInput(this, 'birth')" class="text-input" placeholder="1990" />
                            </div>
                        </div>
                    </div>

                    <div class="field-wrap">
                        <p class="field-title">4. 지원자 거주지 <span>(필수)</span><b class="star">*</b></p>
                        <p class="field-text"></p>
                        <div class="field-input-text-wrap">
                            <div class="input-wrap">
                                <div class="address-input">
                                    <input type="text" id="address1" name="address1" oninput="onInput(this, 'address')"
                                        onchange="onInput(this, 'address')" class="text-input"
                                        placeholder="지번 또는 도로명 주소" />
                                    <img src="img/apply/confirm.png" class="confirm" />
                                </div>
                                <div class="address-btn" onclick="findAddr()">주소 찾기</div>
                            </div>
                            <div class="input-wrap detail-wrap">
                                <input type="text" id="address2" name="address2" oninput="onInput(this, 'address2')"
                                    onchange="onInput(this, 'address2')" class="text-input" placeholder="상세 주소" />
                            </div>
                        </div>
                    </div>

                    <div class="field-wrap">
                        <p class="field-title">5. 희망 근무지<span>(필수)</span><b class="star">*</b></p>
                        <div class="field-input-text-wrap">
                            <p class="field-text">*희망 근무지 한 곳을 선택해 주세요.</p>
                            <div class="county-wrap">
                                <div class="county">동부</div>
                                <div class="area-wrap">
                                    <label class="area">
                                        <p>강동</p>
                                        <input type="checkbox" name="location" value="강동"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                    <label class="area">
                                        <p>송파</p>
                                        <input type="checkbox" name="location" value="송파"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                </div>
                            </div>
                            <div class="county-wrap">
                                <div class="county">서부</div>
                                <div class="area-wrap">
                                    <label class="area">
                                        <p>강서</p>
                                        <input type="checkbox" name="location" value="강서"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                    <label class="area">
                                        <p>마포</p>
                                        <input type="checkbox" name="location" value="마포"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                    <label class="area">
                                        <p>은평</p>
                                        <input type="checkbox" name="location" value="은평"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                    <label class="area">
                                        <p>충정로</p>
                                        <input type="checkbox" name="location" value="충정로"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                </div>
                            </div>
                            <div class="county-wrap">
                                <div class="county">중부</div>
                                <div class="area-wrap">
                                    <label class="area">
                                        <p>성동</p>
                                        <input type="checkbox" name="location" value="성동"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                </div>
                            </div>
                            <div class="county-wrap">
                                <div class="county">남부</div>
                                <div class="area-wrap">
                                    <label class="area">
                                        <p>구로</p>
                                        <input type="checkbox" name="location" value="구로"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                    <label class="area">
                                        <p>동작</p>
                                        <input type="checkbox" name="location" value="동작"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                    <label class="area">
                                        <p>영등포</p>
                                        <input type="checkbox" name="location" value="영등포"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                </div>
                            </div>
                            <div class="county-wrap">
                                <div class="county">북부</div>
                                <div class="area-wrap">
                                    <label class="area">
                                        <p>도봉</p>
                                        <input type="checkbox" name="location" value="도봉"
                                            onclick="checkOnlyOne(this, 'location')" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field-wrap">
                        <p class="field-title">6. 추천인 성명 <span class="ch">(선택)</span><b class="star">*</b></p>
                        <div class="field-input-text-wrap">
                            <p class="field-text">*예시 : 나지니</p>
                            <div class="input-select">
                                <div class="select-wrap" onclick="onSelect(event)">
                                    <input type="text" readonly name="recommender" class="recommender-data"
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
                                </div>
                            </div>

                            <div class="input-wrap">
                                <input type="text" name="recommender_name" class="text-input" />
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

                    <input class="submit-btn" type="submit" value="제출하기" class="g-recaptcha" data-sitekey="6Lf0d90mAAAAALdfOB6-x61SMRrm8AlEvzUwMtK-" data-callback='frmSubmit' data-action='submit'  />
                </article>
            </form>
        </section>
    </div>

    <script>
        function frmSubmit(){
            if(!$("input[name=name]").val()) {
                alert("이름을 입력해주세요");
                return false;
            }
            if(!$("input[name=phone]").val()){
                alert("상담하시려는 분의 연락처를 입력해주세요");
                return false;
            }
            document.contact_form.submit()
        }
    </script>

    <?php
    include_once('tale.php');
    ?>
