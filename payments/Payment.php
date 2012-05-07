<?php
    class Payment implements IPayment {

        private $value;
        private $currency;
        private $currencyTranslation;
        private $isBuyable;
        private $countryPayments;

        public function __construct(){
            $this->countryPayments = getPayments();
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

        public function setValue(float $value) {
            $this->value = $value;
        }

        public function setCurrency($countryName) {
            $this->currency = $this->countryPayments[$countryName][0];
        }

        public function setCurrencyTranslation($countryName) {
            $this->currencyTranslation = $this->countryPayments[$countryName][1];
        }

        public function isBuyable(IPayment $otherPayment) {
            return $this->isBuyable;
        }
    }
