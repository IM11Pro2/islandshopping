<?php
    class DepositState implements IBankState{

        public function depositMoney(IPayment $capital, $value) {
            $capital->addValue($value);
            return $value;
        }

        public function payOffMoney(IPayment $capital) {
            return 0;
        }

        public function chargeInterest() {
            // TODO: Implement chargeInterest() method.
        }
    }
