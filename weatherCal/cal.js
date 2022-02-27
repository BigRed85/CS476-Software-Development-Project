const TODAY = new Date();
var date = TODAY.getDate(); 
var day = TODAY.getDay();
var year = TODAY.getFullYear();
var month = TODAY.getMonth();
var WEATHER;

const MONTHS = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];



$(document).ready(function(){
    loadWeather();
    loadMonth();

    $("#backBtn").hover(function() {
        $(this).stop(true, true);
        $(this).animate({  opacity: '1.0'}, "fast");
    },
    function() {
        $(this).animate({  opacity: '0.4'});
    });

    $("#frwBtn").hover(function() {
        $(this).stop(true, true);
        $(this).animate({  opacity: '1.0'}, "fast");
    },
    function() {
        $(this).animate({  opacity: '0.4'});
    });

    $("#backBtn").click(loadPrevMonth);
    $("#frwBtn").click(loadNextMonth);

    $("#city").change(loadWeather);

});

function loadWeather(){
    getWeatherCity($("#city").val())
        .then(weather => updateWeather(weather));
    
}

function updateWeather(weather = null) {


    if (weather != null)
    {
        WEATHER = weather;
    }
    else
    {
        if (WEATHER == null)
            return;
        weather = WEATHER;
    }
    

    if (year == TODAY.getFullYear() && month == TODAY.getMonth())
    {
        var last = new Date(year, month + 1, 0);
        last = last.getDate();

        for (let i = 0; i < 8 && i < last; i++)
        {
            let high = weather.list[i].main.temp_max;
            high = "High: " + high;

            let low = weather.list[i].main.temp_min;
            low = "Low: " + low;

            let cond = weather.list[i].weather[0].description;

            let dateID = '#' + year + '-' + (month + 1) + '-' + (TODAY.getDate() + i);
            $(dateID).children(".weather").empty();

            let newEl = document.createElement("p");
            newEl.textContent = high;
            $(dateID).children(".weather").append(newEl);

            newEl = document.createElement("p");
            newEl.textContent = low;
            $(dateID).children(".weather").append(newEl);

            newEl = document.createElement("p");
            newEl.textContent = cond;
            $(dateID).children(".weather").append(newEl);

        }
    }
}

function setUpMonthEvents() {
    $(".day").hover(function() {
        $(this).stop(true, true);
        $(this).animate({ opacity: "1.0"}, "fast");
    },
    function() {
        $(this).animate({ opacity: "0.6"}, "slow");

    });

    /*$(".day").click(function() {
        $(this).animate({
            height: "+=150px",
            width: "+=150px"
        });
    });*/
}

function loadPrevMonth() {
    var newDate = new Date(year, month - 1, 1);
    date = newDate.getDate();
    day = newDate.getDay();
    year = newDate.getFullYear();
    month = newDate.getMonth();
    $("#month").empty();
    loadMonth();
    updateWeather();
}

function loadNextMonth() {
    var newDate = new Date(year, month + 1, 1);
    date = newDate.getDate();
    day = newDate.getDay();
    year = newDate.getFullYear();
    month = newDate.getMonth();
    $("#month").empty();
    loadMonth();
    updateWeather();
}

function loadMonth() {
    var first = new Date(year, month, 1);
    var firstDay = first.getDay();

    var last = new Date(year, month + 1, 0);

    $("#monthName").text(MONTHS[month]);
    $("#yearTop").text(year);

    for (var i = 0; i < firstDay; i++) //pad out the front 
    {
        var newDay = document.createElement("div");

        $("#month").append(newDay);
    }

    for (var i = 0; i < last.getDate(); i++) //add the days of the month
    {
        var dow = (i + firstDay) % 7;
        var newDay = new Day(dow, i + 1, month, year);

        $("#month").append(newDay.day);
    }

    setUpMonthEvents();
}