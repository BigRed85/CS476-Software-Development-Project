
controller = new Controller();

window.onload = init;

function init() {
    
    //get and display journal info
    controller.update_journals_list();
    //initialise the page
    controller.init();
    //set up event listeners
    controller.init_listeners();
    
}


