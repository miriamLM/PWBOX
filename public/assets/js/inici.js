window.onload = function(){

};

//console.log(i);

function profile(){
    var username = document.getElementById("username").value;
    console.log(username);
    console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
    var email = document.getElementById("email").value;
    var birthdate = document.getElementById("birthdate").value;
    var password = document.getElementById("password").value;
    var confirm = document.getElementById("confirm").value;
    var image = document.getElementById("image").value;

    //validar si lo que entran es correcto
    validation();
    //cuandos sea correcto
    //window.location = '';
    window.location.assign("http://www.slimapp.test/prof")


}

function validation() {
    //TODAS LAS PUTAS VALIDACIOENS
}


function myFunction() {
    window.location.assign("http://www.slimapp.test/lp/{id}")



}

