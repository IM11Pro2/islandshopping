<?php
//require_once("config.php");

interface IEventListener{
    function handleEvent(IEvent $event);
}
?>