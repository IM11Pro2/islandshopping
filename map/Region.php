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
            //$this->neighbours = $node->getNeighbours();
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

        public static function resetRegionId() {
            self::$ID = 0;
        }

    }

?>