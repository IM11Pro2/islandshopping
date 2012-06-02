<?php
    class HumanPlayer extends IPlayer {

        public function __construct($countryName, $colorArray) {
            $this->playerId = 0;
            $this->setPlayerState(self::INACTIVE);
            $this->init($countryName, $colorArray);
        }

    }
?>