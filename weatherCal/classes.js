const DAYS = ['SUNDAY', 'MONDAY', 'TUESDAY', "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY"];

class Day {
    constructor(dow, date, month, year) {
        this.day = document.createElement("div");
        this.day.classList.add("day");

        if (dow == 0 || dow == 6)
            this.day.classList.add("weekend")

        var dayOfWeek = document.createElement("h3");
        dayOfWeek.textContent = DAYS[dow];
        this.day.appendChild(dayOfWeek);
        this.day.appendChild(document.createElement("p"));//day number
        this.day.lastChild.textContent = date;
        this.day.appendChild(document.createElement("div"));//weather forcast
        this.day.lastChild.textContent = "Weather: N/A";
        this.day.lastChild.classList.add("weather");
        this.day.id = year + '-' + (month + 1) + '-' + (date);

    }
}
