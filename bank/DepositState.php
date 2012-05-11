<?php
    class DepositState implements IBankState{

        public function depositMoney(IPayment $capital) {
            $capital->addValue(BASIC_CAPITAL_REGION);
            return BASIC_CAPITAL_REGION;
        }

        public function payOffMoney(IPayment $capital) {
            return 0;
        }

        public function chargeInterest() {
            // TODO: Implement chargeInterest() method.
        }
    }
