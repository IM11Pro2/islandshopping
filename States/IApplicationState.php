<?php
require_once("../config.php");

interface IApplicationState {
   	function init();
	function endState();
	function getApplicationStateType();
}

?>