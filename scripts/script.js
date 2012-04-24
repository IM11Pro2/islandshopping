$(document).ready(function(){
    $('input:checkbox').click(function(){

        if($('input:checked').length > 0){
            $('#oida').text("on");
            /*$.getJSON(

                "./index.php",

                {id : "ajaxTest"},

                function(data) {
                    alert(data);
                    $('#oida').text(data);
                }
              );
                */

           /* $.getJSON("./ajax/ajaxResponse.php?callback=?", {id: "ajaxTest"},  function(data) {
                alert(data);
             }); */

            $.ajax({
                url: "./states/MenuState.php",
                data: {handle: "ajaxTest"},
                dataType: "jsonp",
                contentType: 'application/json',
                jsonp : "callback",
                jsonpCallback: "jsonpcallback"
               // success: function(data){
                 //   alert("yess");
                   // jsonpcallback(data);
                //}
            });

        }
        else{
            $('#oida').text("off");
            // TODO: Ajax
        }
    });
});
function jsonpcallback(data) {
    alert(data["message"]);
}