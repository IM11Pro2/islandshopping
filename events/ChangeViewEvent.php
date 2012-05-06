<?php
    require_once("../config/config.php");
    class ChangeViewEvent implements IEvent {
        private $view;
        const TYPE = "CHANGE_VIEW";

        public function __construct(IStateView $view) {
            $this->view = $view;
        }

        function getEventType() {
            return self::TYPE;
        }

        function getView() {
            return $this->view;
        }
    }
