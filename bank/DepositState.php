<?php
    class DepositState implements IBankState{

        public function depositMoney(IPayment $capital) {
            $capital->addValue(BASIC_CAPITAL_REGION);
        }

        public function payOffMoney(IPayment $capital) {
            return 10;
        }

        public function chargeInterest() {
            // TODO: Implement chargeInterest() method.
        }
    }
