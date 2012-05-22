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
            "EU"           => array("Euro", 1.0),
            "Schweiz"      => array("Franken", 1.20),
            "Tuerkei"      => array("Lira", 2.30),
            "SaudiArabien" => array("Riyal", 4.88),
            "USA"          => array("Dollar", 1.30));
    }

    //Colors
    function getColorArray() {
        return array("#FF0000", "#00FF00", "#0000FF", "#FFFF00 ", "#A901DB");
    }

    //Number of Regions
    define("NUM_OF_REGIONS", 12);

    //Amount of StartMoney per Country
    define("START_CAPITAL_COUNTRY", 1000.0);

    //Basic Capital per Region == Grundkapital
    define("BASIC_CAPITAL_REGION", 50.0);

    //Defines how often an incident occurs (more is less)
    define("FREQUENCY_OF_INCIDENT", 20);

    //Messages for the incident dialogs
    //---------- LOCAL INCIDENT -------------
    function getIncidentMessages(){

        $currencies = getCurrencies();

        $countryNames = array();
        foreach($currencies as $key){
            array_push($countryNames, $key);
        }

        return array(
            // 0 global
            array(
                //0.0 bank
                "Bank wurde ausgeraubt",

                //0.1 region
                "Region Finanzen wurde eingebrochen"
            ),
            // 1 local
            array(
                // 1.0 country1
                $countryNames[0] => array(
                    // 1.0.0 positve
                    "positve" => array(
                        "positive message 1 for $countryNames[0]",
                        "positive message 2 for $countryNames[0]",
                        "positive message 3 for $countryNames[0]"
                    ),

                    // 1.0.1 negative
                    "negative" => array(
                        "negative message 1 for $countryNames[0]",
                        "negative message 2 for $countryNames[0]",
                        "negative message 3 for $countryNames[0]"
                    )

                ),
                // 1.1 country2
                $countryNames[1] => array(
                    // 1.1.0 positve
                    "positve" => array(
                        "positive message 1 for $countryNames[1]",
                        "positive message 2 for $countryNames[1]",
                        "positive message 3 for $countryNames[1]"
                    ),

                    // 1.1.1 negative
                    "negative" => array(
                        "negative message 1 for $countryNames[1]",
                        "negative message 2 for $countryNames[1]",
                        "negative message 3 for $countryNames[1]"
                    )

                ),
                // 1.2 country3
                $countryNames[2] => array(
                    // 1.2.0 positve
                    "positve" => array(
                        "positive message 1 for $countryNames[2]",
                        "positive message 2 for $countryNames[2]",
                        "positive message 3 for $countryNames[2]"
                    ),

                    // 1.2.1 negative
                    "negative" => array(
                        "negative message 1 for $countryNames[2]",
                        "negative message 2 for $countryNames[2]",
                        "negative message 3 for $countryNames[2]"
                    )

                ),
                // 1.3 country4
                $countryNames[3] => array(
                    // 1.0.0 positve
                    "positve" => array(
                        "positive message 1 for $countryNames[3]",
                        "positive message 2 for $countryNames[3]",
                        "positive message 3 for $countryNames[3]"
                    ),

                    // 1.0.1 negative
                    "negative" => array(
                        "negative message 1 for $countryNames[3]",
                        "negative message 2 for $countryNames[3]",
                        "negative message 3 for $countryNames[3]"
                    )

                ),
                // 1.4 country5
                $countryNames[4] => array(
                    // 1.0.0 positve
                    "positve" => array(
                        "positive message 1 for $countryNames[4]",
                        "positive message 2 for $countryNames[4]",
                        "positive message 3 for $countryNames[4]"
                    ),

                    // 1.0.1 negative
                    "negative" => array(
                        "negative message 1 for $countryNames[4]",
                        "negative message 2 for $countryNames[4]",
                        "negative message 3 for $countryNames[4]"
                    )

                )

            )
        );


    }


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
                                'GlobalBankEvent'             => '../events/GlobalBankEvent.php',
                                'GlobalRegionEvent'           => '../events/GlobalRegionEvent.php',
                                'IncidentEvent'               => '../events/IncidentEvent.php',

                                'IncidentGenerator'           => '../incident/IncidentGenerator.php',

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