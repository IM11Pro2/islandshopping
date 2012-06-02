<?php
    class Country implements ICountry {

        private $name;
        private $payment;
        private $color;

        function __construct(IPlayer $player, $countryName ,$countryColor) {

            $this->name = $countryName;
            $this->color = $countryColor;

            $player->setCountry($this);
            $this->payment = new Payment($this);
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
