//this contains the view and the classes that are used by the view 
//the view contains methods for manimpulating the presentation of the application

class RemoveUserListItem {

    option

    constructor(user_id, user_name) {
        this.option = document.createElement("option")
        this.option.value = user_id;
        this.option.innerText = user_name;
    }
}

class JournalEntryView{
    constructor(entry, user_id, is_journal_owner) {
        
        this.entry_factory(entry, user_id, is_journal_owner);
    }

    entry_factory(entry, user_id, is_journal_owner) {

        //build the basic structure
        this.div = document.createElement("div");
        this.div.classList.add("journal_entry");9
        this.div.setAttribute("entry_id", entry.entry_id);
        this.div.setAttribute("entry_type", entry.entry_type);

        switch (entry.entry_type) {
            case 'event':
                this.event_factory(entry, user_id, is_journal_owner);
                break;
            case 'note':
                this.note_factory(entry, user_id, is_journal_owner);
                break;

            case 'photo':
                this.photo_factory(entry, user_id, is_journal_owner);
                break;
            default:
                break;
        }

    }

    event_factory(entry, user_id, is_journal_owner) {
        this.div.classList.add('event-' + entry.event_type);
        var type = document.createElement('h5');
        type.innerText = "Event: " + entry.event_type;
        this.div.appendChild(type);
        this.div.setAttribute("type", entry.event_type);

        var note = document.createElement('p');
        note.innerText = entry.event_note;
        this.div.appendChild(note);
        this.div.setAttribute("note", entry.event_note);

        var br = document.createElement("br");
        this.div.appendChild(br);

        //determin edit permission then build edit button
        this.edit_factory(entry, user_id)

        //determin delete permissions then build delete button
        this.delete_factory(entry, is_journal_owner)

    }

    note_factory(entry, user_id, is_journal_owner) {
        this.div.classList.add('event');
        var h = document.createElement('h5');
        h.innerText = "Note:";
        this.div.appendChild(h);

        var p = document.createElement('p');
        fetch(entry.note_path)
            .then(response => response.text())
            .then(text => this.insert_text(p, text), function() {console.log("failer")})


        //var text = document.createElement('embed');
        //text.src = entry.note_path;
        //text.width = "95%";
        //p.appendChild(text);
        this.div.appendChild(p);

        var br = document.createElement("br");
        this.div.appendChild(br);

        //determin edit permission then build edit button
        this.edit_factory(entry, user_id)

        //determin delete permissions then build delete button
        this.delete_factory(entry, is_journal_owner)
    }

    insert_text(p , text) {
        p.innerText = text;
    }

    photo_factory(entry, user_id, is_journal_owner) {
        this.div.classList.add('event');
        var photo = document.createElement('img');
        photo.src = entry.photo_path;
        photo.style.maxWidth = "95%"
        this.div.appendChild(photo);

        var br = document.createElement("br");
        this.div.appendChild(br);

        //determin delete permissions then build delete button
        this.delete_factory(entry, is_journal_owner)
    }

    edit_factory(entry, user_id) {
        //to do create edit button
        if(user_id == entry.user_id)
        {
            //create edit button
            var edit_button = document.createElement("button");
            edit_button.classList.add("btn", "btn-success");
            edit_button.innerText = "Edit";
            edit_button.setAttribute("data-bs-toggle", "modal");            

            switch (entry.entry_type) {
                case "event":
                    edit_button.setAttribute("data-bs-target", "#edit_event_modal");
                    edit_button.classList.add("edit_event_button");                
                    break;
                case "note":
                    edit_button.setAttribute("data-bs-target", "#edit_note_modal");
                    edit_button.classList.add("edit_note_button");
                    break;
            }

            this.div.appendChild(edit_button);
        }
        else
        {
            //show the user who createed this
            var p = document.createElement("p");
            p.innerText = "User: " + entry.username;
            var img = document.createElement("img");
            img.src = entry.avatar;
            img.style.height = "2em"
            img.classList.add('rounded'); 
            p.appendChild(img)
            this.div.appendChild(p);
        }
    }

    delete_factory(entry, is_journal_owner) {
        //to do create delete button
        if(is_journal_owner === true)
        {
            var delete_button = document.createElement("button");
            delete_button.classList.add("btn", "btn-danger", "entry_delete_button");
            delete_button.innerText = "Delete";
            delete_button.setAttribute("data-bs-toggle", "modal");
            delete_button.setAttribute("data-bs-target", "#delete_modal");

            this.div.appendChild(delete_button);
        }
        else
        {
            //display create info
            //username and avatar
        }
    }

}

class JournalListItem {
    constructor(journal_id, journal_title) {
        this.item = document.createElement("li");
        this.item.classList.add("dropdown-item", "journal_selector");
        this.item.textContent = journal_title;
        this.item.value = journal_id;
    }
}

class View {
    is_journal_owner;

    constructor() {
        this.is_journal_owner = false;
    }

    set_journal_owner(is_journal_owner = false)
    {
        this.is_journal_owner = is_journal_owner;
    }

