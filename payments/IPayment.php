<?php
    interface IPayment {
        function getValue();

        function getCurrency();

        function getCurrencyTranslation();

        function setValue($value);

        function addValue($value);

        function reduceValue($value);

        function setCurrency($countryName);

        function setCurrencyTranslation($countryName);

        function isBuyable(IPayment $otherPayment, $venture);
    }
