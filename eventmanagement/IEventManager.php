<?php
require_once("../config.php");

interface IEventManager{
	
	public function addEventListener($eventListener, $eventType);
	
	public function removeEventListener($eventListener);
	
	public function dispatchEvent($event);

}
?>