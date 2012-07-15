<?php
require_once("../config/config.php");
class GlobalBankEvent extends IncidentEvent
{

    const TYPE = "GLOBAL_BANK";

    private $bank;
    private $message;

    public function __construct($message, Bank $bank){
        $this->bank = $bank;
        $this->message = $message;
    }


    function getEventType()
    {
        return self::TYPE;
    }

    function execute()
    {
        $this->bank->setPlainCapital($this->bank->getPlainCapital()*BANK_ROBBERY);
        return array("message" => $this->message, "bankCapital" => $this->bank->getCapital(),
                                                    "bankName" => $this->bank->getCountry()->getName()."Bank",
                                                    "country" => $this->bank->getCountry()->getName(),
                            "type" => self::TYPE);
    }
}
