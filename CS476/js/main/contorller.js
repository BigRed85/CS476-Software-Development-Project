// this is the main controller that will deal with the interactions that all pages will have.
// this deals with all event listeners and sending request to the view and the model.


class Controller {
    model;
    view;
    content;

    constructor() {
        this.model = new Model();
        this.view = new View();

    }

    //initializes the aplication by showing todays date
    init() {
        this.load_page()
        this.update_current_journal();
    }

    update_user_info() {
        
        this.model.load_user_info()
            .then(this.update_journals_list.bind(this))
    }

    update_journals_list() {
        if (this.model.isLoaded === false)
        {
            return;
        }

        var owned = this.get_owned_journals();
        var contrib = this.get_contributed_journals();

        //update the view journal lists
        this.view.update_journals_list(owned, contrib);

        var journals = document.getElementsByClassName("journal_selector");
        
        for (let i = 0; i < journals.length; i++)
        {
            journals[i].addEventListener('click', this.load_journal.bind(this));
        }

    }

    get_owned_journals() {
        return this.model.current_user.get_owned_journals();
    }

    get_contributed_journals() {
        return this.model.current_user.get_contributed_journals();
    }
    
    init_listeners() {
      //set up the event listeners for the main elements (header, footer and sidebar)
    
        //-------------header--------------------

        //-------------footer--------------------

        
        //-----------sidebar--------------------

        //calender
        var month_forward = document.getElementById("cal_month_forward");
        month_forward.addEventListener('click', this.load_next_month.bind(this));

        var month_back = document.getElementById("cal_month_back");
        month_back.addEventListener('click', this.load_prev_month.bind(this));

        var days_of_month = document.getElementsByClassName("day");

        for (let i = 0; i < days_of_month.length; i++) 
        {
            days_of_month[i].addEventListener('click', this.load_page.bind(this));
        }

        //update select journal button
        var select_journal = document.getElementById("select_joural_button");
        select_journal.addEventListener('click', this.update_journals_list.bind(this));

        //create journal and upsdate user info on journal creation
        var new_journal_form = document.getElementById("new_journal_form");
        new_journal_form.addEventListener('submit', this.create_journal.bind(this));
        
        //load a journal
        var journals = document.getElementsByClassName("journal_selector");
        for (let i = 0; i < journals.length; i++)
        {
            journals[i].addEventListener('click', this.load_journal.bind(this));
        }

        //load to do list
        var to_do_button = document.getElementById("To_Do_button");
        to_do_button.addEventListener('click', this.load_to_do_list.bind(this));

        //load photo galary
        var photo_button = document.getElementById("Photo_button");
        photo_button.addEventListener('click', this.load_photo_gallery.bind(this));

        //load journal managment
        var manage_button = document.getElementById("Manage_button");
        manage_button.addEventListener('click', this.load_journal_managment.bind(this));


        //------------- content ----------------------

        //-------------- Journal page -----------------

        
    }

    load_next_month(event) {
        var days_of_month = document.getElementsByClassName("day");
        loadNextMonth(event);
            
        for (let i = 0; i < days_of_month.length; i++) 
        {
            days_of_month[i].addEventListener('click', this.load_page.bind(this));
                
        }
    }

    load_prev_month(event) {
        var days_of_month = document.getElementsByClassName("day");
        loadPrevMonth(event);
            
        for (let i = 0; i < days_of_month.length; i++) 
        {
            days_of_month[i].addEventListener('click', this.load_page.bind(this));
                
        }
    }

    load_journal(event) {
        //load a new journal with the current selected date
        var journal_id = event.currentTarget.value;

        this.model.current_user.load_journal(journal_id);
        this.update_current_journal();
        //load the page todays date and display it
        this.load_page();
        
    }

    update_current_journal() {
        if (typeof this.model.current_user === 'undefined')
        {
            //wait for user to load then try again;
            setTimeout(this.update_current_journal.bind(this), 100);
        }
        else
        {
            //cange the displayed title
            this.view.update_journal_title(this.model.current_user.current_journal.title);
            
            //change if the manage journal button is visible;
            var user_id = this.model.current_user.user_id;
            var owner_id = this.model.current_user.current_journal.owner_id;

            if (owner_id === user_id)
            {
                //make visible
                this.view.hide_manage(false);
            }
            else
            {
                //make invisible
                this.view.hide_manage(true);
            }
        }

        //
        
    }

    

