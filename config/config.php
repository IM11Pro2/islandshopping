<?php
define("SITE_TITLE", "Island(S)hopping");
//$all_countries_array = array(0 => "EU", 1 =>"Schweiz", 2 =>"Tuerkei", 3 =>"SaudiArabien", 4 =>"USA");

//Countries
//define("COUNTRIES_ARRAY", array(0 => "EU", 1 =>"Schweiz", 2 =>"Türkei", 3 =>"Saudi Arabien", 4 =>"USA"));
define("PLAYER_VALUE", 1);
define("ENEMY_VALUE", -1);
function getCountriesArray()
{
   return array
   (
       "EU" => PLAYER_VALUE,
       "Schweiz" => ENEMY_VALUE,
       "T&uuml;rkei" => 0,
       "Saudi Arabien" => 0,
       "USA"=> 0
   );
}

define("NUM_OF_REGIONS", 70);

function __autoload($classname) {
  static $classes = array ('AjaxResponse' => '../ajax/AjaxResponse.php',

                           'GameApplication' => '../application/GameApplication.php',

                           'GameEventManager' => '../eventmanagement/GameEventManager.php',
                           'IEventListener' => '../eventmanagement/IEventListener.php',
                           'IEventManager' => '../eventmanagement/IEventManager.php',

                           'IEvent' => '../events/IEvent.php',
                           'UpdateViewEvent' => '../events/UpdateViewEvent.php',

                           'Map' => '../map/Map.php',
                           'Region' => '../map/Region.php',
                           'RegionGraph' => '../map/RegionGraph.php',

                           'EndOfPlayState' => '../states/EndOfPlayState.php',
                           'IApplicationState' => '../states/IApplicationState.php',
                           'MapState' => '../states/MapState.php',
                           'MenuState' => '../states/MenuState.php',
                           'PlayState' => '../states/PlayState.php',
                           'PreloaderState' => '../states/PreloaderState.php',

                           'IStateView' => '../views/IStateView.php',
                           'MapStateView' => '../views/MapStateView.php',
                           'MenuStateView' => '../views/MenuStateView.php'

                            );

  if (!isset($classes[$classname])) return false;

  require_once $classes[$classname];
}

?>