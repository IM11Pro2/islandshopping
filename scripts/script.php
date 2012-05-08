<?php
require_once("../config/config.php");
function printJavaScript(){

?>

<script type="text/javascript">
$(document).ready(function(){

    function sendAjaxRequest(url, data){
        $.ajax({
            url: url,
            data: data
        });
    }

    $('input:checkbox[name="enemyCountries[]"]').click(function(event){
        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "ajaxRequest", enemycountry: $(this).parent().text(), <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
        }
    });


    $('input:radio[name="playerCountry"]').click(function(event) {
        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "ajaxRequest", playercountry: $(this).parent().text(), <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
        }
    });

    $(':button[name="MenuSubmit"]').click(function (event) {
        sendAjaxRequest("../states/MenuState.php", {handle: "ajaxRequest", endState: "Menu", <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
    });


    $('body').on('click',':button[name="MapSubmit"]',function (event) {
        sendAjaxRequest("../states/MapState.php", {handle: "ajaxRequest", endState: "Map", <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
    });


    function activateRegions(){
        $('body').on('click', '.region', function(){

            paper.forEach(function (el) {
                el.attr({ stroke: "blue" });
            });

            /*var id = $(this).attr('id');

            paper.getElementByPoint(mouseX, mouseY).attr({stroke: "green"});
            */
        });
    }

    function deactivateRegions(){
        $('body').off('click', '.region');
    }


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
        }
        if(settings.url.indexOf("endState")!= -1){
            //alert(settings.url);
            $('#content').html(xhr.responseText);

            // TODO unterscheiden zu welchem state gewechselt wird
            if(hasParamValue(settings.url,"endState","Menu")){
                deactivateRegions();

            }

            if(hasParamValue(settings.url,"endState","Map")){
                activateRegions();
            }
        }
    });

    function hasParamValue(url, param, value){
        url = $.trim(url);
        param = $.trim(param);
        value = $.trim(value);

        param = param.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");

        var regexS = "[\\?&]"+param+"=([^&#]*)";
        var regex = new RegExp( regexS );
        var results = regex.exec(url);

        if(results[1] == value){
            return true;
        }
        return false;
    }


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

?>