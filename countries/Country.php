<?php
    class Country implements ICountry {

        private $name;
        private $payment;
        private $color;
        private $colors;

        function __construct() {
            $this->colors = getColorArray();
        }

        function __destruct() {
            // TODO: Implement __destruct() method.
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function getName() {
            return $this->name;
        }

        public function setColor($playerId) {
            print_r($this->colors);
            $this->color = $this->colors[$playerId];
        }

        public function getColor() {
            return $this->color;
        }

        public function setPayment(IPayment $payment) {
            $this->payment = $payment;
        }

        public function getPayment() {
            return $this->payment;
        }
    }
