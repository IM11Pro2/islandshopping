<?php
    class Country implements ICountry {

        private $name;
        private $color;
        private $payment;

        function __construct() {
           // echo "im country __construct";
            // TODO: Implement __construct() method.
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

        public function setColor($color) {
            $this->color = $color;
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
