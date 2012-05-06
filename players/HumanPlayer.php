<?php
    class HumanPlayer implements IPlayer {

        private $country;
        private $playerId;

        public function __construct() {
            $this->playerId = 0;
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
