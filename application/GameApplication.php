<?php
class GameApplication implements IEventListener
{
    private $state;
    private $view;

    function __construct(){

        GameEventManager::getInstance()->addEventListener($this, ChangeStateEvent::TYPE);
        GameEventManager::getInstance()->addEventListener($this, ChangeViewEvent::TYPE);

        $this->state = new MenuState();

        $this->view = new MenuStateView();

        $this->state->init();
        $this->view->printView();
    }

    function handleEvent(IEvent $event)
    {

        if($event->getEventType() == ChangeViewEvent::TYPE){
            if($this->view instanceof MapStateView){
                GameEventManager::getInstance()->removeEventListener($this->view, UpdateViewEvent::TYPE);

            }

            $this->view = $event->getView();
            $this->view->printView();
        }

        if($event->getEventType() == ChangeStateEvent::TYPE){
            $this->state = $event->getState();
            $this->state->init();
        }
    }
}

?>
