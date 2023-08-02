window.onload = function () {
    buildCalendar();
}    // 웹 페이지가 로드되면 buildCalendar 실행

let nowMonth = new Date();  // 현재 달을 페이지를 로드한 날의 달로 초기화
let today = new Date();     // 페이지를 로드한 날짜를 저장
today.setHours(0, 0, 0, 0);    // 비교 편의를 위해 today의 시간을 초기화
var start = new Date(today.setDate(today.getDate() + 2));	// 이틀 후
var end = new Date(today.setDate(today.getDate() + 12));	// 2주 후


// 달력 생성 : 해당 달에 맞춰 테이블을 만들고, 날짜를 채워 넣는다.
function buildCalendar() {

    let firstDate = new Date(nowMonth.getFullYear(), nowMonth.getMonth(), 1);     // 이번달 1일
    let lastDate = new Date(nowMonth.getFullYear(), nowMonth.getMonth() + 1, 0);  // 이번달 마지막날

    let tbody_Calendar = document.querySelector(".Calendar > tbody");
    document.getElementById("calYear").innerText = nowMonth.getFullYear();             // 연도 숫자 갱신
    document.getElementById("calMonth").innerText = leftPad(nowMonth.getMonth() + 1);  // 월 숫자 갱신

    while (tbody_Calendar.rows.length > 0) {                        // 이전 출력결과가 남아있는 경우 초기화
        tbody_Calendar.deleteRow(tbody_Calendar.rows.length - 1);
    }

    let nowRow = tbody_Calendar.insertRow();        // 첫번째 행 추가

    for (let j = 0; j < firstDate.getDay(); j++) {  // 이번달 1일의 요일만큼
        let nowColumn = nowRow.insertCell();        // 열 추가
    }

    for (let nowDay = firstDate; nowDay <= lastDate; nowDay.setDate(nowDay.getDate() + 1)) {   // day는 날짜를 저장하는 변수, 이번달 마지막날까지 증가시키며 반복

        let nowColumn = nowRow.insertCell();        // 새 열을 추가하고


        let newDIV = document.createElement("p");
        newDIV.innerHTML = leftPad(nowDay.getDate());        // 추가한 열에 날짜 입력
        nowColumn.appendChild(newDIV);

        if (nowDay.getDay() == 6) {                 // 토요일인 경우
            nowRow = tbody_Calendar.insertRow();    // 새로운 행 추가
        }

//  if (nowDay.getFullYear() == today.getFullYear() && nowDay.getMonth() == today.getMonth() && nowDay.getDate() == today.getDate()) { // 오늘인 경우
//     newDIV.className = "today";
//     newDIV.onclick = function () { choiceDate(this); }
// }
    // else {                                      // 미래인 경우
//     newDIV.className = "futureDay";
//     newDIV.onclick = function () { choiceDate(this); }
// }
        if (start <= nowDay && nowDay <= end && nowDay.getDay() != 5 && nowDay.getDay() != 6 && nowDay.getDay() != 0){
            newDIV.className = "futureDay";

            var value = "<span class='soundOnly'>"+nowDay+"</span>"
            var location = $("input[name=location]").val();

            newDIV.innerHTML += value;
            newDIV.onclick = function () {
               choiceDate(this);

            }

        } else {                       // 지난날인 경우
            newDIV.className = "pastDay";
        }

    }
}

//같은 날짜 비교
const isSameDay = (target1, target2) => {
    return target1.getFullYear() === target2.getFullYear() &&
        target1.getMonth() === target2.getMonth() &&
        target1.getDate() === target2.getDate();
}

// 날짜 선택
function choiceDate(newDIV) {

    if(!$("input[name=locationYn]").val()) {
        alert("희망 근무지를 선택해주세요");
        return false;
    }
    else {
        if (document.getElementsByClassName("choiceDay")[0]) {                              // 기존에 선택한 날짜가 있으면
            document.getElementsByClassName("choiceDay")[0].classList.remove("choiceDay");  // 해당 날짜의 "choiceDay" class 제거
        }
        var dateVal = newDIV.querySelector('.soundOnly').textContent;

        newDIV.classList.add("choiceDay");
        let chkDate = new Date(dateVal);
        // 선택된 날짜에 "choiceDay" class 추가
        $("input[name=apply_date]").val(dateFormat(chkDate));
        var locate = $("input[name=locationYn]").val();


        $.ajax({
            type: 'post',
            dataType: 'html',
            url: '/geni/interview_ajax/time_ajax.php',
            data: {date: dateFormat(chkDate), locate: locate},
            success: function (html) {
                $(".time-list").html(html)
            }
        });
    }
}

// 이전달 버튼 클릭
function prevCalendar() {
    nowMonth = new Date(nowMonth.getFullYear(), nowMonth.getMonth() - 1, nowMonth.getDate());   // 현재 달을 1 감소
    buildCalendar();    // 달력 다시 생성
}

// 다음달 버튼 클릭
function nextCalendar() {
    nowMonth = new Date(nowMonth.getFullYear(), nowMonth.getMonth() + 1, nowMonth.getDate());   // 현재 달을 1 증가
    buildCalendar();    // 달력 다시 생성
}

//날짜 포멧 변경
function dateFormat(date) {
    let month = date.getMonth() + 1;
    let day = date.getDate();
    let hour = date.getHours();
    let minute = date.getMinutes();
    let second = date.getSeconds();

    month = month >= 10 ? month : '0' + month;
    day = day >= 10 ? day : '0' + day;
    hour = hour >= 10 ? hour : '0' + hour;
    minute = minute >= 10 ? minute : '0' + minute;
    second = second >= 10 ? second : '0' + second;

    return date.getFullYear() + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
}

// input값이 한자리 숫자인 경우 앞에 '0' 붙혀주는 함수
function leftPad(value) {
    if (value < 10) {
        value = "0" + value;
        return value;
    }
    return value;
}
