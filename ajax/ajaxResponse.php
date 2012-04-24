<?php
/*
if(isset($_GET['id'])){
    header("content-type: application/json");
} */

if(isset($_GET['id'])){

/* $id = $_GET['id'];

    $message = "You got an AJAX response via JSONP from another site!";

    $a = array('id' => $id, 'message'=>$message);
    echo $_GET['callback']. '('. json_encode($a) . ')';*/
    $a = new AjaxResponse();
    $a->getResponse();
}

?>

<?php
    class AjaxResponse{

       public static function getResponse(){
           header("content-type: application/json");

            if(isset($_GET['id'])) $id = $_GET['id'];



                $message = "You got an AJAX response via JSONP from another site!";

                $a = array('id' => $id, 'message'=>$message);
            echo $_GET['callback']. '('. json_encode($a) . ')';
        }

    }
?>

