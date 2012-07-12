<?php
require_once("../config/config.php");
function printJavaScript(){

?>
<script type="text/javascript">

function sendAjaxRequest(url, data, getAsJSON){
        var type;

        data.<?php  echo session_name(); ?> = "<?php echo session_id(); ?>";

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

$(document).ready(function(){


    activeRegion = false;

    $('input:checkbox[name="enemyCountries[]"]').click(function(event){
        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", enemycountry: $(this).parent().text()}, false);
        }
    });

    $('input:radio[name="playerCountry"]').click(function(event) {
        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", playercountry: $(this).parent().text()}, false);
        }
    });

    /*
    $('body').on('click','input:radio[name="bankstate"]', changeBankState);

    function changeBankState(event){
        resetElements(event);
        if($('input:checked').length > 0){
            sendAjaxRequest("../bank/Bank.php", {handle: "bank", bankstate: $.trim($(this).val())}, false);
        }
    }*/

    $(':button[name="MenuSubmit"]').click(function (event) {
        sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", endState: "Menu"}, false);
    });


    $('body').on('click',':button[name="MapSubmit"]',function (event) {
        sendAjaxRequest("../states/MapState.php", {handle: "MapState", endState: "Map"}, false);
    });

    $('body').on('click',':button[name="MapRandom"]',function (event) {
        sendAjaxRequest("../states/MapState.php", {handle: "MapState", randomizeMap: "randomizeMap"}, true);
    });

    $('body').on('click','#incidentView',function (event) {
        $(this).hide();
        $(this).text("");
    });

   /* $('body').on('click',':button[name="NextPlayerSubmit"]',function (event) {
        $('input:radio[name="bankstate"][value="<?php //echo Bank::PAY_OFF ?>"]').attr('checked','checked');
        resetElements(event);
        deactivateAllMouseClicks();
        sendAjaxRequest("../states/PlayState.php", {handle: "PlayState", nextPlayer: "nextPlayer"}, false);
    });*/

    $('body').on('click', '#actionButton', function(event){
        var activeListElement = $('#actionContainer .activeAction');
        activeListElement.removeClass('activeAction');
        var nextElement = activeListElement.next();
        if(nextElement.length < 1){
            nextElement = $('#actionContainer li').first()
        }
        nextElement.addClass('activeAction');
        resetElements(event);
        switch($('#actionContainer li').index(nextElement)){
            case 0: {   sendAjaxRequest("../bank/Bank.php", {handle: "bank", bankstate: "<?php echo Bank::PAY_OFF ?>"}, false); break;}
            case 1: {   sendAjaxRequest("../bank/Bank.php", {handle: "bank", bankstate: "<?php echo Bank::ATTACK ?>"}, false); break;}
            case 2: {   sendAjaxRequest("../bank/Bank.php", {handle: "bank", bankstate: "<?php echo Bank::DEPOSIT ?>"}, false); break;}
            case 3: {   deactivateAllMouseClicks();
                        sendAjaxRequest("../states/PlayState.php", {handle: "PlayState", nextPlayer: "nextPlayer"}, false); break;}
        }

    });

    function activeElementHandler(){

        var activeElement = this;

        if(activeElement.type == "text"){
            paper.forEach(function(el){

               if(el.type == "path"){

                   if(el.data('region') == activeElement.data('text')){
                       activeElement = el;
                   }

               }
            });
        }

        if(activeElement.data('regionOfPlayer') == 0){

            paper.forEach(function (el) {
                el.attr('stroke-width', 1);
            });

            activeElement.attr('stroke-width', 3);
            activeElement.toFront();
            path = activeElement;
            paper.getById((activeElement.id+1)).toFront();

            var regionId = activeElement.data('region');
            activeRegionId = regionId;
            /*if($('input:radio[name="bankstate"]:checked').val() == "<?php //echo Bank::PAY_OFF ?>" ){
                sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", getCountry: regionId, bankstate: "<?php //echo Bank::PAY_OFF ?>"},true);
            }
            else if($('input:radio[name="bankstate"]:checked').val() == "<?php //echo Bank::DEPOSIT ?>"){
                sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", getCountry: regionId, bankstate: "<?php //echo Bank::DEPOSIT ?>"},true);
            }
            else if($('input:radio[name="bankstate"]:checked').val() == "<?php //echo Bank::ATTACK ?>"){
                activeRegion = true;
                sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", getNeighbours: regionId},true);
            }*/
            var actionItem = $('#actionContainer .activeAction');
            if($('#actionContainer li').index(actionItem) == 0){
                sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", getCountry: regionId, bankstate: "<?php echo Bank::PAY_OFF ?>"},true);
            }
            else if($('#actionContainer li').index(actionItem) == 1){
                activeRegion = true;
                sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", getNeighbours: regionId},true);
            }
            else if($('#actionContainer li').index(actionItem) == 2){
                sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", getCountry: regionId, bankstate: "<?php echo Bank::DEPOSIT ?>"},true);
            }
        }
        else if(activeRegion){
            for(var i = 0; i < activeNeighbours.length; i++) {

                if (activeElement.data("region") == activeNeighbours[i]){
                    sendAjaxRequest("../states/PlayState.php",{handle: "PlayState", region: activeRegionId ,enemy: activeElement.data("region")},true);
                    //alert("ANGRIFF");

                }

            }
        }
    }

    function resetElements(event){
        event.stopPropagation();
        if(event.target == paper.canvas || event.target.nodeName == "INPUT"){ // input = radiobutton
            paper.forEach(function(el){
                if(el.type == "path"){
                    el.attr('stroke', "#000000").attr('stroke-width',1).attr('fill-opacity', 1);
                }
            });

            activeRegion = false;
        }
    }


    function initTextValues(){
        paper.forEach(function (el) {
            var value =  el.data('value');
            el.attr('text', value);

        });

    }

    function activateRegions(){
        paper.forEach(function (el) {
            el.click(activeElementHandler);

        });

        $(paper.canvas).on('click',resetElements);

    }

    function deactivateRegions(){
        paper.forEach(function (el) {
            el.unclick(activeElementHandler);
        });

        $(paper.canvas).off('click',resetElements);
    }

   function deactivateAllMouseClicks() {
        deactivateRegions();
        $(':button[name="NextPlayerSubmit"]').attr('disabled', 'disabled');
        //$('#actionContainer li.activeAction').removeClass('activeAction');
        $('#actionButton').attr('disabled', 'disabled');
        /*$('body').off('click','input:radio[name="bankstate"]', changeBankState);
        $('body').on('click','input:radio[name="bankstate"]', function(){
            return false;
        });*/
    }

    function activateAllMouseClicks() {
        activateRegions();
        $(':button[name="NextPlayerSubmit"]').removeAttr('disabled');
        $('#actionButton').removeAttr('disabled');
        $('#actionContainer li.activeAction').removeClass('activeAction');
        $('#actionContainer li').first().addClass('activeAction');
        //$('body').off('click','input:radio[name="bankstate"]');
        //$('body').on('click','input:radio[name="bankstate"]', changeBankState);

    }

    function highlightNeighbourRegions(regions){

        activeNeighbours = new Array();
        paper.forEach(function (el) {

            if(el.type == "path"){
                el.attr('fill-opacity', 0.3);
            }


            for(var i = 0; i < regions.neighbours.length; i++){

                if(el.data("region") == regions.neighbours[i] || el.data("region") == regions.activeRegion){
                    el.attr('fill-opacity', 1);
                    if(el.data("region") != regions.activeRegion){
                        activeNeighbours.push(el.data("region"));
                    }
                }
            }
        });
    }

    function addBasicCapitalToRegion(payment) {
        paper.forEach(function (el) {
            if(el.data("text") == payment.activeRegion){
                var value = payment.payment.value;
                el.attr('text', value);
                $('#'+payment.bankName).text(payment.bankCapital);
            }
        });
    }

    function sendNextPlayerRequest(){
        message_box.show_message('Startgeld verteilen: ', 'Nächster Spieler! ', false);
        deactivateAllMouseClicks();
        sendAjaxRequest("../states/PlayState.php", {handle: "PlayState", nextPlayer: "nextPlayer"}, false);
    }

    function updateMap(regionInfo){
        if(regionInfo.activeRegion.hasWon){

            paper.forEach(function(el){

                if(el.data('region') == regionInfo.enemyRegion.regionId){
                    el.data('regionOfPlayer', regionInfo.enemyRegion.regionOfPlayer);
                    el.attr('fill', regionInfo.enemyRegion.countryColor);
                }

                if(el.data('text') == regionInfo.enemyRegion.regionId){
                    el.attr('text', regionInfo.enemyRegion.payment);
                }

                if(el.data('text') == regionInfo.activeRegion.regionId){
                    el.attr('text', regionInfo.activeRegion.payment);
                }

            });

            $('#'+regionInfo.enemyBank.bankName).text(regionInfo.enemyBank.bankCapital);
        }
        else{
            paper.forEach(function(el){
                if(el.data('text') == regionInfo.activeRegion.regionId){
                    el.attr('text', regionInfo.activeRegion.payment)
                }
            });
        }
    }

    function updateRandomizedMap(regions){
        regionSet.forEach(function(el){
            var id = el.data('region');
            el.data('regionOfPlayer', regions[id].playerId);
            el.attr('fill', regions[id].regionColor);

            var text = textSet[id];
            text.attr('paymentValue', regions[id].payment);
        });
    }

    function checkPayoffRounds(actualRound){
        // first PAYOFF_ROUNDS "attack" and "deposit" are disabled
        if(actualRound <= <?php echo PAYOFF_ROUNDS ?>){
            disableActionPossibilities();
        }
        // after the PAYOFF_ROUNDS "attack" and "deposit" are enabled
        else if(actualRound == <?php echo PAYOFF_ROUNDS ?>+1){
            enableActionPossibilities();
            $('#actionButton').removeAttr('disabled');
        }
    }

    function disableActionPossibilities() {
        $('#deposit').attr("disabled",true);
        $('#attack').attr("disabled",true);
    }

    function enableActionPossibilities() {
        $('#deposit').attr("disabled",false);
        $('#attack').attr("disabled",false);
    }
    
    function renderIncidentInfo(incident){
        if(incident.type == "<?php echo GlobalRegionEvent::TYPE ?>"){

            paper.forEach(function(el){

                if(el.data('region') == incident.region.regionId){
                    el.attr('stroke', "#FF0000");
                }

                if(el.data('text') == incident.region.regionId){
                    el.attr('text', incident.region.payment);
                    //el.attr('text', incident.region.paymentValue * incident.region.currencyTranslation);
                }
            });
            $('#incidentView').text(incident.message + " region: " + incident.region.regionId);
        }
        else if(incident.type == "<?php echo GlobalBankEvent::TYPE ?>"){

            $('#'+incident.bankName).text(incident.bankCapital);
            $('#incidentView').text(incident.message + " bank: " + incident.bankName);
        }
        else if(incident.type == "<?php echo LocalIncidentEvent::TYPE ?>"){

            $('#'+incident.bankName).text(incident.bankCapital);
            $('#incidentView').text(incident.message + " " + incident.value + " "+ incident.currency);
        }
        $('#incidentView').show();
    }

    function updateInterests(interests){
        //var text = "";

        for(var i = 0; i < interests.length; i++){
            /*if(i > 0){
                text += " and ";
            }
            text += ('<span style="color:'+interests[i].color+'" >'+interests[i].countryName + '</span> bekam ' + interests[i].interest + ' Zinsen');
            */
            $('#'+interests[i].bankInterest).text("+"+interests[i].interest);
            $('#'+interests[i].bankName).text(interests[i].bankCapital);
        }



        //$('#interestInfo').html(text).fadeIn('slow', function(){});

    };


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
                initTextValues();
                checkPayoffRounds(0);
            }
        }
        if(settings.url.indexOf("randomizeMap")!= -1){
            //$('#content').html(xhr.responseText);
            updateRandomizedMap($.parseJSON(xhr.responseText));
        }
        if(settings.url.indexOf("getNeighbours")!= -1){
            var regions = $.parseJSON(xhr.responseText);
            highlightNeighbourRegions(regions);
        }
        if(settings.url.indexOf("getCountry")!= -1 ){ // || settings.url.indexOf("nextPlayer")!= -1
            var payment = $.parseJSON(xhr.responseText);
            var spentAction = ((payment.action=="payOff") ? " hat Geld bekommen" : " hat Geld auf die Bank eingezahlt");

            addBasicCapitalToRegion(payment);

            var regionTitle = searchNameOfRegion(payment.activeRegion);

            displayAIinfo(regionTitle + spentAction , false);

            if(payment.numberOfHumanPayoffs == 0){
                sendNextPlayerRequest();
            }

        }

        if((settings.url.indexOf("region")!= -1 && settings.url.indexOf("enemy")!= -1 )){ //|| settings.url.indexOf("nextPlayer")!= -1
            var regionInfo = $.parseJSON(xhr.responseText);
            updateMap(regionInfo);

            var regionTitle = searchNameOfRegion(regionInfo.enemyRegion.regionId);

            showCalculationBox(regionInfo);

            var hasBought = ((!regionInfo.activeRegion.hasWon) ? " nicht kaufen" : " kaufen");

            displayAIinfo(regionInfo.playerCountry + ' konnte ' + regionTitle + hasBought,  false);
           // message_box.show_message('Kaufvertrag ', regionInfo.playerCountry + ' konnte ' + regionTitle + hasBought,  false);
        }


        if(settings.url.indexOf("nextPlayer")!= -1 || settings.url.indexOf("newAIRequest")!= -1){
            //checks if the Human Player hast Lost
            if($.parseJSON(xhr.responseText).humanLost){
                message_box.show_message('Leider Verloren', "Alle Regionen wurden dir abgekauft!", true, true);
            }

            if($.parseJSON(xhr.responseText).attackCountry){
                var regionInfo = $.parseJSON(xhr.responseText);
                /*message_box.show_message('KI ' + regionInfo.playerId + ': ', regionInfo.activeRegion.regionId + ' attacked '
                    + regionInfo.enemyRegion.regionId + " and has won: " + regionInfo.activeRegion.hasWon,  true);*/
                var hasBought = ((!regionInfo.activeRegion.hasWon) ? " nicht kaufen" : " kaufen");
                var regionTitle = searchNameOfRegion(regionInfo.enemyRegion.regionId);

                //showCalculationBox(regionInfo);

                displayAIinfo(regionInfo.playerCountry + ' konnte ' + regionTitle + hasBought,  true);
                updateMap(regionInfo);
            }
            else if($.parseJSON(xhr.responseText).spendMoney){
                var payment = $.parseJSON(xhr.responseText);
                var spentAction = ((payment.action=="payOff") ? " hat Geld bekommen" : " hat Geld auf die Bank eingezahlt");
                var regionTitle = searchNameOfRegion(payment.activeRegion);

                displayAIinfo(regionTitle + spentAction , true);
                //message_box.show_message('KI ' + payment.playerId + ': ', payment.activeRegion + ' spent money '+ payment.action , true);
                addBasicCapitalToRegion(payment);
            }
            else if($.parseJSON(xhr.responseText).nextPlayer){
                var nextPlayer = $.parseJSON(xhr.responseText);
                //message_box.show_message('KI: ', 'Swiched Player to PlayerId ' + nextPlayer.nextPlayerId , true);
                paper.forEach(function(el){

                   if(el.type == "path"){
                       el.attr("stroke-width", 1);
                   }
                });
                displayAIinfo(nextPlayer.playerCountry + ' ist dran' , true);
            }
            else if($.parseJSON(xhr.responseText).humanPlayer){
                var humanPlayer = $.parseJSON(xhr.responseText);

                paper.forEach(function(el){

                   if(el.type == "path"){
                       el.attr("stroke-width", 1);
                   }
                });
                message_box.show_message('Info: ', 'Du bist an der Reihe! ', false);
                activateAllMouseClicks();
                //displayAIinfo('Info: ', 'Your turn! ', false);
            }

            var incident =  $.parseJSON(xhr.responseText);
            if(incident.incident){
                 renderIncidentInfo(incident.incident);
            }

            var interestList = $.parseJSON(xhr.responseText);
            if(interestList.interests){
                updateInterests(interestList.interests);
            }

            if($.parseJSON(xhr.responseText).round){
                var actualRound = $.parseJSON(xhr.responseText);
                checkPayoffRounds(actualRound.round);
            }
        }

    });

    function searchNameOfRegion(regionId){
        var regionTitle;
        paper.forEach(function(el){

           if(el.type == "path"){
               el.attr("stroke-width", 1);
               if(el.data('region') == regionId){
                   regionTitle = el.attr('title');
                   el.attr("stroke-width", 3);
                 el.toFront();
                 paper.getById(el.id+1).toFront();
               }
           }
        });

        return regionTitle;
    }

    function showCalculationBox(regionInfo){
        var hasBoughtTitle = ((!regionInfo.activeRegion.hasWon) ? "VERLOREN" : "GEWONNEN");


        message_box.show_message(hasBoughtTitle, "( " + regionInfo.calculation.originalPayment + " - " + " 2 * "
                 + regionInfo.calculation.basicCapital + " " + regionInfo.calculation.ownCurrency + " )"
                 + " * "  + regionInfo.calculation.translation + " * " + regionInfo.activeRegion.ventureValue
                 + " = " + regionInfo.calculation.calculation + " " + regionInfo.calculation.enemyCurrency,  false);

        /*message_box.show_message(hasBoughtTitle, regionInfo.calculation.originalPayment + " * "
            + regionInfo.calculation.translation + " * " + regionInfo.activeRegion.ventureValue
            + " = " + regionInfo.calculation.calculation + " " + regionInfo.calculation.enemyCurrency,  false);*/
    }

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

