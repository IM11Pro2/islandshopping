<?php
    require_once("../config/config.php");
    class MapStateView implements IEventListener, IStateView {

        const ViewType = "MapStateView";

        public function __construct() {
            //$_SESSION['IEventManager']->addEventListener($this, UpdateViewEvent::TYPE);

            //$_SESSION['state'] = $this;
        }

        function handleEvent(IEvent $event) {
            if($event->getEventType() == UpdateViewEvent::TYPE) {

                //echo "Update Map State View Event<br />";
                //$this->printView();
            }
        }

        function printView() {
            ?>


            <h1>MAP STATE VIEW</h1>

            <object data="../views/islandViews/islandMapView.svg" type="image/svg+xml"
                    width="1000" height="861.79346">

            </object>

            <div class="ajaxSuccess"></div>
            <input type="button" name="MapSubmit" value="Start">

        <?php
        }

        function getViewType() {
            return self::ViewType;
        }

        public static function ajaxRequest() {

            if(isset($_GET[session_name()])) {

                session_id($_GET[session_name()]);
                /*
                if(isset($_SESSION['IEventManager'])) {

                    // TODO change this to useable data
                    $eventManager = $_SESSION['IEventManager'];
                    $eventManager->dispatchEvent(new UpdateViewEvent("bla"));
                }*/

                if(isset($_GET['loadSite'])) {
                    //$_SESSION['state']->printView();
                }
            }
        }
    }


    if(isset($_GET['handle']) || isset($_GET['loadPage'])) {
        MapStateView::ajaxRequest();
    }

?>
<?php
    //$_SESSION['state']->printView();
    //MapStateView::printView();
    //$_SESSION['state']->getPlayerCountry();

?>

<?php


?>

