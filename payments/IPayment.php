<?php
    interface IPayment {
        function getValue();

        function getCurrency();

        function getCurrencyTranslation();

        function setValue(float $value);

        function setCurrency($currency);

        function setCurrencyTranslation(float $currencyTranslation);

        function isBuyable(IPayment $payment);
    }