    show_journal_page(date) {

        //update the date
        var page_date = document.getElementById("journal-page-date");
        page_date.innerText = date;

        //switch to journal page
        var content_children = document.getElementById("content").children;
        
        for (let i = 0; i < content_children.length; i++)
        {
            content_children[i].classList.add("hidden");
        }

        var to_show = document.getElementById("content-journal-page");
        to_show.classList.remove("hidden");
    }

    set_weather(weather) {
        var journal_page_weather = document.getElementById("journal_page_weather");
        journal_page_weather.innerText = weather;
    }

    show_to_do() {
        var content_children = document.getElementById("content").children;
        
        for (let i = 0; i < content_children.length; i++)
        {
            content_children[i].classList.add("hidden");
        }

        var to_show = document.getElementById("content-to-do");
        to_show.classList.remove("hidden");

    }

    show_photo_gallery() {
        var content_children = document.getElementById("content").children;
        
        for (let i = 0; i < content_children.length; i++)
        {
            content_children[i].classList.add("hidden");
        }

        var to_show = document.getElementById("content-photo");
        to_show.classList.remove("hidden");
    }

    show_journal_managment(archived) {
        var content_children = document.getElementById("content").children;
        
        for (let i = 0; i < content_children.length; i++)
        {
            content_children[i].classList.add("hidden");
        }

        //set up the archive from to acked correctory 
        // if the current journal is archived then the frorm should be to unarchive the journal
        // if the current journal is not archived it should arcvive the journal 
        var arcive_journal_from = document.getElementById("arcive_journal_from");
        if (archived === 0)
        {
            arcive_journal_from.children[0].value = 1;
            arcive_journal_from.children[1].value = "Archive this journal.";
            arcive_journal_from.children[2].innerText = "This will make it so no user may add or edit the current journal.";
        }
        else
        {
            arcive_journal_from.children[0].value = 0;
            arcive_journal_from.children[1].value = "Un-Archive this journal.";
            arcive_journal_from.children[2].innerText = "This will make it so users may add or edit the current journal."
        }

        var to_show = document.getElementById("content-journal-manage");
        to_show.classList.remove("hidden");
    }

    update_remove_contrib(contrib_list) {
        var remove_user_list = document.getElementById("remove_user_list");
        remove_user_list.innerText = "";

        for (let i = 0; i < contrib_list.length; i++)
        {
            let option = new RemoveUserListItem(contrib_list[i].user_id, contrib_list[i].username);
            remove_user_list.appendChild(option.option);
        }
    }


    //update the journal lists with the given content
    update_journals_list(owned_journals, cont_journals) {
        var owned_journal_list = document.getElementById("owned_journal_list");
        var contrib_journal_list = document.getElementById("contrib_journal_list");

        //empty the lists
        owned_journal_list.textContent = "";
        contrib_journal_list.textContent = "";

        var new_li;
        //fill the lists with the given content
        for (let i = 0; i < owned_journals.length; i++)
        {
            new_li = new JournalListItem(owned_journals[i].journal_id, owned_journals[i].title);
            owned_journal_list.appendChild(new_li.item);
        }

        for (let i = 0; i < cont_journals.length; i++)
        {
            new_li = new JournalListItem(cont_journals[i].journal_id, cont_journals[i].title);
            contrib_journal_list.appendChild(new_li.item);
        }
    }

    display_journal_entries(entries, user_id) {
        var journal_page_entries = document.getElementById("journal-page-entries");
        journal_page_entries.innerText = "";
        //build the entries
        //use an abstract factory to construct the entires

        var event_entries = entries.events;
        var note_entries = entries.notes;
        var photo_entries = entries.photos;

        var new_entry;
        
        if (typeof event_entries !== 'undefined')
        {
            for (let i = 0; i < event_entries.length; i++)
            {
                new_entry = new JournalEntryView(event_entries[i], user_id, this.is_journal_owner);
                journal_page_entries.appendChild(new_entry.div);
            }
        }

        if (typeof note_entries !== 'undefined')
        {
            for (let i = 0; i < note_entries.length; i++)
            {
                new_entry = new JournalEntryView(note_entries[i], user_id, this.is_journal_owner);
                journal_page_entries.appendChild(new_entry.div);
            }
        }

        if (typeof photo_entries !== 'undefined')  
        {  
            for (let i = 0; i < photo_entries.length; i++)
            {
                new_entry = new JournalEntryView(photo_entries[i], user_id, this.is_journal_owner);
                journal_page_entries.appendChild(new_entry.div);
            }
        }
        
    }

    update_journal_title(title) {
        var current_journal_title = document.getElementsByClassName('current_journal_title');

        for(let i = 0; i < current_journal_title.length; i++)
        {
            current_journal_title[i].innerText = title;
        }
       
    }

    hide_manage(bool = true) {

        var manage_button = document.getElementById("Manage_button")

        if (bool) 
        {
            manage_button.classList.add('hidden');
        }
        else
        {
            manage_button.classList.remove('hidden');
        }
    }


}