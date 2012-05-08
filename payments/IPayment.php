<?php
    interface IPayment {
        function getValue();

        function getCurrency();

        function getCurrencyTranslation();

        function setValue($value); //float

        function setCurrency($countryName);

        function setCurrencyTranslation($countryName);

        function isBuyable(IPayment $otherPayment);
    }
