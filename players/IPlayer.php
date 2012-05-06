<?php
    interface IPlayer {
        function getCountry();

        function setCountry(ICountry $country);

        function getPlayerId();
    }
