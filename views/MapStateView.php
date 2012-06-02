<?php
    require_once("../config/config.php");
    class MapStateView implements IEventListener, IStateView {

        const ViewType = "MapStateView";

        public function __construct() {
            GameEventManager::getInstance()->addEventListener($this, UpdateViewEvent::TYPE);

        }

        function handleEvent(IEvent $event) {
            if($event->getEventType() == UpdateViewEvent::TYPE) {
                $this->printView();
            }
        }

        function printView() {
            ?>


            <h1>MAP STATE VIEW</h1>

        <div class="mapView">
            <!-- Load svg -->
            <div id="canvas">
            <?php
                $dummyView = new DummyView();
                $dummyView->printDummyMap();
            ?>
            </div>
        </div><!--end .mapView-->

        <input type="button" name="MapRandom" value="Nächste Map">
        <input type="button" name="MapSubmit" value="Start">

        <?php
        }

        function getViewType() {
            return self::ViewType;
        }


    }


?>

