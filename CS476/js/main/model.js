//this contains the client-side model and the functions that are used by the model
//the model includes a representation of the data currently being used and the methods for making requests of the server

function error_check(json) {
    if ( typeof json.ERROR !== 'undefined')
    {
        return Promise.reject(new Error(json.ERROR));
    }
    else
        return Promise.resolve();
}

var WEATHER;


class Weather_History_Behavior {
    constructor()
    {
        console.log("history");
    }

    get() {
        return "N/A"
    }
}

class Weather_Forcast_Behavior {

    weather_obj;
    high;
    low;
    cond;
    i;


    constructor(i)
    {
        console.log("forcast");
        this.i = Math.ceil(i) + 1;

        this.set();
    }

    get() {
        if (typeof this.weather_obj === 'undefined')
        {
            return "N/A"
        }
        

        this.high = this.weather_obj.list[this.i].main.temp_max;
        this.low = this.weather_obj.list[this.i].main.temp_min;
        this.cond = this.weather_obj.list[this.i].weather[0].description;

        return this.cond + ", High: " + this.high + ", Low: " + this.low;
    }

    set() {
        this.weather_obj = WEATHER;
    }
}

class No_Weather_Behavior {
    constructor() {
        //idk
    }

    get() {
        return "N/A"
    }
}

//only one of these 
class Page {
    page_id;
    journal_id;
    entries;
    date;
    weather;

    constructor(journal_id, date = null) {
        if (date === null)
        {
            let today = new Date();
            date = today.toLocaleDateString();
        }

        this.journal_id = journal_id;
        this.date = date;
        this.entries = [];
        this.weather = [];
        this.page_id = null;

        this.#set_weather();

    }

    create_journal_entry(entry_type, form_data) {
        //add date and journal id to from_data
        form_data.append("date", this.date);
        form_data.append("journal_id", this.journal_id);
        form_data.append("add", entry_type)
        return fetch('../pages/journal-page.php', {method: 'POST', body: form_data})
            .then(response => response.json())
            .then(json => error_check(json)); 
    }

    delete_entry(form_data) {
        form_data.append("date", this.date);
        form_data.append("journal_id", this.journal_id);
        form_data.append("delete", 1);
        return fetch('../pages/journal-page.php', {method: 'POST', body: form_data})
            .then(response => response.json())
            .then(json => error_check(json)); 
    }

    edit_entry(form_data) {
        form_data.append("journal_id", this.journal_id);
        form_data.append("date", this.date);
        console.log(...form_data);
        return fetch('../pages/journal-page.php', {method: 'POST', body: form_data})
            .then(response => response.json())
            .then(json => error_check(json)); 
    }

    //returns a promes
    load_page(journal_id, date) {
        //load page from server
        if (date === null)
        {
            date = this.date;
        }

        this.journal_id = journal_id;
        this.date = date;
        this.entries = [];
        this.weather = [];
        this.page_id = null;

        this.#set_weather();

        console.log("journal-page.php?journal_id=" + journal_id + "&date=" + date);

        return fetch("journal-page.php?journal_id=" + journal_id + "&date=" + date)
            .then(response => response.json())
            .then(json => this.#set_entries(json));

    }

    #set_entries(entries) {
        console.log(entries); //for debuging !!!!!!!
        this.entries = entries;
        //return the arrray of entries
        return this.entries;
    }

    #set_weather(city, prov) {
        var today = new Date();
        var page_date = new Date(this.date);

        var date = today.getDate();
        if (date < 10)
        {
            date = "0" + date;
        }

        var month = today.getMonth() + 1;
        if (month < 10)
        {
            month = "0" + month;
        }
        var year = today.getFullYear();
        today = year + "-" + month + "-" + date;
        today = new Date(today);

        var time_diff = page_date.getTime() - today.getTime();
        var days_diff = time_diff / (1000 * 3600 * 24);  

        days_diff = Math.ceil(days_diff);

        //if date is in past user history
        if(days_diff < 0)
        {
            this.weather = new Weather_History_Behavior();
        }
        else if (days_diff < 8)//if date is in future or today use forcast
        {
            this.weather = new Weather_Forcast_Behavior(days_diff);
        }
        else 
        {
            this.weather = new No_Weather_Behavior();
        }
    }

    get_weather() {
        return this.weather.get();
    }

}

class Journal {
    journal_id;
    owner_id;
    contributors; //a list of objects that will represent the contributors
    title;
    delete_behavior;
    current_page;
    archived;

    constructor(journal_info) {
        this.journal_id = journal_info.journal_id;
        this.owner_id = journal_info.user_id;
        this.title = journal_info.title;
        this.contributors = [];
        this.current_page = new Page(this.journal_id);
        this.archived = journal_info.archived;
        this.load_contributors();
    }

    create_journal_entry(type, form_data) {
        return this.current_page.create_journal_entry(type, form_data);
    }

    delete_entry(form_data) {
        return this.current_page.delete_entry(form_data);
    }