    create_journal(event) {
        //create a new journal
        event.preventDefault();
        var title = event.currentTarget[0].value;

        this.model.current_user.create_journal(title)
            .then(this.create_journal_success, error => this.create_journal_fail(error));

    }

    create_journal_success() {
        var new_title = document.getElementById("new_title");
        new_title.value = "";

        var alert = document.getElementById("new_journal_success");
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_journal_success");
            alert.classList.add("hidden");
        } , 2000);


    }

    create_journal_fail(error) {
        var alert = document.getElementById("new_journal_fail");
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_journal_fail");
            alert.classList.add("hidden");
            alert.innerText = error;
        } , 2000);
    }


    //------------------------------------------journal page -----------------------------------------------
    // the following are the functions that deal with te interactions pertaining to a journal page

    //get and display the journal page
    load_page(event = null) {

                
        var date;
        //if event == null load todays date.
        if (event === null)
        {
            let today = new Date();
            let year = today.getFullYear();
            let month = today.getMonth() + 1;
            if (month < 10)
            {
                month = "0" + month;
            }

            let day = today.getDate();
            if (day < 10)
            {
                day = "0" + day;
            }
            date = year + "-" + month + "-" + day;
        }
        else
        {
            date = event.currentTarget.id;
            let days_of_month = event.currentTarget.parentNode.children;
            for (let i = 0; i < days_of_month.length; i++)
            {
                days_of_month[i].classList.remove("selected_day")
            }
            event.currentTarget.classList.add("selected_day");
        }

        //show the journal page
        this.view.show_journal_page(date);

        //load a new page with the selected date
        //display that page
        //set up the event listeners for the content
        if (typeof this.model.current_user === 'undefined')
        {
            //wait for user to load then contrinue;
            setTimeout(this.load_page.bind(this), 100);
        }
        else
        {
            var user_id = this.model.current_user.get_user_id();
            
            //update the view with the current owner
            if (this.model.current_user.current_journal.owner_id == user_id)
            {
                this.view.set_journal_owner(true);
            }
            else
            {
                this.view.set_journal_owner(false);
            }

            //get weather for current page 
            //var weather = this.model.current_user.current_journal.current_page.get_weather();
            //this.view.set_weather(weather);

            this.model.current_user.current_journal.load_page(date)
                .then(entries => this.view.display_journal_entries(entries, user_id))
                .then(this.init_journal_page.bind(this)) //set up the event listeners for the new page
                .then(this.update_weather.bind(this));
        }
        
    }

    update_weather() {
        
        var weather = this.model.current_user.current_journal.current_page.get_weather();
        this.view.set_weather(weather);
    }

    //will update the current viewd page
    update_page() {
        var date = this.model.current_user.current_journal.current_page.date;

        //show the journal page
        this.view.show_journal_page(date);

        //load a new page with the selected date
        //display that page
        //set up the event listeners for the content
        if (typeof this.model.current_user === 'undefined')
        {
            //wait for user to load then contrinue;
            setTimeout(this.load_page.bind(this), 100);
        }
        else
        {
            var user_id = this.model.current_user.get_user_id();
            
            //update the view with the current owner
            if (this.model.current_user.current_journal.owner_id == user_id)
            {
                this.view.set_journal_owner(true);
            }

            this.model.current_user.current_journal.load_page(date)
                .then(entries => this.view.display_journal_entries(entries, user_id))
                .then(this.init_journal_page.bind(this)); //set up the event listeners for the new page
        }
    }

    init_journal_page() {
        //set up event liteners for a journal page

        
        //add event
        var add_event = document.getElementById("add_event_form");
        var new_add_event = add_event.cloneNode(true);
        add_event.parentNode.replaceChild(new_add_event, add_event); //makes sure that there are no duplicate event listeners
        new_add_event.addEventListener('submit', this.add_event.bind(this));

        //add note
        var add_note = document.getElementById("add_note_form");
        var new_add_note = add_note.cloneNode(true);
        add_note.parentNode.replaceChild(new_add_note, add_note); //makes sure that there are no duplicate event listeners
        new_add_note.addEventListener('submit', this.add_note.bind(this));

        //add photo
        var add_photo = document.getElementById("add_photo_form");
        var new_add_photo = add_photo.cloneNode(true);
        add_photo.parentNode.replaceChild(new_add_photo, add_photo); //makes sure that there are no duplicate event listeners
        new_add_photo.addEventListener('submit', this.add_photo.bind(this));

        //edit entry
        var edit_event_button = document.getElementsByClassName("edit_event_button");
        for (let i = 0; i < edit_event_button.length; i++)
        {
            edit_event_button[i].addEventListener('click', this.edit_event_set_up.bind(this));
        }

        var edit_event_form = document.getElementById("edit_event_form");
        var edit_event_form_2 = edit_event_form.cloneNode(true);
        edit_event_form.parentNode.replaceChild(edit_event_form_2, edit_event_form);
        edit_event_form_2.addEventListener('submit', this.edit_event.bind(this));

        var edit_note_button = document.getElementsByClassName("edit_note_button");
        for (let i = 0; i < edit_note_button.length; i++)
        {
            edit_note_button[i].addEventListener('click', this.edit_note_set_up.bind(this));
        }

        var edit_note_form = document.getElementById("edit_note_form");
        var edit_note_form_2 = edit_note_form.cloneNode(true);
        edit_note_form.parentNode.replaceChild(edit_note_form_2, edit_note_form);
        edit_note_form_2.addEventListener('submit', this.edit_note.bind(this));

        //delete entry
        var delete_buttons = document.getElementsByClassName("entry_delete_button");
        for (let i = 0; i < delete_buttons.length; i++)
        {
            delete_buttons[i].addEventListener('click', this.delete_set_up.bind(this));
        }

        var delete_form = document.getElementById("delete_form");
        var new_delete_form = delete_form.cloneNode(true);
        delete_form.parentNode.replaceChild(new_delete_form, delete_form);
        new_delete_form.addEventListener('submit', this.delete_entry.bind(this));

    }

    //--------------add entry ----------------

    //deals with the submit on the form to add a new event
    add_event(event) {
        event.preventDefault();

        //get from data
        var form_data = new FormData(document.getElementById('add_event_form'));
        console.log(...form_data);
        //call the method from module to create new entry
        this.model.current_user.create_journal_entry('event',form_data)
            .then(this.add_event_success, error => this.add_event_fail(error))
            .then(this.update_page.bind(this));
    }

    add_event_success() {
        var event_note = document.getElementById("event_note");
        event_note.value = "";

        var alert = document.getElementById("new_event_success");
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_event_success");
            alert.classList.add("hidden");
        } , 2000);
    }

    add_event_fail(error) {
        var alert = document.getElementById("new_event_fail");
        alert.innerText = error;
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_event_fail");
            alert.classList.add("hidden");
            alert.innerText = "";
        } , 2000);
    }

    //deals with the submit on the form to add a new event
    add_note(event) {
        event.preventDefault();

        //get from data
        var form_data = new FormData(document.getElementById("add_note_form"));
        console.log(...form_data);
        //call the method from module to create new entry
        this.model.current_user.create_journal_entry('note',form_data)
            .then(this.add_note_success, error => this.add_note_fail(error))
            .then(this.update_page.bind(this));
    }

    add_note_success() {
        var note_text = document.getElementById("note_text");
        note_text.value = "";

        var alert = document.getElementById("new_note_success");
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_note_success");
            alert.classList.add("hidden");
        } , 2000);
    }

    add_note_fail(error) {
        var alert = document.getElementById("new_note_fail");
        alert.innerText = error;
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_note_fail");
            alert.classList.add("hidden");
            alert.innerText = "";
        } , 2000);
    }

    //deals with the submit on the form to add a new event
    add_photo(event) {
        event.preventDefault();

        var form = document.getElementById("add_photo_form");
        //validate the image
        var isValid = true;
        var error_msg = "";

        if (form.children[1].value == "" ) {
            error_msg = "Avatar cannot be empty!";
            isValid = false;
        }
        else
        {
            var fileName = form.children[1].value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile != "jpg" && extFile != "jpeg" && extFile != "png") {
                error_msg = "Only jpg/jpeg and png files are allowed!";
                form.children[1].value = "";  // Reset the input so no files are uploaded
                isValid = false;
            }
        }
        

        //if not valid show error
        if (!isValid)
        {
            this.add_photo_fail(error_msg);
            return;
        }

        //get from data
        var form_data = new FormData(form);
    
        //call the method from module to create new entry
        this.model.current_user.create_journal_entry('photo',form_data)
            .then(this.add_photo_success, error => this.add_photo_fail(error))
            .then(this.update_page.bind(this));
    }

    add_photo_success() {
        var note_text = document.getElementById("photo_file");
        note_text.value = "";

        var alert = document.getElementById("new_photo_success");
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_photo_success");
            alert.classList.add("hidden");
        } , 2000);
        
    }

    add_photo_fail(error) {
        var alert = document.getElementById("new_photo_fail");
        alert.innerText = error;
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_photo_fail");
            alert.classList.add("hidden");
            alert.innerText = "";
        } , 2000);
    }

    //---------delete entry------------
    delete_set_up(event) {
        var delete_form = document.getElementById("delete_form");
        var elements = delete_form.children;

        var entry_id = event.currentTarget.parentNode.getAttribute("entry_id");

        elements[0].value = entry_id;
    }

    delete_entry(event) {
        event.preventDefault();
        var form_data = new FormData(document.getElementById("delete_form"));

        this.model.current_user.current_journal.delete_entry(form_data)
            .then(this.delete_entry_success, error => this.delete_entry_fail(error))
            .then(this.update_page.bind(this));
    }

    delete_entry_success() {
        var alert = document.getElementById("delete_success");
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("delete_success");
            alert.classList.add("hidden");
        } , 2000);
    }

    delete_entry_fail(error) {
        var alert = document.getElementById("delete_fail");
        alert.innerText = error;
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("delete_fail");
            alert.classList.add("hidden");
            alert.innerText = "";
        } , 2000);
    }

    //----------------- edit entry -----------------

    edit_event_set_up(event) {
        var entry = event.currentTarget.parentNode;
        var entry_id = entry.getAttribute("entry_id");
        var type = entry.getAttribute("type");
        var note = entry.getAttribute("note");

        document.getElementById("edit_event_id").value = entry_id;
        
        var form_type = document.getElementById("edit_event_type");
        form_type.value = type;
        var form_note = document.getElementById("edit_event_note");
        form_note.value = note;
    }

    edit_event(event) {
        event.preventDefault();

        var form_data = new FormData(document.getElementById("edit_event_form"));

        this.model.current_user.current_journal.edit_entry(form_data)
            .then(this.edit_event_success, error => this.edit_event_fail(error))
            .then(this.update_page.bind(this));
    }

    edit_event_success() {
        var alert = document.getElementById("edit_event_success");
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("edit_event_success");
            alert.classList.add("hidden");
        } , 2000);
    }

    edit_event_fail(error) {
        var alert = document.getElementById("edit_event_fail");
        alert.innerText = error;
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("edit_event_fail");
            alert.classList.add("hidden");
            alert.innerText = "";
        } , 2000);
    }

    edit_note_set_up(event) {
        var entry = event.currentTarget.parentNode;
        var entry_id = entry.getAttribute("entry_id");
        var note = entry.children[1].innerText;

        document.getElementById("edit_note_id").value = entry_id;
        
        var form_note = document.getElementById("edit_note_text");
        form_note.innerText = note;
    }

    edit_note(event) {
        event.preventDefault();

        var form_data = new FormData(document.getElementById("edit_note_form"));

        this.model.current_user.current_journal.edit_entry(form_data)
            .then(this.edit_note_success, error => this.edit_note_fail(error))
            .then(this.update_page.bind(this));
    }

    edit_note_sucess() {
        var alert = document.getElementById("new_note_success");
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_note_success");
            alert.classList.add("hidden");
        } , 2000);
    }

    edit_note_fail(error) {
        var alert = document.getElementById("new_note_fail");
        alert.innerText = error;
        alert.classList.remove("hidden");

        setTimeout(function() {
            var alert = document.getElementById("new_note_fail");
            alert.classList.add("hidden");
            alert.innerText = "";
        } , 2000);
    }

    //----------------------------------------- photo gallery ------------------------------------------------
    // the following are the interactions that deal with the photo gallery

    load_photo_gallery() {
        //load a photo gallary of the current selected mounth
        this.view.show_photo_gallery();
    }

    

    //----------------------------------------- to-do list --------------------------------------------------
    // the following are functions that deal with the to-do list

    load_to_do_list() {
        this.model.load_to_do_list;

        //load the to-do list page
        this.view.show_to_do();


    }

    //---------------------------------------- journal managment ---------------------------------------------
    // the following are functions that deal with the journal management

    load_journal_managment() {
        //load the journal managment page
        this.view.show_journal_managment();

        //load journal managmant page with a list of contributors
        var contrib_list = this.model.current_user.current_journal.contributors;
        this.view.update_remove_contrib(contrib_list);
        //set up event listeners 

        var add_contributor_form = document.getElementById("add_contributor_form");
        var new_add_contributor_form = add_contributor_form.cloneNode(true);
        add_contributor_form.parentNode.replaceChild(new_add_contributor_form, add_contributor_form);
        new_add_contributor_form.addEventListener('submit', this.add_contrib.bind(this));


        var remove_contributor_form = document.getElementById("remove_contributor_form");
        var new_remove_contributor_form = remove_contributor_form.cloneNode(true);
        remove_contributor_form.parentNode.replaceChild(new_remove_contributor_form, remove_contributor_form);
        new_remove_contributor_form.addEventListener('submit', this.remove_contrib.bind(this));

        var arcive_journal_from = document.getElementById("arcive_journal_from");
        var new_arcive_journal_from = arcive_journal_from.cloneNode(true);
        arcive_journal_from.parentNode.replaceChild(new_arcive_journal_from, arcive_journal_from);
        new_arcive_journal_from.addEventListener('submit', this.archive_journal.bind(this));

        var delete_journal_form = document.getElementById("delete_journal_form");
        var new_delete_journal_form = delete_journal_form.cloneNode(true);
        delete_journal_form.parentNode.replaceChild(new_delete_journal_form, delete_journal_form);
        new_delete_journal_form.addEventListener('submit', this.delete_journal.bind(this));
    }

    add_contrib(event) {
        event.preventDefault();

        var form_data = new FormData(document.getElementById("add_contributor_form"));

        this.model.current_user.current_journal.add_contrib(form_data)
            .then(this.add_contrib_success.bind(this), error => this.add_contrib_fail(error));
    }

    add_contrib_success() {
        this.model.current_user.current_journal.load_contributors()
            .then(this.load_journal_managment.bind(this));
            
        var form = document.getElementById("add_contributor_form");
        form.children[1].value = "";

        alert("user addad successful!")
    }

    add_contrib_fail(error) {
        alert(error); //fix this
    }

    remove_contrib(event) {
        event.preventDefault();

        var form_data = new FormData(document.getElementById("remove_contributor_form"));

        this.model.current_user.current_journal.remove_contrib(form_data)
            .then(this.remove_contrib_success.bind(this), error => this.remove_contrib_fail(error));

    }

    remove_contrib_success() {
        this.model.current_user.current_journal.load_contributors()
            .then(this.load_journal_managment.bind(this));

            alert("User removed!");
    }

    remove_contrb_fail(error) {
        alert(error);
    }
 
    archive_journal(event) {
        event.preventDefault();
    }

    archive_journal_success() {
        this.load_journal_managment();
    }

    archive_journal_fail(error) {
        alert(error);
    }

    delete_journal(event) {
        event.preventDefault();
    }

}