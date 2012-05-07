<?php

    class Region {

        private static $ID = 0;

        private $neighbours = array();
        private $playerId;
        private $regionId;
        private $color;
        private $payment;

        public function __construct(IPlayer $player) {
            $this->regionId = self::$ID++;
            $this->playerId = $player->getPlayerId();
            $this->color = $player->getCountry()->getColor();
            $this->payment = $player->getCountry()->getPayment();
        }

        public function initNeighbourRegions($node){
            $this->neighbours = $node->getNeighbours();
        }

        public function getPlayerId(){

        }

        public function getRegionId(){
            return $this->regionId;
        }

        public static function resetRegionId() {
            self::$ID = 0;
        }

    }

?>