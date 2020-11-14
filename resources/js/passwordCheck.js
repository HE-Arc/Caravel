var password = document.getElementsByClassName('newPassword')[0];

password.addEventListener("keyup",function(){checkPassword(password.value)});

function checkPassword(value){
    let strength = 0;
    if (value.match(/[a-z]+/))strength++;
    if (value.match(/[A-Z]+/))strength++;
    if (value.match(/[0-9]+/))strength++;
    if (value.match(/[^\p{L}\d\s]/u))strength++;

    let message = "";
    let color = "";
    if(strength > 3 && value.length > 5){
        message = "strong";
        color = "green";
    }
    else if(strength > 2 && value.length > 5){
        message = "moderate";
        color = "orange";
    }
    else {
        message = "weak";
        color = "red";
    }
    var messageStrength = document.getElementById("passwordStrength");
    messageStrength.innerHTML = message;
    messageStrength.style.color = color;
}