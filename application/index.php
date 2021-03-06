<?php
require_once("../config/config.php");
require_once("../scripts/script.php");

if(isset($_SESSION)){
    session_destroy();
}

session_start();
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"],
        $params["domain"], $params["secure"], $params["httponly"]
    );
}

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
<?php
$gameApp = new GameApplication();
?>
<div class="ajaxSuccess"></div>
</body>
</html>