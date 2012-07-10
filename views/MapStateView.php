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
            <div class="infoBox">
            <input type="button" name="MapRandom" value="Nächste Map" />
            <input type="button" name="MapSubmit" value="Start" />
            </div>
            <h2>Spieler</h2>
            <div class="infoBox">
            <?php
                foreach($_SESSION['activePlayers'] as $player){
                    if($player->getPlayerId() == 0){

                        echo "<span style=\"background:".$player->getCountry()->getColor().";\">&nbsp;&nbsp;</span> Spieler Land: ".$player->getCountry()->getName()."<br/>";

                    }
                    else{
                        echo "<span style=\"background:".$player->getCountry()->getColor().";\">&nbsp;&nbsp;</span> KI".($player->getPlayerId())." Land: ".$player->getCountry()->getName()."<br/>";
                    }
                }
            ?>
            </div>
        </div><!--end .right-->

        <div class="clear"></div>
        <?php
        }

        function getViewType() {
            return self::ViewType;
        }


    }


?>

