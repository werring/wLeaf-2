var menuVisible = false;
var selectedOptions = [];
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
    if($("#binds")[0] != null){
        showCommands($("#binds")[0].value);
    }
    $("#binds").change(function(){
        showCommands($(this)[0].value);
    });
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

function showCommands(bind){
    $("#commands").html("<optgroup label=' - Available commands - '><option></option></optgroup>");
    $.ajax({
        url: ("AJAX/commandbinds.php?bind=" + bind),
        success: function(html){
            $("#commands").html("<optgroup label=' - Available commands - '>" + html + "</optgroup>");
        }
    });
}
function countSelected(select,maxNumber){
   for(var i=0; i<select.options.length; i++){
     if(select.options[i].selected && !new RegExp(i,'g').test(selectedOptions.toString())){
        selectedOptions.push(i);
     }

     if(!select.options[i].selected && new RegExp(i,'g').test(selectedOptions.toString())){
      selectedOptions = selectedOptions.sort(function(a,b){return a-b});  
       for(var j=0; j<selectedOptions.length; j++){
         if(selectedOptions[j] == i){
            selectedOptions.splice(j,1);
         }
       }
     }

     if(selectedOptions.length > maxNumber){
      var throwAlert = true;
        select.options[i].selected = false;
        selectedOptions.pop();
     }  
   }            

     if(throwAlert == true){
        document.body.focus();
     }
}

function selectCommand(select){
    $.ajax({
        url: "AJAX/commandinfo.php?command=" + select.options[0].value,
        success: function(html){
            data = html.split("|");
            bind = data.shift();
            access = data.shift();
            commandFile = data.join("|");
            $("#cmdInfo").text("Bind: " + bind + " Needed Access: " + access);
            $("#cmdCode")[0].innerHTML=commandFile.replace(/#0000BB/g,"#bed8fe").replace(/#007700/g,"#CCFFCC").replace(/#FF8000/g,"#FFD000").replace(/#DD0000/g,"#F63");
        }
        });
    
    
}
