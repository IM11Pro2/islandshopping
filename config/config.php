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
    define("NUM_OF_REGIONS", 35);

    //Amount of StartMoney per Country
    define("START_CAPITAL_COUNTRY", 1000.0);

    //Basic Capital per Region == Grundkapital
    define("BASIC_CAPITAL_REGION", 50.0);

    function getSpeculationValues(){
        return array(
            0.5,
            0.66,
            1,
            1,
            1.5,
            2
        );
    }

    define("INTEREST_RATE", 0.10);

    //Defines the interval after how many moves an incident occurs
    define("MIN_MOVES_FOR_INCIDENT", PHP_INT_MAX-1);
    define("MAX_MOVES_FOR_INCIDENT", PHP_INT_MAX);

    define("BANK_ROBBERY", 0.5); // p/100 of lost bankcapital

    define("INCIDENT_CAPITAL", 100);
    //Messages for the incident dialogs
    //---------- LOCAL INCIDENT -------------
    function getIncidentMessages(){

        $currencies = getCountriesArray();

        $countryNames = array();
        foreach($currencies as $key => $value){
            array_push($countryNames, $key);
        }

        $messages = array(
            // 0 global
            array(
                //0.0 bank
                "Bank wurde ausgeraubt",

                //0.1 region
                "Region: Finanzmarkt ist eingebrochen :("
            ),
            // 1 local
            array(
                // 1.0 country1
               $countryNames[0] => array(
                    // 1.0.0 positve
                    "positive" => array(
                        "Die ".$countryNames[0]." hat die Steuern für Bier erhöht und erzielt damit Mehreinnahmen von:",
                        "Durch Kürzungen im Bildungssystem spart die ".$countryNames[0].":",
                        "Die ".$countryNames[0]."-Mitgliedsländer haben einen höheren Beitrag zu zahlen, dadurch steigt der Gewinn um:"
                    ),

                    // 1.0.1 negative
                    "negative" => array(
                        "Der Verwaltungsaufwand in der ".$countryNames[0]." hat das Budget überschritten. Die Kosten belaufen sich bei:",
                        "Der Fehler eines Dolmetschers kostet die ".$countryNames[0].":",
                        "Die Spesen mehrerer ".$countryNames[0]."-Abgeordneten auf dem Ballermann betragen:"
                    )

                ),
                // 1.1 country2
               $countryNames[1] => array(
                    // 1.1.0 positve
                    "positive" => array(
                        "Die Einzahlung von Schmiergeldern beschert der ".$countryNames[1]." Zinsen im Wert von",
                        "Das internationale Käsefestival in der ".$countryNames[1]." lockt viele Besucher an. Der Gewinn beträgt:",
                        "Die ".$countryNames[1]." mustert Teile der Lawinenhund-Staffel aus und erspart sich Kosten im Wert von:"
                    ),

                    // 1.1.1 negative
                    "negative" => array(
                        "In der ".$countryNames[1]." hat die Schließung einer Schokoladenfabrik zum Streik der ganzen Bevölkerung geführt. Die Kosten belaufen sich bei:",
                        "In der ".$countryNames[1]." kostet das Aufschütten der Alpen für die Vergrößerung von Skigebieten:",
                        "Die verzögerte Entwicklung eines Hightech-Messers kostet die ".$countryNames[1].":"
                    )

                ),
                // 1.2 country3
                $countryNames[2] => array(
                    // 1.2.0 positve
                    "positive" => array(
                        "Der Verkauf von Burkinis in der ".$countryNames[2]." erwirtschaftet ein Plus von:",
                        "Zahlreiche Maturaklassen feiern in der ".$countryNames[2]." und bescheren einen Gewinn von:",
                        "Eine neue Kebapstand-Filiale in Wien bringt der".$countryNames[2]." ein Umsatzplus von:"
                    ),

                    // 1.2.1 negative
                    "negative" => array(
                        "Die ".$countryNames[2]." investiert in die Bestrebungen der EU beizutreten - Verursachte Kosten:",
                        "Schmerzensgeldzahlungen wegen Lebensmittelvergiftungen kosten die ".$countryNames[2].":",
                        "Die ".$countryNames[2]." baut die Züchtung von Seidenraupen aus und rechnet mit Mehrkosten von:"
                    )

                ),
                // 1.3 country4
                $countryNames[3] => array(
                    // 1.0.0 positve
                    "positive" => array(
                        "Ein neuer Ölfund bringt ".$countryNames[3]." Einnahmen von:",
                        $countryNames[3]." verkauft seine größte Sandburg und erzielt einen Preis von:",
                        "Mekka-Pilger bringen ".$countryNames[3]." Einnahmen von:"
                    ),

                    // 1.0.1 negative
                    "negative" => array(
                        "Die Veranstaltung eines Beachvolleyball-Turniers kostet ".$countryNames[3].":",
                        "Ein neues Hochhaus wird errichtet und übersteigt das Budget. ".$countryNames[3]." zahlt die Mehrkosten von:",
                        "Eine ausgelassene Party der reichsten Scheichs von ".$countryNames[3]." kostet:"
                    )

                ),
                // 1.4 country5
                $countryNames[4] => array(
                    // 1.0.0 positve
                    "positive" => array(
                        "Die Werbeinnahmen der Superbowl bescheren der ".$countryNames[4]." einen Gewinn von:",
                        "Die Casinos in Las Vegas/".$countryNames[4]." erwirtschaften ein Plus von:",
                        "Nach dem Einführen der Fettsteuer in den ".$countryNames[4]." steigen die Mehreinnahmen um:"
                    ),

                    // 1.0.1 negative
                    "negative" => array(
                        "Die ".$countryNames[4]." verschärfen die Grenzkontrollen nach Mexico. Kostenpunkt:",
                        "Die Finazierung von NASA-Projekten kostet die ". $countryNames[4].":",
                        "Das neuerliche Aufrüsten von Atomwaffen belastet das Budget der ".$countryNames[4]." um:"
                    )

                )

            )
        );
        return $messages;

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
                                'LocalIncidentEvent'           => '../events/LocalIncidentEvent.php',
                                'IncidentEvent'               => '../events/IncidentEvent.php',

                                'IncidentGenerator'           => '../incident/IncidentGenerator.php',

                                'Map'                         => '../map/Map.php',
                                'Region'                      => '../map/Region.php',
                                'RegionGraph'                 => '../map/RegionGraph.php',
                                'GraphNode'                   => '../map/RegionGraph.php',

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
                                'MapView'                   => '../views/islandViews/MapView.php',
                                'BankView'                    => '../views/bank/BankView.php',

        );

        if(!isset($classes[$classname]))
            return false;

        require_once $classes[$classname];
    }

?>