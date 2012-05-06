<?php
class GameApplication implements IEventListener
{
    private $state;
    private $view;

    function __construct(){
        $_SESSION['IEventManager'] = GameEventManager::getInstance();
        $_SESSION['IEventManager']->addEventListener($this, ChangeStateEvent::TYPE);
        $_SESSION['IEventManager']->addEventListener($this, ChangeViewEvent::TYPE);

        //beim 1. mal ausführen
        $this->state = new MenuState();

        $this->view = new MenuStateView();
        $_SESSION['view'] = $this->view;
        //echo "1er Sessionaufruf <br />";
        //print_r($_SESSION);
        $this->state->init();
        $this->view->printView();
    }

    function handleEvent(IEvent $event)
    {

        if($event->getEventType() == ChangeViewEvent::TYPE){
            echo "Change View Event<br />";
            //echo "2er Sessionaufruf <br />";
            //print_r($_SESSION);
            unset($this->view);
            //echo "3er Sessionaufruf <br />";
            //print_r($_SESSION);
            $this->view = $event->getView();
            $this->view->printView();
        }

        if($event->getEventType() == ChangeStateEvent::TYPE){

            //echo "Change State Event<br />";

            $this->state = $event->getState();

            //session_id($event->getSessionId());
            $this->state->init();
        }
    }
}

?>
