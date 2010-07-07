var onlineState = 1;
var lockScroll = true;
var menuVisible = false;
var lastLine = 0;
var channels = new Array();
$(document).ready(function() {
    $('#menu ul').css('display',"none");
    $('#menu ul').fadeTo('fast',0);
    $('#menu').click(function(){
        if(menuVisible){
            $('#menu ul').fadeTo('fast',0,function(){
                $('#menu ul').css('display',"none");
            });
            menuVisible = false;
        } else { 
            $('#menu ul').css('display',"block");
            $('#menu ul').fadeTo('fast',1);
            menuVisible = true;
        }
    });
    
    $('body').append('<div id="lockScroll">lockScroll: true</div>');
    
    $('#lockScroll').click(function(){
        if(lockScroll){
            $('#lockScroll').html('lockScroll: false');
            lockScroll = false;
        }
        else{
            $('#lockScroll').html('lockScroll: true');
            lockScroll = true;
        }
    });
    $('#logOptions').append('<label title="Search log with this regex"><input style="color: #cccccc;" type="text" id="searchText" value="Search" name="search" /></label><br />');
    $('#logOptions').append('<label title="Togle visibility for ping-pong events"><input type="checkbox" value="1" name="ping" />Ping-pong</label><br />');
    $('#logOptions').append('<label title="Togle visibility for error, notice & debug messages and numeric server messages"><input type="checkbox" value="1" name="console" checked="true" />Console messages</label><br />');
    $('#logOptions').append('<label title="Togle visibility for wLeafs messages to the IRCserver"><input type="checkbox" value="1" name="input" checked="true" />Outgoing messages</label><br />');
    $('#logOptions').append('<label title="Togle visibility for nickchanges"><input type="checkbox" value="1" name="nick" checked="true" />Nick changes</label><br />');
    $('#logView').scroll(function(){
        if($('#logView').scrollTop() >= ($('#logView')[0].scrollHeight-$('#logView').height())){
            $('#lockScroll').html('lockScroll: true');
            lockScroll = true;
        } else {
            $('#lockScroll').html('lockScroll: false');
            lockScroll = false;
        }
    });
    $('#searchText').focus(function(){
        if($(this).attr('value')=="Search"){
            $(this).css("color","#000000");
            $(this).attr('value',"");
        }
        lockScroll = false;
            $('#lockScroll').html('lockScroll: false');
    });
    $('#searchText').blur(function(){
        if($(this).attr('value')==""){
            $(this).css("color","#cccccc");
            $(this).attr('value',"Search");
        }
        lockScroll = true;
            $('#lockScroll').html('lockScroll: true');
    });

    updateFields();
    setTimeout("$('#logView').scrollTop($('#logView')[0].scrollHeight)",500);
    setInterval('updateFields()',1000);
});

function updateFields(){
    $.ajax({
        url: '/AJAX/activestate.php',
        cache: false,
        success: function(html){
            var online = html.split('|');
            $('#activeState').html(online[0]);
            onlineState = online[1];
        }
    });
    if(onlineState == 1){
        var get ='';
        for(var i=0;i<$('#logOptions input[type="checkbox"]').length;i++){
            if($('#logOptions input[type="checkbox"]')[i].checked){
                if($('#logOptions input[type="checkbox"]')[i].name == "channel")
                    get += '&channel[]=' + $('#logOptions input[type="checkbox"]')[i].value;
                else
                    get += '&'+ $('#logOptions input[type="checkbox"]')[i].name+'=1';
            } else {
                if($('#logOptions input[type="checkbox"]')[i].name != "channel"){
                    get += '&'+ $('#logOptions input[type="checkbox"]')[i].name+'=0';
                }
            }
            
        }
       ($('#searchText').attr('value')!="Search")&&($('#searchText').attr('value')!="") ?  get += '&search=' + $('#searchText').attr('value') : false;
        $.ajax({
            url: '/AJAX/logViewer.php?_=0' + get,
            cache: false,
            success: function(html){
                var chans = html.split('\n');
                html = chans.pop();
                lines = html.split('<br />');
                var counter = 0;
                for(var i in lines){
                    var temp = Array();
                    temp = lines[i].split(':');
                    line = temp.reverse().pop();
                    type = temp.pop();
                    if(type){    
                        lines[i] = '<span class="'+type.trim().toLowerCase()+'" title="'+type.trim().toLowerCase()+'">' + temp.reverse().join(':').trim()+'</span>';
                        
                    }
                }
                newHtml = lines.join('\n');
                if(newHtml !=$('#logView').html()){
                    $('#logView').html(newHtml);
                }
                var checkbox = new Array();
                for(var j in chans){
                    tmp = channels.toString();
                    if(tmp.search(chans[j])== -1){
                       checkbox[j] = '<label title="Togle visibility for channelmessages in #'+chans[j]+'"><input type="checkbox" name="channel" value="'+chans[j]+'" checked="checked" />Channel messages of #'+chans[j]+'</label>';
                       $('#logOptions').append(checkbox[j] + '<br />\n');
                       channels.push(chans[j]);
                    }
                }
              //alert('Skipped: ' + counter + ' lines. Highest line: ' + lastLine);
            }
        });

    } else {
        $('#logView').html('Sorry, wLeaf is offline :(');
        lastLine = 0;
    }
    if(lockScroll){
        $('#logView').animate({scrollTop: $('#logView')[0].scrollHeight});
    }
}