<?php
    require_once("../config/config.php");
    class ChangeStateEvent implements IEvent {

        private $state;
        // private $sessionId;
        const TYPE = "CHANGE_STATE";

        public function __construct(IApplicationState $state /*, $sessionId*/) {
            $this->state = $state;
            // $this->sessionId = $sessionId;
        }

        function getEventType() {
            return self::TYPE;
        }

        function getState() {
            return $this->state;
        }

/*    function getSessionId(){
        return $this->sessionId;
    }*/
    }
