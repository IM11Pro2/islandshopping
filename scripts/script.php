<?php
require_once("../config/config.php");
function printJavaScript(){

?>

<script type="text/javascript">
$(document).ready(function(){

    function sendAjaxRequest(url, data, getAsJSON){
        var type;

        if(getAsJSON){
            type = "json";
        }
        else{
            type = "html";
        }
        $.ajax({
            url: url,
            data: data,
            dataType: type
        });
    }

    $('input:checkbox[name="enemyCountries[]"]').click(function(event){
        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", enemycountry: $(this).parent().text(), <?php  echo session_name().': '.'"'.session_id().'"'; ?>}, false);
        }
    });


    $('input:radio[name="playerCountry"]').click(function(event) {
        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", playercountry: $(this).parent().text(), <?php  echo session_name().': '.'"'.session_id().'"'; ?>}, false);
        }
    });

    $('body').on('click','input:radio[name="bankstate"]', function(event) {
        if($('input:checked').length > 0){
            sendAjaxRequest("../bank/Bank.php", {handle: "bank", bankstate: $.trim($(this).val()) , <?php  echo session_name().': '.'"'.session_id().'"'; ?>}, false);
        }
    });

    $(':button[name="MenuSubmit"]').click(function (event) {
        sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", endState: "Menu", <?php  echo session_name().': '.'"'.session_id().'"'; ?>}, false);
    });


    $('body').on('click',':button[name="MapSubmit"]',function (event) {
        sendAjaxRequest("../states/MapState.php", {handle: "MapState", endState: "Map", <?php  echo session_name().': '.'"'.session_id().'"'; ?>}, false);
    });

    $('body').on('click',':button[name="MapRandom"]',function (event) {
        sendAjaxRequest("../states/MapState.php", {handle: "MapState", randomizeMap: "randomizeMap", <?php  echo session_name().': '.'"'.session_id().'"'; ?>}, false);
    });


    function activeElementHandler(){
        paper.forEach(function (el) {
            el.attr('stroke-width', 1);
        });

        this.attr('stroke-width', 3);

        var regionId = $(this.node).data('region');

        //sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", getNeigbours: regionId, <?php  echo session_name().': '.'"'.session_id().'"'; ?>},true);
        sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", getCountry: regionId, <?php  echo session_name().': '.'"'.session_id().'"'; ?>},true);
    }

    function resetElements(event){
        event.stopPropagation();
        if(event.target == paper.canvas){
            paper.forEach(function(el){
                el.attr('stroke-width',1).attr('fill-opacity', 1);
            });
        }

    }

    function activateRegions(){
        paper.forEach(function (el) {

            el.click(activeElementHandler);

            //wär schön, geht aber leider nicht
/*          var value =  $(el.node).data('value');
            alert(el.node);
            alert(value);
            el.attr('text', value);*/

        });

        $(paper.canvas).on('click',resetElements);

    }

    function deactivateRegions(){
        paper.forEach(function (el) {
            el.unclick(activeElementHandler);
        });

        $(paper.canvas).off('click',resetElements);
    }

    function highlightNeighbourRegions(regions){

        paper.forEach(function (el) {
            el.attr('fill-opacity', 0.3);

            for(var i = 0; i < regions.neighbours.length; i++){

                if($(el.node).data("region") == regions.neighbours[i] || $(el.node).data("region") == regions.activeRegion){
                    el.attr('fill-opacity', 1);
                }
            }
        });
    }

    function addBasicCapitalToRegion(payment) {
        paper.forEach(function (el) {
            if($(el.node).data("text") == payment.activeRegion){
                //alert(country.country.name);
                var value = payment.payment.value;
                el.attr('text', value);
            }
        });
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

            if(hasParamValue(settings.url,"endState","Menu")){
                deactivateRegions();

            }

            if(hasParamValue(settings.url,"endState","Map")){
                activateRegions();
            }
        }
        if(settings.url.indexOf("randomizeMap")!= -1){
            $('#content').html(xhr.responseText);
        }
        if(settings.url.indexOf("getNeigbours")!= -1){
            var regions = $.parseJSON(xhr.responseText);
            highlightNeighbourRegions(regions);
        }
        if(settings.url.indexOf("getCountry")!= -1){
            var payment = $.parseJSON(xhr.responseText);
            addBasicCapitalToRegion(payment);
        }
    });

    $('.ajaxSuccess').ajaxError(function(e, xhr, settings, error) {
        alert("error: " + error);

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