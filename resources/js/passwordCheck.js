var password = document.getElementById('passwordRegister');

password.addEventListener("keyup",function(){checkPassword(password.value)});

function checkPassword(value){
    let strength=0;
    if (value.match(/[a-z]+/))strength++;
    if (value.match(/[A-Z]+/))strength++;
    if (value.match(/[0-9]+/))strength++;
    if (value.match(/[^\p{L}\d\s]/u))strength++;

    let message="";
    if(strength >3&&value.length>8)message="strong";
    else if(strength >2&&value.length>8)message="moderate";
    else message="weak";
    document.getElementById("passwordStrength").innerHTML=message;
}