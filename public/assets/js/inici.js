window.onload = function(){

};
var i=0;

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