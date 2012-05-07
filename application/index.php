<?php
require_once("../config/config.php");
require_once("../scripts/script.php");
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Hallo IslandshoppAA!!</title>
    <script type="text/javascript" src="../scripts/jquery-1.7.2.js"></script>
    <link rel="stylesheet" type="text/css" href="../styles/style.css" />
    <?php printJavaScript(); ?>
    <!--<script type="text/javascript" src="../scripts/script.js" language="javascript" ></script>-->
</head>
<body>
<h1> Hallo IslandshoppAA!! </h1>
<?php
$gameApp = new GameApplication();
?>

</body>
</html>