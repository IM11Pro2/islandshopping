<?php
    abstract class IPlayer {

        const ACTIVE = 0;
        const INACTIVE = 1;
        const GAME_OVER = 2;

        private $country;
        protected $playerId;
        private $playerState;
        protected $playRound;

        protected function init($countryName, $colorArray, $round){
            $this->country = new Country($this, $countryName, $colorArray[$this->playerId]);
            $this->playRound = $round;
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

        public function setPlayerState($playerState){
            $this->playerState = $playerState;
        }

        public function getPlayerState(){
            return $this->playerState;
        }

        public function getPlayRound(){
            return $this->playRound;
        }

        public function updatePlayRound(){
            $this->playRound++;
        }
    }
