$(document).ready(function(){

    console.log(document.cookie);
    var years = document.cookie.split(";");
    console.log(years[1].split=("="));

    // TODO set profile cookie with js
    /*$("#submit").click(function(){

        document.cookie = "pseudo="+$(this).val(); 
    });*/

    $("#years").click(function(){
        console.log($(this).val());
        document.cookie = "years="+$(this).val();
    });
});