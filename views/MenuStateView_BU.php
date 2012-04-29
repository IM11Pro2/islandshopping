<?php
    require_once("../config.php");
    require_once("../states/MenuState.php");
    require_once("../events/UpdateViewEvent.php");
    require_once("../eventmanagement/IEventListener.php");
    require_once("../eventmanagement/GameEventManager.php");

   // echo "<h1>Seitentitel: ". SITE_TITLE."</h1>";
    echo "<h1>MenuStateView</h1>";
    //print_r($all_countries_array);

class MenuStateView_BU implements IEventListener {

    public function __construct(){
        GameEventManager::getInstance().addEventLisener($this, UpdateViewEvent::TYPE);
    }


    function handleEvent(IEvent $event)
    {
        if($event.getEventType() == UpdateViewEvent::TYPE){

        }
    }
}
?>
<!-- <form name="menuForm1" method="POST" action="<?php //echo $_SERVER['PHP_SELF']; ?>"> -->
<form name="menuForm1" method="POST" action="/MapStateView.php"> <!--action="../States/MenuState.php" -->
                                                                                   <?php //echo $_SERVER['PHP_SELF']; ?>

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

<!--    <input type="button" name="Change" value="Take changes" onclick="--><?php //echo $_SERVER['PHP_SELF']; ?><!--">-->

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

