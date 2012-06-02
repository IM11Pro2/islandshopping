<?php
    class Payment implements IPayment {

        private $value;
        private $currency;
        private $currencyTranslation;
        private $countryCurrencies;

        public function __construct(ICountry $country = null){
            $this->countryCurrencies = getCurrencies();

            if($country != null){
                $this->setCurrency($country->getName());
                $this->setCurrencyTranslation($country->getName());
                $this->setValue(START_CAPITAL_COUNTRY);

                $country->setPayment($this);
            }
        }


        public function getValue() {
            return $this->value;
        }

        //Money I can use for an attack minus basis capital
        public function getUsableValue() {
            return $this->value - BASIC_CAPITAL_REGION;
        }

        public function getCurrency() {
            return $this->currency;
        }

        public function getCurrencyTranslation() {
            return $this->currencyTranslation;
        }

        public function setValue($value) { //float
                $this->value = $value;
        }

        public function addValue($value) {
                $this->value += $value;
        }

        public function reduceValue($value) {
                $this->value -= $value;
        }

        public function setCurrency($countryName) {
            $this->currency = $this->countryCurrencies[$countryName][0];
        }

        public function setCurrencyTranslation($countryName) {
            $this->currencyTranslation = $this->countryCurrencies[$countryName][1];
        }

        public function isBuyable(IPayment $enemyPayment, $venture) {
            /* grundwert noch zu subtrahieren und gewährleisten,
            dass sich min der grundbetrag auch auf übernommener region befindet
            (spekulationszufallswert beachten)
            */
            return (($this->value * $venture)>= ($enemyPayment->getValue()+2*BASIC_CAPITAL_REGION));
        }

        public function __toString(){
            return ($this->value * $this->currencyTranslation)." ".$this->currency;
        }
    }
