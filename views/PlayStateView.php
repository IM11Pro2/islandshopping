<?php
    class PlayStateView implements IStateView, IEventListener {

        const ViewType = "PlayStateView";

        function handleEvent(IEvent $event) {
            // TODO: Implement handleEvent() method.
        }

        function printView() {
            ?>


        <h1>PLAY STATE VIEW</h1>


        <input type="button" name="PlaySubmit" value="Start">

        <?php
        }

        function getViewType() {
            return self::ViewType;
        }
    }
