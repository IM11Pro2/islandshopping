<?php

require_once("../config/config.php");
//require_once("GameApplication.php");
//require_once("./ajax/AjaxResponse.php");

/*if(isset($_GET['id'])){
   ajaxResponse::getResponse();
    $id = $_GET['id'];

        $message = "You got an AJAX response via JSONP from another site!";

        $a = array('id' => $id, 'message'=>$message);
        echo $_GET['callback']. '('. json_encode($a) . ')';
    exit;
}*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Hallo IslandshoppAA!!</title>
    <script type="text/javascript" src="../scripts/jquery-1.7.2.js" language="javascript" ></script>
    <script type="text/javascript" src="../scripts/script.js" language="javascript" ></script>
</head>
<body>
<h1> Hallo IslandshoppAA!! </h1>
<?php
$gameApp = new GameApplication();

/*
if(isset($_GET['id'])){
   $a = new AjaxResponse();
    $a->getResponse();

}

function ajaxTest(){
    header("content-type: application/json");

    if(isset($_GET['id'])) $id = $_GET['id'];



        $message = "You got an AJAX response via JSONP from another site!";

        $a = array('id' => $id, 'message'=>$message);
    echo $_GET['callback']. '('. json_encode($a) . ')';

}
*/

?>

</body>
</html>