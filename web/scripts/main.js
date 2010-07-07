var menuVisible = false;
$(document).ready(function() {    
    $("#menu ul").css("display","none");
    $("#menu ul").fadeTo("fast",0);
    $("#menu").click(function(){
        if(menuVisible){
            $("#menu ul").fadeTo("fast",0,function(){
                $("#menu ul").css("display","none");
            });
            menuVisible = false;
        } else { 
            $("#menu ul").css("display","block");
            $("#menu ul").fadeTo("fast",1);
            menuVisible = true;
        }
    });
    updateFields();
    setInterval("updateFields()",2000);
});

function updateFields(){
    $.ajax({
        url: "AJAX/activestate.php",
        cache: false,
        success: function(html){
            var online = html.split("|");
            $("#activeState").html(online[0]);
        }
    });
}