<?php
    abstract class IPlayer {

        const ACTIVE = 0;
        const INACTIVE = 1;
        const GAME_OVER = 2;

        private $country;
        protected $playerId;
        private $playerState;

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
    }
