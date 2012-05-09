<?php
    interface IBankState  {
        function depositMoney(Payment $capital);
        function payOffMoney(Payment $capital);
        function chargeInterest();
        //function addInterest();

    }
