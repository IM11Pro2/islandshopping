<?php

    class Region {

        private static $ID = 0;

        private $neighbours = array();
        private $playerId;
        private $regionId;
        private $color;
        private $payment;
        private $country;

        public function __construct(IPlayer $player) {
            $this->regionId = self::$ID++;
            $this->playerId = $player->getPlayerId();
            $this->color = $player->getCountry()->getColor();
            $this->payment = new Payment();
            $this->country = $player->getCountry();

            $this->payment->setCurrency($this->country->getName());
            $this->payment->setCurrencyTranslation($this->country->getName());

            $this->payment->setValue(BASIC_CAPITAL_REGION);
        }

        public function initNeighbourRegions($node){
            foreach($node->getNeighbours() as $node){
                array_push($this->neighbours, $node->getId());
            }
        }

        public function getPlayerId(){
            return $this->playerId;
        }

        public function getColor(){
            return $this->color;
        }

        public function getRegionId(){
            return $this->regionId;
        }

        public function getNeighbours(){
            return $this->neighbours;
        }

        public function getCountry(){
            return $this->country;
        }

        public function getPayment(){
            return $this->payment;
        }

        public static function resetRegionId() {
            self::$ID = 0;
        }

    }

?>