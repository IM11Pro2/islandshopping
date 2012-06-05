<?php
require_once("../config/config.php");
require_once("../scripts/script.php");

session_start();
$_SESSION = array();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Welcome Islandshopper!!</title>
    <script type="text/javascript" src="../scripts/raphael.js"></script>
    <script type="text/javascript" src="../scripts/jquery-1.7.2.js"></script>
    <link rel="stylesheet" type="text/css" href="../styles/style.css" />
    <?php printJavaScript(); ?>
</head>
<body>
<h2> Islandshopping v0.9 </h2>
<?php
$gameApp = new GameApplication();
?>
<div class="ajaxSuccess"></div>
</body>
</html>