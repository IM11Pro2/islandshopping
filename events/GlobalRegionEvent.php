<?php
require_once("../config/config.php");

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
        $payment = $this->region->getPayment();
        $payment->setValue(BASIC_CAPITAL_REGION);
        return array("message" => $this->message, "region" => array(
                                                                            "regionId" => $this->region->getRegionId(),
                                                                                "payment" => $payment->__toString(),
            /*
                                                                            "paymentValue" => $payment->getValue(),
                                                                            "currencyTranslation" => $payment->getCurrencyTranslation()*/),
                    "type" => self::TYPE);
    }
}