function displayAIinfo(body, request){

    $('#infoAI').prepend('<li></li>');

    $('#infoAI li').first().hide();

    $('#infoAI li:hidden').text(body).fadeIn(1000, 'swing', function(){
        if(request){
            sendAjaxRequest("../states/PlayState.php", {handle: "PlayState", newAIRequest:"newAIRequest"}, false);
        }
    });

}

function sendNewAIRequest(){
    message_box.close_message();
    sendAjaxRequest("../states/PlayState.php", {handle: "PlayState", newAIRequest:"newAIRequest"}, false);
}

var message_box = function() {
	var button_request = '<input type="button" onclick="sendNewAIRequest();" value="Ok" />';
    var button_close = '<input type="button" onclick="message_box.close_message();" value="Ok" />'; //onclick="message_box.close_message();"
	return {
		show_message: function(title, body, request, menu) {

            //if request needed --> button sends request, else only close window
            var button = request ? button_request : button_close;

            if(menu){
                // Check if game is over
                button = '<input type="button" onclick="window.location.reload()" value="Zum Menü" />';
            }
			if(jQuery('#message_box').html() === null) {
				var message = '<div id="message_box"><h1>' + title + '</h1>' + body + '<br/>' + button + '</div>';
				jQuery(document.body).append( message );
				jQuery(document.body).append( '<div id="darkbg"></div>' );
				jQuery('#darkbg').show();
				jQuery('#darkbg').css('height', jQuery(document).height());

				jQuery('#message_box').css('top', 150);
				jQuery('#message_box').show(100);
			} else {
				var message = '<h1>' + title + '</h1>' + body + '<br/>' + button;
				jQuery('#darkbg').show();
				jQuery('#darkbg').css('height', jQuery(document).height());

				jQuery('#message_box').css('top', 150);
				jQuery('#message_box').show(100);
				jQuery('#message_box').html( message );
			}
		},
		close_message: function() {
			jQuery('#message_box').hide(100);
			jQuery('#darkbg').hide();
		}
	}
}();

</script>
<?php
}

?>