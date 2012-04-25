<?php
//require_once("./states/IApplicationState.php");
//require_once("./states/MenuState.php");
//require_once("./views/MenuStateView.php");

class GameApplication
{
    private $state;
    private $view;

    function __construct(){
        $this->state = new MenuState();

        $this->view = new MenuStateView();

        $this->state->init();
        $this->view->printForm();
    }

}

?>