    edit_entry(form_data) {
        return this.current_page.edit_entry(form_data);
    }

    load_page(date) {
        return this.current_page.load_page(this.journal_id, date);
    }

    load_contributors() {
        //get contributor list from server
        return fetch('../pages/main.php?ajax_request=manage&journal_id=' + this.journal_id)
            .then(response => response.json())
            .then(json => this.update_contrib(json));
    }

    update_contrib(contributors) {
        this.contributors = contributors;
        console.log(...contributors);
    }

    add_contrib(form_data) {
        var add =  form_data.get("add");
        console.log(add);
        var journal_id = this.journal_id;

        return fetch("../pages/journal-manage.php?add=" + add + "&journal_id=" + journal_id)
            .then(response => response.json())
            .then(json => error_check(json));
    }

    remove_contrib(form_data) {
        var remove = form_data.get("remove");
        var journal_id = this.journal_id;

        return fetch("../pages/journal-manage.php?remove=" + remove + "&journal_id=" + journal_id)
            .then(response => response.json())
            .then(json => error_check(json));
    }

    archive_journal(form_data) {
        var archive = form_data.get("archive");
        var journal_id = this.journal_id;

        return fetch("../pages/journal-manage.php?archive=" + archive)
            .then(response => response.json())
            .then(json => error_check(json));
    }

    delete_journal(form_data) {
        var delete_str = form_data.get("delete");
        var journal_id = this.journal_id;

        return fetch("../pages/journal-manage.php?delete=" + delete_str)
            .then(response => response.json())
            .then(json => error_check(json));
    }

}

//only one of these
class User {
    user_id;
    user_name;
    city;
    prov;
    owned_journals;
    contrabution_journals;
    current_journal;

    constructor(user_obj) {
        this.user_id = user_obj.user.user_id;
        this.user_name = user_obj.user.username;
        this.city = user_obj.user.city;
        this.prov = user_obj.user.prov;
        this.owned_journals = user_obj.owned_journals;
        this.contrabution_journals = user_obj.journal_contrabutions;

        getWeatherCity(this.city)
        .then(weather => this.load_weather(weather));

        if(typeof this.owned_journals[0] !== 'undefined')
        {
            this.current_journal = new Journal(this.owned_journals[0]);
        }
        else if (typeof this.contrabution_journals[0] !== 'undefined')
        {
            this.current_journal = new Journal(this.contrabution_journals[0]);
        }

        

        
    }

    load_weather(weather) {
        WEATHER = weather;
    }

    //returns a promise
    create_journal(journal_title) {

        if (this.owned_journals.length >= 4)
        {
            return Promise.reject(new Error("You have the maximum number of journals!!"));
        }

        return fetch("../pages/main.php?ajax_request=create_journal&title=" + journal_title)
            .then(response => response.json())
            .then(journals => this.update_owned_journals(journals));
    }

    //takes in an array of journals replaces the owned journals list
    update_owned_journals(journals) {
        console.log(journals.ERROR);
        if ( typeof journals.ERROR !== 'undefined')
        {
            return Promise.reject(new Error(journals.ERROR));
        }


        this.owned_journals = journals;
    }

    create_journal_entry(type, form_data) {
        return this.current_journal.create_journal_entry(type, form_data);
    }

    delete_journal_entry(entry_id) {

    }

    load_journal(journal_id) {
        // vlidate that the user can load the journal 
        var valid = false;
        var journal_to_load;

        for (let i = 0; i < this.owned_journals.length; i++)
        {
            
            if (journal_id == this.owned_journals[i].journal_id)
            {
                valid = true;
                journal_to_load = this.owned_journals[i];
            }
        }

        for (let i = 0; i < this.contrabution_journals.length; i++)
        {
            if (journal_id == this.contrabution_journals[i].journal_id)
            {
                valid = true;
                journal_to_load = this.contrabution_journals[i];
            }
        }

        if (valid === false)
        {
            return; //should have some error?
        }

        
        //load the journal
        this.current_journal = new Journal(journal_to_load);

    }

    delete_journal(journal_id) {

    }

    archive_journal(journal_id) {

    }

    unarchive_journal(journal_id) {

    }

    get_user_id() {
        return this.user_id;
    }

    get_user_name() {
        return this.user_name;
    }

    get_city() {
        return this.city;
    }

    get_prov() {
        return this.prov;
    }

    get_owned_journals() {
        return this.owned_journals;
    }

    get_contributed_journals() {
        return this.contrabution_journals;
    }

}

class Model {
    current_user;
    isLoaded;

    constructor(){
        //get the user information and build the model
        this.isLoaded = false;
        this.load_user_info();
    }

    async load_user_info() {
        //request a json that contains the users information and send the json to the build user function
        return fetch("main.php?ajax_request=user")
            .then(response => response.json())
            .then(json => this.build_user(json));
        
    }

    build_user(user) {
        
        this.current_user = new User(user);
        this.isLoaded = true;
        console.log(user);    //for debuging !!!!!!!!!!
    }

    
}