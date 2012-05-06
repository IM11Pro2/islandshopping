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
    }
