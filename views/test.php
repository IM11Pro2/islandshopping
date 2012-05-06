<?php
    require_once("../config.php");
    require_once("../states/MenuState.php");

    echo "<br>Seitentitel " . SITE_TITLE . "<br><br>";
    //print_r($all_countries_array);
    $playerCountry;
?>

<form name="menuForm1" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <!--action="../States/MenuState.php" -->

    <h2>Welches Land m&ouml;chtest du sein ?</h2>

    <?php
    //    foreach($all_countries_array as $name => $wert) {
    //        echo "<input type='radio' name='playerCountry' value=$wert>&nbsp;$wert&nbsp;&nbsp;";
    //    }

    if(isset($_POST['Submit1'])) {
        $playerCountry = $_POST['playerCountry'];
    }
    else $playerCountry = $all_countries_array[0];

    $status = "unchecked";
    foreach($all_countries_array as $name => $wert) {

        if($playerCountry == $wert) {
            $status = 'checked';
        }

        echo "<input type='radio' name='playerCountry' value=$wert " . $status . " >&nbsp;$wert&nbsp;&nbsp;";
        $status = "unchecked";
    }
    ?>
    <input type="submit" name="Submit1" value="Send">
</form>

<form name="menuForm2" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <h2>W&auml;hle deine Gegner</h2>

    <?php
    if(isset($_POST['Submit2']) && isset($_POST['otherCountries'])) {
        $otherCountries = $_POST['otherCountries'];
    }
    else $otherCountries = array();

    $checked = "unchecked";
    foreach($all_countries_array as $name => $wert) {
        if($playerCountry != $wert) {

            if(in_array($wert, $otherCountries))
                $checked = "checked";

            echo "<input type='checkbox' name='otherCountries[]' value=$wert " . $checked . ">&nbsp;$wert&nbsp;&nbsp;";
            $checked = "unchecked";
        }
    }
    ?>
    <input type="submit" name="Submit2" value="Send">
</form>

<?php
    echo"<br /><br /> Player: ";
    echo $playerCountry;

    echo"<br /><br /> Gegner: ";
    foreach($otherCountries as $name => $wert) {
        echo $wert . " ";
    }
?>