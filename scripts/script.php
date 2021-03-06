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

    var activeNeighbours = undefined;
    var activeRegion = false;

    $('input:checkbox[name="enemyCountries[]"]').click(function(event){
        if($('input:checkbox[name="enemyCountries[]"]:checked').length > 0){
            $('#menuSubmit').removeAttr('disabled');
            sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", enemycountry: $(this).parent().text()}, false);
        }
        else{
            $('#menuSubmit').attr('disabled', 'disabled');
            sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", enemycountry: $(this).parent().text()}, false);
        }
    });

    $('input:radio[name="playerCountry"]').click(function(event) {

        var clickedElementLabel = $.trim($(this).parent().text());

        $.each($('input:checkbox[name="enemyCountries[]"]:checked'), function(index){

            if($.trim($(this).parent().text()) == clickedElementLabel && $('input:checkbox[name="enemyCountries[]"]:checked').length == 1){
                $('#menuSubmit').attr('disabled', 'disabled');
            }
        });

        if($('input:checked').length > 0){
            console.log($('input:checked').length);
            sendAjaxRequest("../states/MenuState.php", {handle: "MenuState", playercountry: $(this).parent().text()}, false);
        }
    });


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

    function bringElementToFront(lastElement, element){

        lastElement.node.parentNode.parentNode.insertBefore(element.node.parentNode, lastElement.node.parentNode.nextSibling);
        if(element.type == "path"){
            path = element;
        }

    }

    function activeElementHandler(){

        var activeElement = this;

        if(activeElement.type == "text"){
            /*paper.forEach(function(el){

               if(el.type == "path"){

                   if(el.data('region') == activeElement.data('text')){
                       activeElement = el;
                   }

               }
            });*/
            activeElement = paper.getById(activeElement.data('pathId'));
        }

        if(activeElement.data('regionOfPlayer') == 0){

            paper.forEach(function (el) {
                el.attr('stroke-width', 2);
            });

            activeElement.attr('stroke-width', 4);
            /*activeElement.toFront();
            path = activeElement;
            paper.getById((activeElement.id+1)).toFront();*/

            bringElementToFront(path, activeElement);
            textSet.items[activeElement.data('region')].toFront();

            var regionId = activeElement.data('region');
            activeRegionId = regionId;

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

    function resetTextElement(textElement){
        textElement.unhover(hoverNeighbourElement, resetElement);
        textElement.attr('cursor', 'default');
    }

    function resetElements(event){
        event.stopPropagation();
        if(event.target == paper.canvas || event.target.nodeName == "INPUT"){ // input = radiobutton
            paper.forEach(function(el){
                if(el.type == "path"){
                    el.attr('stroke', "#ffffff").attr('stroke-width',2).attr('fill-opacity', 1);
                }
            });

            activeRegion = false;
        }
    }


    function initTextValues(){
        textSet.forEach(function (el) {
            var paymentValue =  el.data('paymentValue');
            el.attr('text', paymentValue);

        });

    }


    function hoverElement(el, text){



            el.attr('cursor', 'pointer');
            el.attr('stroke-width', 4);
            bringElementToFront(path, el);
            textSet.items[el.data('region')].attr('cursor', 'pointer').toFront();


    }

    function hoverPlayerElement(event){
        var el = this;
        if(this.type == "text"){
            el = paper.getById(this.data('pathId'));
        }
        if(el.data('regionOfPlayer') == 0){
            hoverElement(el, this);
        }
    }

    function hoverNeighbourElement(event){
        var el = this;
        if(this.type == "text"){
            el = paper.getById(this.data('pathId'));
        }
        if(el.data('regionOfPlayer') != 0){
            hoverElement(el, this);

        }
    }

    function resetElement(){
        var el = this;

        if(el.type == "text"){
            el.attr('cursor', 'default');
            el = paper.getById(el.data('pathId'));
        }
        el.attr({'stroke-width' : 2, 'cursor': 'default'});
    }

    function activateRegions(){
        paper.forEach(function (el) {
            el.click(activeElementHandler);
            el.hover(hoverPlayerElement, resetElement);
        });

        $(paper.canvas).on('click',resetElements);

    }

    function deactivateRegions(){
        paper.forEach(function (el) {
            el.unclick(activeElementHandler);
            el.unhover(hoverPlayerElement, resetElement);

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


    }

    function highlightNeighbourRegions(regions){

        if(activeNeighbours != undefined){
            for(var i = 0; i < activeNeighbours.length; ++i){
                var el = regionSet.items[activeNeighbours[i]];
                el.unhover(hoverNeighbourElement, resetElement);
                resetTextElement(textSet.items[activeNeighbours[i]])
            }
        }

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
                        el.hover(hoverNeighbourElement, resetElement);
                        textSet.items[activeNeighbours[i]].hover(hoverNeighbourElement, resetElement);
                    }
                }
            }
        });
    }

    function addBasicCapitalToRegion(payment) {
        /*paper.forEach(function (el) {
            if(el.data("text") == payment.activeRegion){
                var value = payment.payment.value;
                el.attr('text', value);
                $('#'+payment.bankName).text(payment.bankCapital);
            }
        });*/
        var textElement = textSet.items[payment.activeRegion];
        var paymentValue = payment.payment.value;
        textElement.attr('text', paymentValue);
        $('#'+payment.bankName).text(payment.bankCapital);
    }

    function sendNextPlayerRequest(){
        message_box.show_message('Startgeld verteilen: ', 'Nächster Spieler! ', false);
        deactivateAllMouseClicks();
        sendAjaxRequest("../states/PlayState.php", {handle: "PlayState", nextPlayer: "nextPlayer"}, false);
    }

    function updateMap(regionInfo){

        var textElement = textSet.items[regionInfo.activeRegion.regionId];
        textElement.attr('text', regionInfo.activeRegion.payment);
        if(regionInfo.activeRegion.hasWon){

            var region = regionSet.items[regionInfo.enemyRegion.regionId];
            region.data('regionOfPlayer', regionInfo.enemyRegion.regionOfPlayer);
            region.attr('fill', regionInfo.enemyRegion.countryColor);

            textElement = textSet.items[regionInfo.enemyRegion.regionId];
            textElement.attr('text', regionInfo.enemyRegion.payment);





            $('#'+regionInfo.enemyBank.bankName).text(regionInfo.enemyBank.bankCapital);
        }

    }

    function updateRandomizedMap(regions){
        regionSet.forEach(function(el){
            var id = el.data('region');
            el.data('regionOfPlayer', regions[id].playerId);
            el.attr('fill', regions[id].regionColor);

            var text = textSet.items[id];
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


            var region = regionSet.items[incident.region.regionId];
            region.attr('stroke', "#FF0000");

            var textElement = textSet.items[incident.region.regionId];
            textElement.attr('text', incident.region.payment);
            
            var regionTitle = searchNameOfRegion(incident.region.regionId);
            $('#incidentView').text(incident.message + regionTitle);
        }
        else if(incident.type == "<?php echo GlobalBankEvent::TYPE ?>"){

            $('#'+incident.bankName).text(incident.bankCapital);
            $('#incidentView').text(incident.country + incident.message);
        }
        else if(incident.type == "<?php echo LocalIncidentEvent::TYPE ?>"){

            $('#'+incident.bankName).text(incident.bankCapital);
            $('#incidentView').text(incident.message + " " + incident.value);//incident.payment);
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

            if(payment.updateInfo){
                displayAIinfo(regionTitle + spentAction , false);
            }

            if(payment.numberOfHumanPayoffs == 0){
                sendNextPlayerRequest();
            }

        }

        if((settings.url.indexOf("region")!= -1 && settings.url.indexOf("enemy")!= -1 )){ //|| settings.url.indexOf("nextPlayer")!= -1
            var regionInfo = $.parseJSON(xhr.responseText);
            updateMap(regionInfo);

            var regionTitle = searchNameOfRegion(regionInfo.enemyRegion.regionId);

            var hasBought = ((!regionInfo.activeRegion.hasWon) ? " nicht kaufen" : " kaufen");

            displayAIinfo(regionInfo.playerCountry + ' konnte ' + regionTitle + hasBought,  false);

            //prints if the Human Player has WON
            if(regionInfo.humanWon){
                message_box.show_message('DU HAST GEWONNEN', "Gratuliere! Alle Regionen gehören Dir!", true, true);
            }
            else {
                showCalculationBox(regionInfo);
            }
        }


        if(settings.url.indexOf("nextPlayer")!= -1 || settings.url.indexOf("newAIRequest")!= -1){
            //prints if the Human Player hast Lost
            if($.parseJSON(xhr.responseText).humanLost){
                message_box.show_message('Leider Verloren', "Alle Regionen wurden dir abgekauft!", true, true);
            }

            if($.parseJSON(xhr.responseText).attackCountry){
                var regionInfo = $.parseJSON(xhr.responseText);
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

                if(payment.updateInfo){
                    displayAIinfo(regionTitle + spentAction , true);
                }
                else {
                    sendNewAIRequest();
                }

                addBasicCapitalToRegion(payment);
            }
            else if($.parseJSON(xhr.responseText).nextPlayer){
                var nextPlayer = $.parseJSON(xhr.responseText);
                paper.forEach(function(el){

                   if(el.type == "path"){
                       el.attr("stroke-width", 2);
                   }
                });
                displayAIinfo(nextPlayer.playerCountry + ' ist dran' , true);
            }
            else if($.parseJSON(xhr.responseText).humanPlayer){
                var humanPlayer = $.parseJSON(xhr.responseText);

                paper.forEach(function(el){

                   if(el.type == "path"){
                       el.attr("stroke-width", 2);
                   }
                });
                message_box.show_message('Info: ', 'Du bist an der Reihe! ', false);
                activateAllMouseClicks();
                //displayAIinfo('Du bist an der Reihe!', false);
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

               el.attr("stroke-width", 2);

               if(el.data('region') == regionId){
                   regionTitle = el.attr('title');
                   el.attr("stroke-width", 4);
                   bringElementToFront(path, el);
                   textSet.items[el.data('region')].toFront();
               }
           }
        });

        return regionTitle;
    }

    function showCalculationBox(regionInfo){
        var hasBoughtTitle = ((!regionInfo.activeRegion.hasWon) ? "Nicht gekauft" : "Gekauft");
        var sign = ((!regionInfo.activeRegion.hasWon) ? "<=" : ">=");

        message_box.show_message(hasBoughtTitle, "Kapital: <div class='right'>" + regionInfo.calculation.originalPayment + "</div>"
            + "<br/>Spekulationswert: <div class='right'>" + " * " + regionInfo.activeRegion.ventureValue + "</div>"
            + "<br/>Grundkapital:  <div class='right'>" + " - 2 * " + regionInfo.calculation.basicCapital + " " + regionInfo.calculation.ownCurrency + "</div>"
            + "<br/><br/>Wechselkurs:  <div class='right'>" + " * " + regionInfo.calculation.translation + "</div>"
            + "<br/>____________________________________"
            + "<br/>Resultat:  <div class='right'>" + regionInfo.calculation.calculation + " " + regionInfo.calculation.enemyCurrency + "</div>"
            + "<br/><br/> <div class='center'>" + regionInfo.calculation.calculation + " " + regionInfo.calculation.enemyCurrency
            + "  "+ sign + "  " + regionInfo.enemyRegion.enemyOriginalPayment + "</div>",  false, false, true);


/*        message_box.show_message(hasBoughtTitle, "( " + regionInfo.calculation.originalPayment
            + " * " + regionInfo.activeRegion.ventureValue  + "  -  "
            + " 2 * " + regionInfo.calculation.basicCapital + " " + regionInfo.calculation.ownCurrency + " )"
            + " * " + regionInfo.calculation.translation
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

    var messageBoxId;

    return {
		show_message: function(title, body, request, menu, calculation) {

            //if request needed --> button sends request, else only close window
            var button = request ? button_request : button_close;

            if(menu){
                // Show if game is over
                button = '<input type="button" onclick="window.location.reload()" value="Zum Menü" />';
            }

            if(calculation){
                messageBoxId = 'calculation_box';
            }
            else {
                messageBoxId = 'message_box';
            }

			if(jQuery('#'+messageBoxId).html() === null) {
				var message = '<div id=' + messageBoxId + '><h1>' + title + '</h1><div class="body">' + body + '</div><br/>' + button + '</div>';
				jQuery(document.body).append( message );
				jQuery(document.body).append( '<div id="darkbg"></div>' );
				jQuery('#darkbg').show();
				jQuery('#darkbg').css('height', jQuery(document).height());

				jQuery('#'+messageBoxId).css('top', 150);
				jQuery('#'+messageBoxId).show(100);
			} else {
				var message = '<h1>' + title + '</h1>' + '</h1><div class="body">' + body + '</div><br/>' + button;
				jQuery('#darkbg').show();
				jQuery('#darkbg').css('height', jQuery(document).height());

				jQuery('#'+messageBoxId).css('top', 150);
				jQuery('#'+messageBoxId).show(100);
				jQuery('#'+messageBoxId).html( message );
			}
		},
		close_message: function() {
			jQuery('#'+messageBoxId).hide(100);
			jQuery('#darkbg').hide();
		}
	}
}();

</script>
<?php
}

?>