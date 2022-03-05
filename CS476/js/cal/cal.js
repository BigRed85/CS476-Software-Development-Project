const TODAY = new Date();
var date = TODAY.getDate(); 
var day = TODAY.getDay();
var year = TODAY.getFullYear();
var month = TODAY.getMonth();

const MONTHS = ['Jan.', 'Feb.', 'Mar.', 'Apr.', 'May.', 'Jun.', 'Jul.', 'Aug.', 'Sep.', 'Oct.', 'Nov.', 'Dec.'];

window.addEventListener("load", init);
function init(){
    loadMonth();

    document.getElementById("cal_month_back").onclick = loadPrevMonth;
    document.getElementById("cal_month_forward").onclick = loadNextMonth;

}

function setUpMonthEvents() {
    //set up click events
}

function loadPrevMonth(event) {
    event.currentTarget.blur();
    var newDate = new Date(year, month - 1, 1);
    date = newDate.getDate();
    day = newDate.getDay();
    year = newDate.getFullYear();
    month = newDate.getMonth();
    document.getElementById("cal_month").innerHTML = "";
    loadMonth();
}

function loadNextMonth(event) {
    event.currentTarget.blur();
    var newDate = new Date(year, month + 1, 1);
    date = newDate.getDate();
    day = newDate.getDay();
    year = newDate.getFullYear();
    month = newDate.getMonth();
    document.getElementById("cal_month").innerHTML = "";
    loadMonth();
}

function loadMonth() {
    var first = new Date(year, month, 1);
    var firstDay = first.getDay();

    var last = new Date(year, month + 1, 0);

    document.getElementById("cal_month_name").innerText = MONTHS[month];
    document.getElementById("cal_year").innerText = year;

    for (var i = 0; i < firstDay; i++) //pad out the front 
    {
        var newDay = document.createElement("div");

        document.getElementById("cal_month").appendChild(newDay);
    }

    for (var i = 0; i < last.getDate(); i++) //add the days of the month
    {
        var dow = (i + firstDay) % 7;
        var newDay = new Day(dow, i + 1, month, year);

        document.getElementById("cal_month").appendChild(newDay.day);
    }

    setUpMonthEvents();
}