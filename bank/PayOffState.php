<?php
    class PayOffState implements IBankState{

        public function depositMoney(Payment $capital){
            $capital->addValue(BASIC_CAPITAL_REGION);
        }

        public function payOffMoney(Payment $capital) {

            if(($capital->getValue() - BASIC_CAPITAL_REGION) > 0){
                return BASIC_CAPITAL_REGION;
            }
            else{
                return 0;
            }

        }

        private function chargeInterest() {
            // TODO: Implement chargeInterest() method.
        }
    }
