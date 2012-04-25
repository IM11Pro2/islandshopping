<?php
//require_once("../config.php");
//require_once("../states/MapState.php");
?>

<h1>MAP STATE VIEW</h1>

<?php
if (isset($_POST['playerCountry'])){
    $playerCountry = $_POST['playerCountry'];
    echo "Player: " . $playerCountry;
}
if (isset($_POST['otherCountries'])){
    $otherCountries = $_POST['otherCountries'];
    echo"<br /><br /> Gegner: ";
        foreach($otherCountries as $name => $country) {
            echo $country." ";
        }
}

//for($i=0, $i<$otherCo)




?>

