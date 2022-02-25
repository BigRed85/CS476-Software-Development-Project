var regex_email = /^\w+@\w+\.[a-zA-Z]{2,3}$/;
var regex_pass = /^(?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+={}:;'<,>.?~-]).{8,}$/;
var regex_name = /^(?=.*[\d])(?=.*[a-z])(?=.*[A-Z])(?!.*[!@#$%^&*()_+={}:;'<,>.?~-]).{4,}$/;

window.addEventListener("load", init);
function init()
{
    setUpEvents();
}

function setUpEvents()
{
    //validate the user information prior to sending it to the server (for login and signup)
    document.getElementById("loginForm").addEventListener("submit", LoginValidate, false);
    document.getElementById("signupForm").addEventListener("submit", SignupValidate, false);
    
    //switch between login and signup forms
    document.getElementById("signup_link").addEventListener("click", login_signup_toggle, false);
    document.getElementById("login_link").addEventListener("click", login_signup_toggle, false);
}

function login_signup_toggle(event)
{
    login = document.getElementById("login");
    signup = document.getElementById("signup");

    login.classList.toggle("hidden");
    signup.classList.toggle("hidden");

    event.preventDefault();
}

function LoginValidate(event)
{
    
    var isValid = true;

    var elements = event.currentTarget;
    var err_msg = document.getElementsByClassName("err_msg1");
    
    var i;

    //reset error messages
    for (i = 0; i < elements.length; i++)
    {
        elements[i].classList.remove("red_border");
    }
    
    for(i = 0; i < err_msg.length; i++)
    {
        err_msg[i].textContent = "";
    }

    //output errors if the user name or the password are not the right format
    if (elements[0].value === "" || regex_name.test(elements[0].value) == false)
    {
        err_msg[0].textContent = "Invalid user name.";
        elements[0].classList.add("red_border");
        isValid = false;
    }
    if (elements[1].value === "" || regex_pass.test(elements[1].value) == false)
    {
        err_msg[1].textContent = "Invalid pasword.";
        elements[1].classList.add("red_border");
        isValid = false;
    }

    if (isValid === false)
    {
        event.preventDefault();
    }
    
}


function SignupValidate(event)
{
    var isValid = true;

    var elements = event.currentTarget;
    var err_msg = document.getElementsByClassName("err_msg");
    
    var i;
    
    for(i = 0; i < err_msg.length; i++)
    {
        err_msg[i].textContent = "";
    } 
    
    for(i = 0; i < elements.length; i++)
    {
        
        elements[i].classList.remove("red_border");

        if (elements[i].name == "email")
        {
            if (elements[i].value == "")
            {
                err_msg[i].textContent = "Email cannot be empty!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
            else if (regex_email.test(elements[i].value) == false)
            {
                err_msg[i].textContent = "Email is invalid format!(a@a.ca)";
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        else if (elements[i].name == "confe")
        {
            if(elements[i].value != elements[i-1].value)
            {
                err_msg[i].textContent = "Does not match email!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        else if (elements[i].name == "pass")
        {
            if (elements[i].value == "")
            {
                err_msg[i].textContent = "Password cannot be empty!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
            else if (regex_pass.test(elements[i].value) == false)
            {
                err_msg[i].textContent = "Password invalid format! Must be at least 8 char, and have one of each[uppercase, lowercase, digit, symbol]";
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        else if (elements[i].name == "confp")
        {
            if (elements[i].value != elements[i-1].value) 
            {
                err_msg[i].textContent = "Does not match Password!"
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        else if (elements[i].name == "username")
        {
            if (elements[i].value == "")
            {
                err_msg[i].textContent = "Username cannot be empty!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        else if (elements[i].name == "avatar")
        {
            if (elements[i].value == "")
            {
                err_msg[i].textContent = "Avatar cannot be empty!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        else if (elements[i].name == "fname")
        {
            if (elements[i].value == "")
            {
                err_msg[i].textContent = "First name cannot be empty!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
            else if (regex_name.test(elements[i].value) == false)
            {
                err_msg[i].textContent = "Names must start with a uppercase, and contain only letters!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        else if (elements[i].name == "lname")
        {
            if (elements[i].value == "")
            {
                err_msg[i].textContent = "Last name cannot be empty!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
            else if (regex_name.test(elements[i].value) == false)
            {
                err_msg[i].textContent = "Names must start with a uppercase, and contain only letters!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        else if (elements[i].name == "bday")
        {
            if (elements[i].value == "")
            {
                err_msg[i].textContent = "Birthday cannot be empty!";
                elements[i].classList.add("red_border");
                isValid = false;
            }
        }
        
    }

    if (isValid == false)
    {
        event.preventDefault();
    }

    
}