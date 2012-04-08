<?php

class Region{

    private static $ID = 0;

    private $neighbours = array();
    private $playerId;
    private $regionId;
    private $color;
    private $payment;

    public function __construct(Player $player){
        $this->regionId = ++self::$ID;
        $this->playerId = $player->getPlayerId();
        $this->color = $player->getCountry()->getColor();
        $this->payment = $player->getCountry()->getPayment();
    }


    public static function resetRegionId(){
        self::$ID = 0;
    }

}

?>