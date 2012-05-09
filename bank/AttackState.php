<?php
    class AttackState implements IBankState{

        public function depositMoney(IPayment $capital) {
        }

        public function payOffMoney(IPayment $capital) {
            return 0;
        }

        public function chargeInterest() {
            // TODO: Implement chargeInterest() method.
        }
    }
