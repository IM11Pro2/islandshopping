<?php
    class PlayStateView implements IStateView, IEventListener {

        const ViewType = "PlayStateView";

        function handleEvent(IEvent $event) {
            // TODO: Implement handleEvent() method.
        }

        function printView() {
            ?>
        <h1>PLAY STATE VIEW</h1>

        <div class="left">
            <h2>Map</h2>
            <div class="mapView">
                <!-- Load svg -->
                <div id="canvas">
                <?php
                $dummyView = new DummyView();
                $dummyView->printDummyMap();
                ?>
                </div>

            </div><!--end .mapView-->

            <input type="button" name="NextPlayerSubmit" value="NextPlayer">
        </div> <!-- end .left -->
        <div class="right">
            <h2>Bank</h2>

            <div class="bankView">
                <?php
                    $bankView = new BankView();
                    $bankView->printBankView();
                ?>
            </div>
            <br />
            <h2>Info</h2>
            <div class="infoView">
                <ul id="infoAI">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
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
