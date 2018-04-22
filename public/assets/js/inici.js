window.onload = function(){

};

function ajaxx(){
        $.ajax({
            type: 'POST',
            url: '/prof',
            data: {
                "username": document.getElementById("username").value,
                "email": document.getElementById("email").value,
                "birthdate": document.getElementById("birthdate").value,
                "psw": document.getElementById("psw").value,
                "confirmpsw": document.getElementById("confirmpsw").value,
                "myfile" :document.getElementById("myfile").value
            }
        });

}






function validation() {
    //TODAS LAS PUTAS VALIDACIOENS
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

