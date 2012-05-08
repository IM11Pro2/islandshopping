<?php

    require_once("../config/config.php");

    class DummyView{


        public function printDummyMap(){
            ?>
        <svg version="1.1" baseProfile="full" width="800" height="800">

            <circle id="region0" class="region" cx="100" cy="100" r="20" fill="red" />
            <circle id="region1" class="region" cx="200" cy="390" r="30" fill="blue"/>
            <circle id="region2" class="region" cx="100" cy="200" r="40" fill="yellow"/>
            <circle id="region3" class="region" cx="200" cy="200" r="50" fill="magenta"/>
            <circle id="region4" class="region" cx="200" cy="300" r="30" fill="orange"/>
            <circle id="region5" class="region" cx="150" cy="300" r="20" fill="green"/>
            <circle id="region6" class="region" cx="230" cy="450" r="30" fill="cyan"/>
            <circle id="region7" class="region" cx="300" cy="400" r="40" fill="grey"/>
            <circle id="region8" class="region" cx="300" cy="500" r="50" fill="darkgrey"/>
            <circle id="region9" class="region" cx="400" cy="500" r="20" fill="purple"/>
            <circle id="region10" class="region" cx="400" cy="600" r="30" fill="brown"/>
            <circle id="region11" class="region" cx="400" cy="700" r="40" fill="black"/>

        </svg>

    <?php }
        //TODO default farben verwenden und dann mittels raphael nach erfolgtem request farben setzen
    }
?>