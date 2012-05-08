<?php
    class Payment implements IPayment {

        private $value;
        private $currency;
        private $currencyTranslation;
        private $isBuyable;
        private $countryCurrencies;

        public function __construct(){
            $this->countryCurrencies = getCurrencies();
        }

        public function getValue() {
            return $this->value;
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

        public function setCurrency($countryName) {
            $this->currency = $this->countryCurrencies[$countryName][0];
        }

        public function setCurrencyTranslation($countryName) {
            $this->currencyTranslation = $this->countryCurrencies[$countryName][1];
        }

        public function isBuyable(IPayment $otherPayment) {
            return $this->isBuyable;
        }
    }
