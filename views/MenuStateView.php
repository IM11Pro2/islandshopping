<?php
   /* require_once("config.php");
    require_once("./states/MenuState.php");
    require_once("./events/UpdateViewEvent.php");
    require_once("./eventmanagement/IEventListener.php");
    require_once("./eventmanagement/GameEventManager.php");*/
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

            echo "event gekommt<br />";

        }
    }

    function printForm(){?>
        <form name="menuForm1" method="POST" action="/MapStateView.php">
            <h2>Welches Land m&ouml;chtest du sein ?</h2>
           <!-- <input type="checkbox" name="testname" id="testid" /> -->
            <?php

            $status = "";
            $all_countries_array = getCountriesArray();


           foreach($all_countries_array as $country => $value) {

               if (PLAYER_VALUE == $value) {
                $status = 'checked="checked"';
               }

               ?>
               <label><input type='radio' name='playerCountry' value="<?php echo $value ?>" <?php echo $status ?> /><?php echo $country ?></label>
               <?php
               $status = "";
           }

            ?>

            <h2>W&auml;hle deine Gegner</h2>

       <?php


               //If PlayerCountry is in the List of EnemyCountries, it will be deleted
               /*if(in_array($playerCountry,$otherCountries)){
                   foreach($otherCountries as $name => $search){
                       if($search == $playerCountry){
                           unset($otherCountries[$name]);
                       }
                   }
               }*/

           //$checked = "unchecked";
           $status = "";
           foreach($all_countries_array as $country => $value) {
               //if (PLAYER_VALUE != $value) {
                  if(ENEMY_VALUE == $value){
                      $status='checked="checked"';
                  }

                   if (PLAYER_VALUE == $value){
                       $status =  $status.' disabled="disabled"';
                   }
                   ?>
                  <label><input type="checkbox" name="enemyCountries[]" value="<?php echo $value ?>" id="enemy_<?php echo $country ?>" <?php echo $status ?> /><?php echo $country ?></label>
                  <?php
                   $status = "";
               }

           //}
       ?>
           <br /><br />
            <div class="log"></div>
       <!--    <input type="button" name="Change" value="Take changes" onclick="--><?php //echo $_SERVER['PHP_SELF']; ?><!--">-->
           <input type="submit" name="Submit" value="Start">
       </form>
    <?php
    }
}

echo"<br /><br /> Player: ";
//echo MenuState::$getPlayerCountry;
/*
 <form name="menuForm1" method="POST" action="
//echo $_SERVER['PHP_SELF']; ?>">
action="../states/MenuState.php" -->
                                                                                  //echo $_SERVER['PHP_SELF'];




    echo"<br /><br /> Player: ";
    echo $playerCountry;

    echo"<br /><br /> Gegner: ";
    foreach($otherCountries as $name => $country) {
        echo $country." ";
    }*/
?>

