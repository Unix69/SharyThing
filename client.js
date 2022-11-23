var pwShown = 0;
var cp_err = 0;
var p_err = 0;
var e_err = 0;




function  JSmessageError(HTMLonthefly){
    document.getElementById("checkErr").innerHTML = HTMLonthefly;
}



function areCookiesEnabled() {
	  document.cookie = "__verify=1";
	  var supportsCookies = document.cookie.length > 1 &&
	                        document.cookie.indexOf("__verify=1") > -1;
	  var thePast = new Date(1976, 8, 16);
	  document.cookie = "__verify=1;expires=" + thePast.toUTCString();
	  return supportsCookies;
	}

function checkCookies(){
	if(!areCookiesEnabled()) {
		document.write("<p>Cookies are disabled! Please activate cookies and</p>"); 
		document.write("<a href=index.php> retry</a>");
		window.stop();
	}
		
}



function validateRequest(min){
return(( /^\d+$/.test(min)) && (min > 0) && (min <= 180));
}

function cancelRequest(){
document.getElementById("min").value = "";
}






function validateReservation() {

 var min = document.getElementById("min").value;

    if (validateRequest(min) == false) {
    
        cancelRequest();
        JSmessageError('<p><font color="#ff0000">Invalid request. Try again to make an other request</font></p>');
        
        return (false);
    }
    return (true);
}



function updateImage(){
document.getElementById("imageForm").style.visibility = "visible";
}



function validateEmail(email) {
    return  /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/.test(email);
}

function validateString(string) {
    return /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/.test(string);
}

function validatePassword(psw){
 return /^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{3,}/.test(psw);
}


function cancelInputL() {
    document.getElementById("password").value = "";
    document.getElementById("username").value = "";
    var usrDiv = document.getElementById("checkUserName");
    var pswDiv = document.getElementById("checkPassword");
    usrDiv.innerHTML = ' Username ';
    pswDiv.innerHTML = ' Password ';
    errDiv=document.getElementById("checkErr");
    errDiv.innerHTML='';
}

function cancelInput() {
    document.getElementById("password").value = "";
    document.getElementById("confirmpassword").value="";
    document.getElementById("username").value = "";
    var cpswDiv = document.getElementById("checkConfirmPassword");
    var usrDiv = document.getElementById("checkUserName");
    var pswDiv = document.getElementById("checkPassword");
    usrDiv.innerHTML = ' Username ';
    cpswDiv.innerHTML = ' Confirm ';
    pswDiv.innerHTML = ' Password ';
}





function inputSecurity(password, username) {
    var level = 0;
    var patt_1 = password;
    var patt_2 = username;   
    
    
      
    if (patt_1.indexOf(username) == -1 || patt_2.indexOf(password) == -1) {
        level = level + 50;
    }
    if (password.length < 10 && password.length > 6) {
        level = level + 15;
    } else if (password.length < 7 && password.length > 4) {
        level = level + 30;
    } else if (password.length < 5) {
        level = level + 50;
    }
    if (/^[a-z]+$/.test(password) || /^[0-9]+$/.test(password)) {
        level += 30;
    }
    
    
    
    
    
    var perc = ((level * 100) / 130);
    return perc;
}




function calculateSecurity() {
    var psw = document.getElementById("password").value;
    var usr = document.getElementById("username").value;
    var errDiv = document.getElementById("checkErr");
    var security = inputSecurity(psw, usr);
    if (security < 20) {
        errDiv.innerHTML = '<br><label><font color="#00ff00"> password is secure </font></label><br>';
    } else if (security > 20 && security < 50) {
        errDiv.innerHTML = '<br><label><font color="#ffff00"> password is not so secure </font></label><br>';
    } else {
      errDiv.innerHTML = '<br><label><font color="#ff0000"> password is not secure </font></label><br>';
    }
}




function validateFormParameter() {
    
    var cpsw = document.getElementById("confirmpassword").value;
    var psw = document.getElementById("password").value;
    if (e_err == 1 || cp_err == 1 || p_err == 1 || cpsw != psw) {
        cancelInput();
        return false;
    } else {
        return true;
    }

}






function validateFormParameterL() {  
    if (e_err == 1 || p_err == 1) {
        cancelInputL();
        return false;
    } else {
        return true;
    }

}




function onChangeEmail() {
    
    var usrDiv = document.getElementById("checkUserName");
    var usr = document.getElementById("username").value;
    
    if (usr == "" || usr == " ") {
        usrDiv.innerHTML = ' Username ';
        e_err = 0;
     } else if (validateEmail(usr) == true && usr.length <= 32) {
        usrDiv.innerHTML = '<font size="3"> Username </font><font color="#00ff00"><i class="glyphicon glyphicon-ok"></i></font>';
        e_err = 0;
    } else {
        e_err = 1;
        usrDiv.innerHTML = '<font size="3"> Username </font><font color="#ff0000"><i class="glyphicon glyphicon-remove"></i></font>';
    }
    
}



function onChangeConfirmPassword() {
    var cpswDiv = document.getElementById("checkConfirmPassword");
    var cpsw = document.getElementById("confirmpassword").value;
    var psw = document.getElementById("password").value;
    if(cpsw == "" || cpsw == " "){
        cpswDiv.innerHTML = ' Confirm ';
        cp_err = 0;
    } else if (validatePassword(cpsw) == true && psw == cpsw && cpsw.length <= 32) {
        cpswDiv.innerHTML = '<font size="3"> Confirm </font><font color="#00ff00"><i class="glyphicon glyphicon-ok"></i></font>';
        cp_err = 0;
    } else {
        cp_err = 1;
        cpswDiv.innerHTML = '<font size="3"> Confirm </font><font color="#ff0000"><i class="glyphicon glyphicon-remove"></i></font>';
    }
}

function onChangePassword() {
    calculateSecurity();
    var psw = document.getElementById("password").value;
    var pswDiv = document.getElementById("checkPassword");
    if (psw == "" || psw == " ") {
        pswDiv.innerHTML = ' Password ';
        p_err = 0;
    } else if (validatePassword(psw) == true && psw.length <= 32) {
        p_err = 0;
        pswDiv.innerHTML = '<font size="3"> Password </font><font color="#00ff00"><i class="glyphicon glyphicon-ok"></i></font>';
    } else {
        p_err = 1;
        pswDiv.innerHTML = '<font size="3"> Password </font><font color="#ff0000"><i class="glyphicon glyphicon-remove"></i></font>';
    }

}


function validateInputL() {
  
    if (validateFormParameterL() == false) {
        JSmessageError('<p><font color="#ff0000">Try again to compile Form</font></p>');
        return (false);
    }
      
    return (true);
}


function validateInput() {
    if (validateFormParameter() == false) {
        JSmessageError('<p><font color="#ff0000">Try again to compile Form</font></p>');
        return (false);
    }
    return (true);
}


    function show() {
        document.getElementById('password').setAttribute('type', 'text');
        document.getElementById('confirmpassword').setAttribute('type', 'text');
    }

    function hide() {
        document.getElementById('password').setAttribute('type', 'password');
        document.getElementById('confirmpassword').setAttribute('type', 'password');
    }





    function hideShow() {
        if (pwShown == 0) {
            pwShown = 1;
            show();
        } else {
            pwShown = 0;
            hide();
        }
    }