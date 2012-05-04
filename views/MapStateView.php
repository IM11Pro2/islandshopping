<?php
require_once("../config/config.php");


class MapStateView implements IEventListener, IStateView {

    const ViewType = "MapStateView";

    public function __construct(){
        $_SESSION['IEventManager']->addEventListener($this, UpdateViewEvent::TYPE);
    }

    function handleEvent(IEvent $event)
    {
        if($event->getEventType() == UpdateViewEvent::TYPE){

            echo "Update View Event gekommt<br />";
        }
    }

    function printView(){?>

    <h1>MAP STATE VIEW</h1>

    <?php }

    function getViewType()
    {
        return self::ViewType;
    }
}


?>
<!--<h1>MAP STATE VIEW</h1>

--><?php
/*if (isset($_POST['playerCountry'])){
    $playerCountry = $_POST['playerCountry'];
    echo "Player: " . $playerCountry;
}
if (isset($_POST['otherCountries'])){
    $otherCountries = $_POST['otherCountries'];
    echo"<br /><br /> Gegner: ";
        foreach($otherCountries as $name => $country) {
            echo $country." ";
        }
}*/

//for($i=0, $i<$otherCo)




?>

