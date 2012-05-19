<?php

class GlobalRegionEvent extends IncidentEvent
{

    const TYPE = "GLOBAL_REGION";

    private $region;
    private $message;

    public function __construct($message, Region $region){
        $this->region = $region;
        $this->message = $message;
    }


    function getEventType()
    {
        return self::TYPE;
    }

    function execute()
    {
        // $this->region;
        // $this->message;
    }
}
