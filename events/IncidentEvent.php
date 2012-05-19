<?php
    require_once("../config/config.php");

    abstract class IncidentEvent implements IEvent {

    abstract function execute();

    }
?>