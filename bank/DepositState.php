<?php
    class DepositState implements IBankState{

        public function depositMoney(Payment $capital) {
            $capital->addValue();
        }

        public function payOffMoney(Payment $capital) {
            return 0;
        }

        private function chargeInterest() {
            // TODO: Implement chargeInterest() method.
        }
    }
