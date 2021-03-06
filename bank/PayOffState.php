<?php
    class PayOffState implements IBankState{

        public function depositMoney(IPayment $capital, $value){
            return 0;
        }

        public function payOffMoney(IPayment $capital) {

            if(($capital->getValue() - BASIC_CAPITAL_REGION) >= 0){
                $capital->reduceValue(BASIC_CAPITAL_REGION);
                return BASIC_CAPITAL_REGION;
            }
            else{
                return 0;
            }

        }

        public function chargeInterest($interestBase) {
            return 0;
        }
    }
?>
