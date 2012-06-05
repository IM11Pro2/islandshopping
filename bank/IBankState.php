<?php
    interface IBankState  {
        function depositMoney(IPayment $capital, $value);
        function payOffMoney(IPayment $capital);
        function chargeInterest();
        //function addInterest();

    }
?>