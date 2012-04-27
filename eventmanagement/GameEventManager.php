<?php
require_once("../config/config.php");
//require_once("./eventmanagement/IEventManager.php");

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

        if(!isset($this->listeners[$eventType])){


            $this->listeners[$eventType] = array($eventListener);
        }
        else{
            array_push($this->listeners[$eventType], $eventListener);
        }

        $_SESSION['IEventManager'] = self::$instance;
    }

    public function removeEventListener(IEventListener $eventListener, $eventType){

        for($i = 0; $i < count($this->listeners[$eventType]); ++$i) {

            if($this->listeners[$eventType][$i] === $eventListener){
                unset($this->listeners[$eventType][$i]);
                return;
            }

        }
    }

    public function dispatchEvent(IEvent $event){

        $listeners = $this->listeners[$event->getEventType()];

        foreach ($listeners as $listener) {
            $listener->handleEvent($event);
        }

    }
}
?>