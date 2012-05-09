<?php
    require_once("../config/config.php");
    //require_once("./states/IApplicationState.php");
    //require_once("./events/IEvent.php");
    class UpdateViewEvent implements IEvent {

        const TYPE = "UPDATE_VIEW";

        public function __construct(){
        }

        function getEventType() {
            return self::TYPE;
        }
    }
