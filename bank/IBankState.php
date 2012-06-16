<?php
    interface IBankState  {
        function depositMoney(IPayment $capital, $value);
        function payOffMoney(IPayment $capital);
        function chargeInterest($interestBase);
        //function addInterest();

    }
?>