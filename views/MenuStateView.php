<?php
    require_once("config.php");
    require_once("./states/MenuState.php");
    require_once("./events/UpdateViewEvent.php");
    require_once("./eventmanagement/IEventListener.php");
    require_once("./eventmanagement/GameEventManager.php");
/*
function __autoload($class) {
    include_once $class . ".php";
}*/

class MenuStateView implements IEventListener {

    public function __construct(){
        GameEventManager::getInstance()->addEventListener($this, UpdateViewEvent::TYPE);
    }


    function handleEvent(IEvent $event)
    {
        if($event->getEventType() == UpdateViewEvent::TYPE){



        }
    }

    function printForm(){?>
        <form name="menuForm1" method="POST" action="/MapStateView.php">
            <h2>Welches Land m&ouml;chtest du sein ?</h2>
            <input type="checkbox" name="testname" id="testid" />
            <?php
           $status = "";
            $all_countries_array = getCountriesArray();

            $playerCountry = $all_countries_array["EU"];

           foreach($all_countries_array as $name => $country) {

               if ($playerCountry == $country) {
                $status = 'checked="checked"';
               }

               ?>
               <label>
                 <input type='radio' name='playerCountry' value="<?php echo $country ?>" <?php echo $status ?> />
                 <?php echo $name ?>
               </label>
               <?php
               $status = "";
           }
            ?>

            <h2 id="oida">W&auml;hle deine Gegner</h2>
       <?php /*
           $checked = "unchecked";

               //If PlayerCountry is in the List of EnemyCountries, it will be deleted
               if(in_array($playerCountry,$otherCountries)){
                   foreach($otherCountries as $name => $search){
                       if($search == $playerCountry){
                           unset($otherCountries[$name]);
                       }
                   }
               }


          // $checked = "unchecked";
           foreach($all_countries_array as $name => $country) {
               if ($playerCountry != $country) {
                   if(in_array($country,$otherCountries)) $checked="checked";

                   echo "<input type='checkbox' name='otherCountries[]' value=$country " .$checked. ">&nbsp;$country&nbsp;&nbsp;";
                   $checked = "unchecked";
               }
           } */
       ?>
           <br /><br />
       <!--    <input type="button" name="Change" value="Take changes" onclick="--><?php //echo $_SERVER['PHP_SELF']; ?><!--">-->
           <input type="submit" name="Submit" value="Start">
       </form>
    <?php
    }
}
/*
 <form name="menuForm1" method="POST" action="
//echo $_SERVER['PHP_SELF']; ?>">
action="../States/MenuState.php" -->
                                                                                  //echo $_SERVER['PHP_SELF'];




    echo"<br /><br /> Player: ";
    echo $playerCountry;

    echo"<br /><br /> Gegner: ";
    foreach($otherCountries as $name => $country) {
        echo $country." ";
    }*/
?>

