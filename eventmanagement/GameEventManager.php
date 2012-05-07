<?php
require_once("../config/config.php");
class GameEventManager implements IEventManager{

    private static $instance = null;
    private $listeners = array();

    private function __construct(){}

    public static function getInstance(){
        if(self::$instance == null && !isset($_SESSION['IEventManager'])){
            self::$instance = new GameEventManager();
            $_SESSION['IEventManager'] = self::$instance;
        }
        self::$instance = $_SESSION['IEventManager'];
        return self::$instance;
    }

    public function addEventListener(IEventListener $eventListener, $eventType){
        if(!isset(GameEventManager::getInstance()->listeners[$eventType])){
            GameEventManager::getInstance()->listeners[$eventType] = array($eventListener);
        }
        else{
            array_push(GameEventManager::getInstance()->listeners[$eventType], $eventListener);
        }
    }

    public function removeEventListener(IEventListener $eventListener, $eventType){

        for($i = 0; $i < count(GameEventManager::getInstance()->listeners[$eventType]); ++$i) {

            if(GameEventManager::getInstance()->listeners[$eventType][$i] === $eventListener){
                unset(GameEventManager::getInstance()->listeners[$eventType][$i]);
                return;
            }
        }
    }

    public function dispatchEvent(IEvent $event){

        $listeners = GameEventManager::getInstance()->listeners[$event->getEventType()];

        foreach ($listeners as $listener) {
            $listener->handleEvent($event);
        }

    }
}
?>