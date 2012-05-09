<?php
    define("SITE_TITLE", "Island(S)hopping");

    //Countries
    define("PLAYER_VALUE", 1);
    define("ENEMY_VALUE", -1);
    function getCountriesArray() {
        return array("EU"           => PLAYER_VALUE,
                     "Schweiz"      => ENEMY_VALUE,
                     "Tuerkei"      => 0,
                     "SaudiArabien" => 0,
                     "USA"          => 0);
    }

    // Currencies
    function getCurrencies() {
        return array( //1. Stelle, Währung, 2.Stelle Wechselkurs
            "EU"           => array("Euro", 1.1),
            "Schweiz"      => array("Franken", 2.2),
            "Tuerkei"      => array("Lira", 3.3),
            "SaudiArabien" => array("Rial", 4.4),
            "USA"          => array("Dollar", 5.5));
    }

    //Colors
    function getColorArray() {
        return array("#FF0000", "#00FF00", "#0000FF", "#FFFF00 ", "#A901DB");
    }

    //Number of Regions
    define("NUM_OF_REGIONS", 12);

    //Amount of StartMoney per Country
    define("START_CAPITAL_COUNTRY", 1000.0);

    //Basic Capital per Region
    define("BASIC_CAPITAL_REGION", 50.0);

    //Autoload
    function __autoload($classname) {
        static $classes = array('AjaxResponse'                => '../ajax/AjaxResponse.php',

                                'GameApplication'             => '../application/GameApplication.php',

                                'Bank'                        => '../bank/Bank.php',
                                'IBankState'                  => '../bank/IBankState.php',
                                'PayOffState'                 => '../bank/PayOffState.php',
                                'DepositState'                => '../bank/DepositState.php',
                                'AttackState'                 => '../bank/AttackState.php',

                                'ICountry'                    => '../countries/ICountry.php',
                                'Country'                     => '../countries/Country.php',

                                'GameEventManager'            => '../eventmanagement/GameEventManager.php',
                                'IEventListener'              => '../eventmanagement/IEventListener.php',
                                'IEventManager'               => '../eventmanagement/IEventManager.php',

                                'IEvent'                      => '../events/IEvent.php',
                                'UpdateViewEvent'             => '../events/UpdateViewEvent.php',
                                'ChangeViewEvent'             => '../events/ChangeViewEvent.php',
                                'ChangeStateEvent'            => '../events/ChangeStateEvent.php',

                                'Map'                         => '../map/Map.php',
                                'Region'                      => '../map/Region.php',
                                'RegionGraph'                 => '../map/RegionGraph.php',
                                'GraphNode'                 => '../map/RegionGraph.php',

                                'IPayment'                    => '../payments/IPayment.php',
                                'Payment'                     => '../payments/Payment.php',

                                'IPlayer'                     => '../players/IPlayer.php',
                                'HumanPlayer'                 => '../players/HumanPlayer.php',
                                'ArtificialIntelligence'      => '../players/ArtificialIntelligence.php',

                                'EndOfPlayState'              => '../states/EndOfPlayState.php',
                                'IApplicationState'           => '../states/IApplicationState.php',
                                'MapState'                    => '../states/MapState.php',
                                'MenuState'                   => '../states/MenuState.php',
                                'PlayState'                   => '../states/PlayState.php',
                                'PreloaderState'              => '../states/PreloaderState.php',

                                'IStateView'                  => '../views/IStateView.php',
                                'MapStateView'                => '../views/MapStateView.php',
                                'MenuStateView'               => '../views/MenuStateView.php',
                                'PlayStateView'               => '../views/PlayStateView.php',
                                'EndOfPlayStateView'          => '../views/EndOfPlayStateView.php',
                                'PreloaderStateView'          => '../views/PreloaderStateView.php',

                                'DummyView'                   => '../views/islandViews/DummyView.php',
                                'BankView'                    => '../views/bank/BankView.php',

        );

        if(!isset($classes[$classname]))
            return false;

        require_once $classes[$classname];
    }

?>