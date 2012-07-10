<?php
    class PlayStateView implements IStateView, IEventListener {

        const ViewType = "PlayStateView";

        function handleEvent(IEvent $event) {
            // TODO: Implement handleEvent() method.
        }

        function printView() {
            ?>
        <div class="left">
            <div class="mapView">
                <!-- Load svg -->
                <div id="canvas">
                <?php
                $dummyView = new MapView();
                $dummyView->printDummyMap();
                ?>
                </div>

            </div><!--end .mapView-->

            <!-- <input type="button" name="NextPlayerSubmit" value="NextPlayer" /> -->
            <div id="incidentView"></div>
        </div> <!-- end .left -->
        <div class="right">
            <h2>Aktionen</h2>
            <div class="infoBox">
                <ol id="actionContainer">
                    <li class="activeAction">Geld verteilen</li>
                    <li>Shoppen</li>
                    <li>Auf Bank einzahlen</li>
                    <li>Nächster Spieler</li>
                </ol>
                <input type="button" name="actionButton" value="Aktion weiter" id="actionButton" disabled="disabled"/>
            </div>
            <h2 class="bank">Bank</h2>
            <div class="infoBox">
                <?php
                    $bankView = new BankView();
                    $bankView->printBankView();
                ?>
            </div>
            <br />
            <h2>Info</h2>
            <div class="infoBox last">
                <ul id="infoAI">
                </ul>
            </div>
        </div><!--end .right-->

        <div class="clear"></div>
        <?php
        }

        function getViewType() {
            return self::ViewType;
        }
    }
