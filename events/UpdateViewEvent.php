<?php
//require_once("./states/IApplicationState.php");
//require_once("./events/IEvent.php");
class UpdateViewEvent implements IEvent
{

    private $state;
    const TYPE = "UPDATE_VIEW";

    public function __construct(/*IApplicationState*/ $state){
        $this->state = $state;
    }

    function getEventType(){
        return self::TYPE;
    }

    function getState(){
        return $this->state;
    }
}
