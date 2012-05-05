<?php
require_once("../config/config.php");
function printJavaScript(){

?>

<script type="text/javascript" charset="utf-8" language="JavaScript">
$(document).ready(function(){

    function sendAjaxRequest(url, data){
        $.ajax({
            url: url,
            data: data
        });
    }

    $('input:checkbox[name="enemyCountries[]"]').click(function(event){
        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "ajaxTest", enemycountry: $(this).parent().text(), <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
        }
    });


    $('input:radio[name="playerCountry"]').click(function(event) {
        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "ajaxTest", playercountry: $(this).parent().text(), <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
        }
    });

    $(':button[name="Submit"]').click(function (event) {
        sendAjaxRequest("../states/MenuState.php", {handle: "ajaxTest", endState: "endState", <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
    });

    $('.ajaxSuccess').ajaxSuccess(function(e, xhr, settings) {
        if(settings.url.indexOf("../states/MenuState.php")!= -1){
            if(settings.url.indexOf("playercountry")!= -1){
            //alert(settings.url);
            //alert($("input:radio:checked[name='playerCountry']").val());
            //alert($("#playerCountry:checked").parent().text());
            disableEnemyCountry($("input:radio:checked[name='playerCountry']").parent().text());

/*            $(this).text('Triggered ajaxSuccess handler. The ajax response was:'
                                     + xhr.responseText );*/
            }
            if(settings.url.indexOf("enemycountry")!= -1){
                alert(settings.url);
            }
            if(settings.url.indexOf("endState")!= -1){
                //sendAjaxRequest("../views/MapStateView.php", {handle: "ajaxTest", loadSite: "printView", <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
                $("#content").load("../views/MapStateView.php");
                return false;
            }
        }

        else if(settings.url.indexOf("../views/MapStateView.php")!= -1){
           /* $("#content").load("../views/MapStateView.php");
            return false;*/
        }
    });

});

function disableEnemyCountry($playerCountry){
    //enable last disabled player country
    $("input:checkbox:disabled[name='enemyCountries[]']").attr("disabled", false);
    //disable new player country
    $("#enemy_"+$playerCountry).attr("disabled",true);
    $("#enemy_"+$playerCountry).attr("checked",false);
}

function jsonpcallback(data) {
    alert(data["message"]);
}

</script>
<?php
}

//dataType: "jsonp",
//contentType: 'application/json',
//jsonp : "callback",
//jsonpCallback: "jsonpcallback"
?>