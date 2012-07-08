<?php
    class ArtificialIntelligence extends IPlayer {

        static $AI_ID_counter = 1;

        public function __construct($countryName, $colorArray) {
            $this->playerId = self::$AI_ID_counter;
            self::$AI_ID_counter++;

            $this->setPlayerState(self::INACTIVE);
            $this->init($countryName, $colorArray, 0);
        }

        public function makeInitPayOff($allEnemyRegions, $regions){
            $possiblePayoffDecisions = array();

            for($i=0; $i<count($allEnemyRegions); $i++){
                $actualRegion = $allEnemyRegions[$i];
                $actualRegionId = $actualRegion->getRegionId();

                $neighbours = $actualRegion->getNeighbours();

                for($j=0; $j< count($neighbours); $j++){
                    // if neighbour region is of other country
                    if($regions[$neighbours[$j]]->getCountry() != $this->getCountry()){
                        // if neighbour region has more/equal money --> payoff
                        if($regions[$neighbours[$j]]->getPayment()->getValue() >= $actualRegion->getPayment()->getUsableValue()){
                            array_push($possiblePayoffDecisions, array("initPayOff" => $actualRegionId));
                        }
                    }
                }

            }

            if(count($possiblePayoffDecisions) > 0){
                $randomPayoff = rand(0, (count($possiblePayoffDecisions)-1));
            }
            else{
                //TODO
                // $randomPayoff=;
                return null;
            }

            return $possiblePayoffDecisions[$randomPayoff];
        }

        public function makeDecision($allEnemyRegions, $regions){
            $possibleDecisions = array();
            $nextPlayerCounter = 0;
            $sameCountryNeighbours = 0;

            for($i=0; $i<count($allEnemyRegions); $i++){
                $actualRegion = $allEnemyRegions[$i];
                $actualRegionId = $actualRegion->getRegionId();

                //Look if Neighbour-Attack is possible
                $neighbours = $actualRegion->getNeighbours();
                for($j=0; $j< count($neighbours); $j++){
                    // if neighbour region is of other country
                    if($regions[$neighbours[$j]]->getCountry() != $this->getCountry()){
                        // if neighbour region has less money --> attack
                        if($regions[$neighbours[$j]]->getPayment()->getValue() < $actualRegion->getPayment()->getUsableValue()){
                            $enemyRegionId = $regions[$neighbours[$j]]->getRegionId();

                            array_push($possibleDecisions, array("attack" => $enemyRegionId, "actualRegionId" => $actualRegionId));
                        }
                        // if neighbour region has more money --> payoff
                        else if($regions[$neighbours[$j]]->getPayment()->getValue() > $actualRegion->getPayment()->getUsableValue()){
                            array_push($possibleDecisions, array("payOff" => $actualRegionId));
                        }
                        // if neighbour region has equal money
                        else {
                            // if there is minimum half of the startcapital of money on the bank -- pay off
                            $bankList = $_SESSION['state']->getBankList();
                            if($bankList[$this->playerId]->getPlainCapital() > (START_CAPITAL_COUNTRY/3)){
                                array_push($possibleDecisions, array("payOff" => $actualRegionId));
                            }
                            else {
                                array_push($possibleDecisions, array("nextPlayer" => $actualRegionId));
                            }
                        }

                        if($nextPlayerCounter == 3){
                            array_push($possibleDecisions, array("nextPlayer" => $actualRegionId));
                            $nextPlayerCounter = 0;
                        }

                        $nextPlayerCounter++;
                    }
                    // if neighbour is of same country
                    else {
                        $sameCountryNeighbours++;

                        if($sameCountryNeighbours == 2) {
                            if(($regions[$neighbours[$j]]->getPayment()->getValue()-BASIC_CAPITAL_REGION) >= BASIC_CAPITAL_REGION){
                                 array_push($possibleDecisions, array("deposit" => $actualRegionId));
                            }
                        }
                    }
                }
            }

            //chooses a random number between 0 and length-1 of array
            $random = rand(0, count($possibleDecisions)-1);

/*            echo "Possible Decisions Array";
            print_r($possibleDecisions);
            echo "<br/>";
            echo "random: " . $random . "<br/>";*/

            //returns a random decison of all possible decisions
            return $possibleDecisions[$random];
        }
    }
