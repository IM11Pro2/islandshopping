<?php
    class ArtificialIntelligence implements IPlayer {

        private $country;
        private $playerId;
        static $counter = 1;

        public function __construct() {
            $this->playerId = self::$counter;
            self::$counter++;
        }

        public function __destruct() {
            // TODO: Implement __destruct() method.
        }

        public function getCountry() {
           return $this->country;
        }

        public function setCountry(ICountry $country) {
            $this->country = $country;
        }

        public function getPlayerId() {
            return $this->playerId;
        }

        public function makeDecision($allEnemyRegions, $regions){
            $possibleDecisions = array();

            for($i=0; $i<count($allEnemyRegions); $i++){
                $actualRegion = $allEnemyRegions[$i];
                $actualRegionId = $actualRegion->getRegionId();

                //Look if Neighbour-Attack is possible
                $neighbours = $actualRegion->getNeighbours();
                for($j=0; $j< count($neighbours); $j++){
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
                            if($_SESSION['listOfBanks'][$this->playerId]->getPlainCapital() > (START_CAPITAL_COUNTRY/2)){
                                array_push($possibleDecisions, array("payOff" => $actualRegionId));
                            }
                            else {
                                array_push($possibleDecisions, array("nextPlayer" => $actualRegionId));
                            }
                            //array_push($possibleDecisions, array("test" => $actualRegionId));
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
