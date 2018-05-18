window.onload = function(){

};
var i=0;

function ajaxx(){
    validation();
    var data = new FormData();
    data.append("username",document.getElementById("username").value);
    data.append("email",document.getElementById("email").value);
    data.append("birthdate",document.getElementById("birthdate").value);
    data.append("psw",document.getElementById("psw").value);
    data.append("confirmpsw",document.getElementById("confirmpsw").value);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open('POST','/prof',true);
    xmlhttp.send(data);
}






function validation() {
   var email = document.getElementById("email").value;
    var username = document.getElementById("username").value;
    var birthdate = document.getElementById("birthdate").value;
    var pass = document.getElementById("psw").value;
    var confirm = document.getElementById("confirmpsw");
    var max = 12;
    var min= 6;

    var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
    if (email== '' || !re.test(email)) {
        // Si no se cumple la condicion...
        alert('[ERROR] El campo email debe tener un valor de...');
       return false;
    }


    if(username== '' || username ==/^[a-zA-Z0-9]/){
        alert('[ERROR] username solo puede ser valores alphanumeric');
        return false;
    }

    if(!birthdate.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/)){
        alert('[ERROR] birthdate');
        return false;
    }

    var upperCaseLetters = /[A-Z]/g;

    var numbers = /[0-9]/g;

    if(pass=='' ||pass.length<min || pass.length>max || (!pass.match(numbers)) || (!pass.match(upperCaseLetters))){
        alert('[ERROR] password tiene que tener un numero');
        return false;
    }
    if(confirm !== pass){
        alert('[ERROR] la password tiene que ser la misma');

        return false;

    }


}


function validationLogin() {
    var email = document.getElementById("email").value;
    var username = document.getElementById("username").value;
    var pass = document.getElementById("psw").value;
    var confirm = document.getElementById("confirmpsw");
    var max = 12;
    var min= 6;

    var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
    if (email== '' || !re.test(email) || username== '' || username ==/^[a-zA-Z0-9]/) {
        // Si no se cumple la condicion...
        alert('[ERROR] El campo email debe tener un valor de...');
        return false;
    }


    if(username== '' || username ==/^[a-zA-Z0-9]/){
        alert('[ERROR] username solo puede ser valores alphanumeric');
        return false;
    }

    var upperCaseLetters = /[A-Z]/g;

    var numbers = /[0-9]/g;

    if(pass=='' ||pass.length<min || pass.length>max || (!pass.match(numbers)) || (!pass.match(upperCaseLetters))){
        alert('[ERROR] password tiene que tener un numero');
        return false;
    }
    if(confirm !== pass){
        alert('[ERROR] la password tiene que ser la misma');

        return false;

    }


}





function myFunction() {

    document.getElementById("myForm").submit();


}

function myFunctionShared(){

    document.getElementById("myFormShared").submit();
}


function myFunctionFo(){

    document.getElementById("myFo").submit();
}

//<input type="file" id="addFile" name="addFile" size="2Mb" />

function uploadMore() {
    i++;
    var form = document.getElementById("upload");
    var file = document.createElement("INPUT");
    file.setAttribute("type", "file");
    file.setAttribute("name", "addFile"+i);
    file.setAttribute("size", "2Mb");
    file.setAttribute("id", "2Mb");
    form.appendChild(file);

}
