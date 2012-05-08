<?php
    interface IBankState  {
        function depositMoney();
        function payOffMoney();
        function chargeInterest();
        //function addInterest();

    }
