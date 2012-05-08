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
                <?php
                $dummyView = new DummyView();
                $dummyView->printDummyMap();
                ?>


            </div><!--end .mapView-->

            <input type="button" name="PlaySubmit" value="NextPlayer">
        </div> <!-- end .left -->
        <div class="right">
            <h2>Bank</h2>

            <div class="bankView">
                <?php
                    $bankView = new BankView();
                    $bankView->printBankView();
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
