<?php

    class LocalIncidentEvent extends IncidentEvent{

        const TYPE = "LocalIncident";

        private $message;
        private $bank;
        private $isPositive;


        /*
         *
         * check when bank has no money left
         *
         */

        public function __construct($message, Bank $bank, $positive){
            $this->message = $message;
            $this->bank = $bank;
            $this->isPositive = $positive;
        }

        function getEventType() {
            return self::TYPE;
        }

        function execute() {

            $payment = $this->bank->getCountry()->getPayment(); // fehler: ist kein payment

            $value = INCIDENT_CAPITAL;

            if($this->isPositive){
                $payment->addValue($value);
            }
            else{
                if($payment->getValue() >= INCIDENT_CAPITAL){
                    $payment->reduceValue($value);
                }
                else{
                    //$value = (INCIDENT_CAPITAL-$payment->getValue());
                    //$payment->reduceValue($value);
                    $value = $payment->getValue();
                    $payment->reduceValue($value);
                }
            }

            $returnValue = number_format($value * $payment->getCurrencyTranslation());


            return array("message" => $this->message, "bankCapital" => $this->bank->getCapital(),
                                                                "bankName" => $this->bank->getCountry()->getName()."Bank",
                                                                "payment" => $payment->__toString(),
                                                                "value" => $returnValue." ".$payment->getCurrency(),
                                "type" => self::TYPE);

        }
    }
?>