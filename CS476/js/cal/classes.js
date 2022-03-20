class Day {
    constructor(dow, date, month, year) {
        this.day = document.createElement("div");
        this.day.classList.add("day");

        if (dow == 0 || dow == 6)
            this.day.classList.add("weekend")

        this.day.appendChild(document.createElement("a"));//day number
        this.day.lastChild.textContent = date;

        month = month + 1;
        if (month < 10)
        {
            month = '0' + month;
        }

        if (date < 10)
        {
            date = '0' + date;
        }

        this.day.id = year + '-' + month + '-' + (date);

    }
}
