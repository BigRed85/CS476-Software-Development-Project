class Day {
    constructor(dow, date, month, year) {
        this.day = document.createElement("div");
        this.day.classList.add("day");

        if (dow == 0 || dow == 6)
            this.day.classList.add("weekend")

        this.day.appendChild(document.createElement("a"));//day number
        this.day.lastChild.textContent = date;
        this.day.id = year + '-' + (month + 1) + '-' + (date);

    }
}
