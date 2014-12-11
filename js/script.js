/**
 * Created by Don Shane on 12/6/2014.
 */

window.onload = function () {
    personalize();

    $('toggle-register').observe('click', toggleUserAdmin);
    $('register-btn').observe('click', validateForm);
    $('login-btn').observe('click', initLogin);
    $('send-message-btn').observe('click', initNewMessage);
    $('logout-btn').observe('click', logoutUser);
};

function initNewMessage() {
    var subject = document.forms['messageForm']['msg-subject'],
        recipients = document.forms['messageForm']['msg-recipients'],
        body = document.forms['messageForm']['msg-body'];

    // add validation

    sendMessage(subject.value, recipients.value, body.value);
}

function sendMessage(subject, recipients, body) {
    alert(recipients);
    new Ajax.Request("controllers/message.php", {
        method: "POST",
        parameters: {
            subject: subject,
            recipients: recipients,
            body: body
        },
        onSuccess: messageSentSuccess,
        onFailure: messageSentFail
    });
}

function messageSentSuccess(reponse) {
    alert(reponse.responseText);
}

function messageSentFail() {
    alert('Something went wrong');
}

function logoutUser() {
    $('sign-in').setStyle({
        display: 'block'
    });
    $('homepage').setStyle({
        display: 'none'
    });

    new Ajax.Request('controllers/logout.php');
}

function toggleUserAdmin() {
    var registrationScreen = $('sign-up'),
        userScreen = $('user-page');
    registrationScreen.style.display == "block" ? registrationScreen.style.display = "none" : registrationScreen.style.display = "block";
    userScreen.style.display == "none" ? userScreen.style.display = "block" : userScreen.style.display = "none";
}

function initLogin() {
    var username = document.forms["signInForm"]["loginUsername"],
        password = document.forms["signInForm"]["loginPassword"];

    // add validation

    login(username.value, password.value);
}

function login(username, password) {
    new Ajax.Request("controllers/login.php", {
        method: "POST",
        parameters: {
            username: username,
            password: password
        },
        onSuccess: userLoginSuccess,
        onFailure: userLoginFail
    });
}

function userLoginSuccess(response) {
    var result = response.responseText;
    //alert("Success! \n\n" + result);
    $('test').update(isNaN(result));
    if (isNaN(result)) {
        alert("Wrong username or password");
    } else {
        //alert("Logged in");
        $('sign-in').setStyle({
            display: 'none'
        });
        $('homepage').setStyle({
            display: 'block'
        });
    }

    // Pulls the user last 10 received emails
    initInbox();
}

function initInbox()
{
    new Ajax.Updater('inbox', 'controllers/inbox.php', {
        method: "GET",
        onFailure: populateInboxFail()
    });
    //new Ajax.Request("controllers/inbox.php", {
    //    method: "GET",
    //    onSuccess: populateInboxSuccess,
    //    onFailure: populateInboxFail
    //});
}

//function populateInboxSuccess(response)
//{
//    alert("Message: " + response.responseText);
//    $('inbox').update(response.responseText);
//}

function populateInboxFail()
{

}

function userLoginFail() {
    alert("unable to login");
}

function personalize() {
    var firstNameInput = document.getElementById('newFirstName'),
        firstNameDivElement = document.getElementById('fName'),
        lastNameDivInput = document.getElementById('newLastName'),
        lastNameDivElement = document.getElementById('lName');

    firstNameInput.addEventListener('keyup', function(){
        var firstNameText = firstNameInput.value,
            lastNameText = lastNameDivInput.value;
        firstNameDivElement.innerHTML = firstNameText;
        lastNameDivElement.innerHTML = lastNameText;
    });
}

function validateForm() {
    var isValid = true,
        fname = document.forms["signUpForm"]["newFirstName"],
        lname = document.forms["signUpForm"]["newLastName"],
        username = document.forms["signUpForm"]["newUsername"],
        password = document.forms["signUpForm"]["newPassword"],
        details = [fname, lname, username, password];

    for (var i = 0; i < details.length; i++) {
        if (details[i].value == null || details[i].value == "") {
            details[i].style.backgroundColor = "red";
            isValid = false;
            console.log('in loop');
        } else {
            details[i].style.backgroundColor = "white";
        }
    }

    if (!(validatePassword(password.value))) {
        password.style.backgroundColor = "white";
        isValid = false;
        console.log('in password check');
        //passwords have at least one number and
        //one letter, and one capital letter and are at least 8 digits long.
    }

    if (isValid)
    {
        createUser(fname.value, lname.value, username.value, password.value);
    } else {
        alert('Errors withing form');
    }
}

function createUser(firstname, lastname, username, password) {
    new Ajax.Request("controllers/register.php", {
        method: "POST",
        parameters: {
            firstname: firstname,
            lastname: lastname,
            username: username,
            password: password
        },
        onSuccess: userAddedSuccess,
        onFailure: userAddedFail
    });
}

function userAddedSuccess(response) {
    alert(response.responseText);
}

function userAddedFail() {
    alert("Something went wrong")
}

function validatePassword(pass) {
    var illegalChars = /[\W_]/;

    if (pass.length < 7)
        return false;
    else if (illegalChars.test(pass))
        return false;
    else if (!((pass.search(/(a-z)+/)) && (pass.search(/(0-9)+/))))
        return false;
    return true
}
