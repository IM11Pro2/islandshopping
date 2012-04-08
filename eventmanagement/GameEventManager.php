<?php
require_once("../config.php");

class GameEventManager implements IEventManager{

    private static $instance = null;
    private $listeners = array();

    private function __construct(){}


    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new GameEventManager();
        }

        return self::$instance;
    }

    public function addEventListener(IEventListener $eventListener, $eventType){

        if(!isset($listeners[$eventType])){
            $listeners[$eventType] = array($eventListener);
        }
        else{
            array_push($listeners[$eventType], $eventListener);
        }
    }

    public function removeEventListener(IEventListener $eventListener, $eventType){

        for($i = 0; $i < count($this->listeners[$eventType]); ++$i) {
            if($this->listeners[$eventType][i] === $eventListener){
                unset($this->listeners[$eventType][i]);
                return;
            }
        }
    }

    public function dispatchEvent(IEvent $event){

        foreach ($this->listeners[$event->getEventType()] as $listener) {
            $listener->handleEvent($event);
        }

    }
}
?>