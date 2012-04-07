<?php
require_once("../config.php");
	
	class GameEventManager implements IEventManager{
		
			private function __construct(){}			
			
			
			private static $instance = null;
            
			
			public static function getInstance(){
				if(self::$instance == null){
					self::$instance = new GameEventManager();
				}
				
				return self::$instance;
			}
		
			public function addEventListener($eventListener, $eventType){
						
				if(!isset($listeners[$eventType])){
					$listeners[$eventType] = array($eventListener);
				}
				else{
					array_push($listeners[$eventType], $eventListener);
				}
			}

			public function removeEventListener($eventListener, $eventType){
				
				
				for($i = 0; $i < count($listeners[$eventType]); ++$i) {
					if($listeners[$eventType][i] === $eventListener){
						unset($listeners[$eventType][i]);
						return;
					}
				}
				
			
								
			}
	
			public function dispatchEvent($event, $listeners){
				
				foreach ($listeners[$event->getEventType()] as $listener) {
					$listener->handleEvent($event);
				}
			
			}
	}
?>