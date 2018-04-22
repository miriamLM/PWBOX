window.onload = function(){

};


function ajaxx(){
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