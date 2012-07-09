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
        <div class="left">
            <div class="mapView">
                <!-- Load svg -->
                <div id="canvas">
                <?php
                    //$dummyView = new DummyView();
                    $dummyView = new MapView();
                    $dummyView->printDummyMap();
                ?>
                </div>
            </div><!--end .mapView-->
        </div> <!-- end .left -->
        <div class="right">
            <h2>Aktionen</h2>
            <input type="button" name="MapRandom" value="Nächste Map" />
            <input type="button" name="MapSubmit" value="Start" />
        </div><!--end .right-->

        <div class="clear"></div>
        <?php
        }

        function getViewType() {
            return self::ViewType;
        }


    }


?>

