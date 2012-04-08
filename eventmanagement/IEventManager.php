<?php
require_once("../config.php");

interface IEventManager{

    public function addEventListener(IEventListener $eventListener, $eventType);

    public function removeEventListener(IEventListener $eventListener, $eventType);

    public function dispatchEvent(IEvent $event);

}
?>