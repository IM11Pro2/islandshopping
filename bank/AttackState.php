<?php
    class AttackState implements IBankState{

        public function depositMoney(IPayment $capital, $value) {
            // do nothing: bank is inactive
            return 0;
        }

        public function payOffMoney(IPayment $capital) {
            // do nothing: bank is inactive
            return 0;
        }

        public function chargeInterest() {
            // do nothing: bank is inactive
        }
    }
