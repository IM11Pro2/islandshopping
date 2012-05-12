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
            for($i=0; $i<count($allEnemyRegions); $i++){
                $actualRegion = $allEnemyRegions[$i];




                //Look if Neighbour-Attack is possible
                $neighbours = $actualRegion->getNeighbours();
                //print_r($neighbours);
                for($j=0; $j< count($neighbours); $j++){
                    if($regions[$neighbours[$j]]->getCountry() != $this->getCountry()){
                        if($regions[$neighbours[$j]]->getPayment()->getValue() < $actualRegion->getPayment()->getValue()){
                            $regionId = $regions[$neighbours[$j]]->getRegionId();
                            echo json_encode(array("attack" => $regionId));
                        }
                        else{
                            $regionId = $actualRegion->getRegionId();
                            echo json_encode(array("payOff" => $regionId));
                        }
                    }
                }
            }
        }
    }
