<?php
    interface ICountry {
        function getName();

        function setName($name);

        function getColor();

        function setColor($color);

        function getPayment();

        function setPayment(IPayment $payment);
    }
