<?php
    class AttackState implements IBankState{

        public function depositMoney(Payment $capital) {
        }

        public function payOffMoney(Payment $capital) {
            return 0;
        }

        private function chargeInterest() {
            // TODO: Implement chargeInterest() method.
        }
    }
