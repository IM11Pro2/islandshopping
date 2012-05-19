<?php

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
        // $this->bank;
        // $this->message;
    }
}
