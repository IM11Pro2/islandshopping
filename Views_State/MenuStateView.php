<?php
    require_once("../config.php");
    require_once("../States/MenuState.php");

    echo "<br>Seitentitel ". SITE_TITLE."<br><br>";
    //print_r($all_countries_array);
?>

<form name="menuForm1" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"> <!--action="../States/MenuState.php" -->

<h2>Welches Land m&ouml;chtest du sein ?</h2>

<?php
    if (isset($_POST['Submit'])) {
        $playerCountry = $_POST['playerCountry'];
    }
    else $playerCountry = $all_countries_array[0];

        $status = "unchecked";
        foreach($all_countries_array as $name => $country) {

            if ($playerCountry == $country) {
             $status = 'checked';
            }

            echo "<input type='radio' name='playerCountry' value=$country " .$status. " >&nbsp;$country&nbsp;&nbsp;";
            $status = "unchecked";
        }
?>

<h2>W&auml;hle deine Gegner</h2>

<?php
    $checked = "unchecked";

    if (isset($_POST['Submit']) && isset($_POST['otherCountries'])) {
        $otherCountries = $_POST['otherCountries'];

        //If PlayerCountry is in the List of EnemyCountries, it will be deleted
        if(in_array($playerCountry,$otherCountries)){
            foreach($otherCountries as $name => $search){
                if($search == $playerCountry){
                    unset($otherCountries[$name]);
                }
            }
        }
    }
    else {
        $otherCountries = array();
        $checked ="checked"; // nur dass man sofort starten kann und min. 1 gegner gewählt wurde
    }

   // $checked = "unchecked";
    foreach($all_countries_array as $name => $country) {
        if ($playerCountry != $country) {
            if(in_array($country,$otherCountries)) $checked="checked";

            echo "<input type='checkbox' name='otherCountries[]' value=$country " .$checked. ">&nbsp;$country&nbsp;&nbsp;";
            $checked = "unchecked";
        }
    }
?>
    <br /><br />

    <input type="submit" name="Submit" value="Start">
</form>

<?php
    echo"<br /><br /> Player: ";
    echo $playerCountry;

    echo"<br /><br /> Gegner: ";
    foreach($otherCountries as $name => $country) {
        echo $country." ";
    }
?>

