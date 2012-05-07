<?php
define("SITE_TITLE", "Island(S)hopping");

//Countries
define("PLAYER_VALUE", 1);
define("ENEMY_VALUE", -1);
function getCountriesArray()
{
   return array
   (
       "EU" => PLAYER_VALUE,
       "Schweiz" => ENEMY_VALUE,
       "Tuerkei" => 0,
       "SaudiArabien" => 0,
       "USA"=> 0
   );
}

define("NUM_OF_REGIONS", 70);

function __autoload($classname) {
  static $classes = array ('AjaxResponse' => '../ajax/AjaxResponse.php',

                           'GameApplication' => '../application/GameApplication.php',

                            'ICountry' => '../countries/ICountry.php',
                            'Country' => '../countries/Country.php',

                           'GameEventManager' => '../eventmanagement/GameEventManager.php',
                           'IEventListener' => '../eventmanagement/IEventListener.php',
                           'IEventManager' => '../eventmanagement/IEventManager.php',

                           'IEvent' => '../events/IEvent.php',
                           'UpdateViewEvent' => '../events/UpdateViewEvent.php',
                           'ChangeViewEvent' => '../events/ChangeViewEvent.php',
                           'ChangeStateEvent' => '../events/ChangeStateEvent.php',

                           'Map' => '../map/Map.php',
                           'Region' => '../map/Region.php',
                           'RegionGraph' => '../map/RegionGraph.php',

                            'IPayment' => '../payments/IPayment.php',

                            'IPlayer' => '../players/IPlayer.php',
                            'HumanPlayer' => '../players/HumanPlayer.php',
                            'ArtificialIntelligence' => '../players/ArtificialIntelligence.php',

                            'EndOfPlayState' => '../states/EndOfPlayState.php',
                            'IApplicationState' => '../states/IApplicationState.php',
                            'MapState' => '../states/MapState.php',
                            'MenuState' => '../states/MenuState.php',
                            'PlayState' => '../states/PlayState.php',
                            'PreloaderState' => '../states/PreloaderState.php',

                            'IStateView' => '../views/IStateView.php',
                            'MapStateView' => '../views/MapStateView.php',
                            'MenuStateView' => '../views/MenuStateView.php',

                             'islandMapView' => '../views/islandViews/islandMapView.sgv'


                            );

  if (!isset($classes[$classname])) return false;

  require_once $classes[$classname];
}

?>