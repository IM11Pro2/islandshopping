<?php
    interface IBankState  {
        function depositMoney(IPayment $capital);
        function payOffMoney(IPayment $capital);
        function chargeInterest();
        //function addInterest();

    }
