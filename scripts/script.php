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

    $('input:checkbox').click(function(event){

        alert($(this).parent().text());
        //alert(this);

        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "ajaxTest", enemycountry: $(this).parent().text(), <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
        }
    });

    $('input:radio').click(function(event) {
        alert($(this).parent().text());

        if($('input:checked').length > 0){
            sendAjaxRequest("../states/MenuState.php", {handle: "ajaxTest", playercountry: $(this).parent().text(), <?php  echo session_name().': '.'"'.session_id().'"'; ?>});
        }
    });

});
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