<?php
    interface ICountry {
        function getName();

        function setName($name);

        function getColor();

        function setColor($playerId);

        function getPayment();

        function setPayment(IPayment $payment);
    }
