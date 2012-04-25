$(document).ready(function(){

    function sendAjaxRequest(url, data){
        $.ajax({
            url: url,
            data: data,
            dataType: "jsonp",
            contentType: 'application/json',
            jsonp : "callback",
            jsonpCallback: "jsonpcallback"
        });
    }

    $('input:checkbox').click(function(event){

        alert($(this).parent().text());

        if($('input:checked').length > 0){

            sendAjaxRequest("../states/MenuState.php", {handle: "ajaxTest"});
        }
    });



});
function jsonpcallback(data) {
    alert(data["message"]);
}