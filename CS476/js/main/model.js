class JournalEntry {

}

//only one of these 
class Page {

}

class Journal {

}

//only one of these
class User {

}

class Model {
    currentUser;

    constructor(){
        //get the user information and build the model
        this.currentUser = new User();
    }

    load_user_info() {
        //request a json that contains the users information
    }

    build_user() {

    }
}