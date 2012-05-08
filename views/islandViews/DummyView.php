<?php

    require_once("../config/config.php");

    class DummyView{


        public function printDummyMap(){
            $regions = $_SESSION['map']->getRegions();
            ?>
        <svg version="1.1" baseProfile="full" width="800" height="800">

            <circle id="region0" class="region" cx="100" cy="100" r="20" fill="<?php echo $regions[0]->getColor(); ?>" />
            <circle id="region1" class="region" cx="200" cy="390" r="30" fill="<?php echo $regions[1]->getColor(); ?>"/>
            <circle id="region2" class="region" cx="100" cy="200" r="40" fill="<?php echo $regions[2]->getColor(); ?>"/>
            <circle id="region3" class="region" cx="200" cy="200" r="50" fill="<?php echo $regions[3]->getColor(); ?>"/>
            <circle id="region4" class="region" cx="200" cy="300" r="30" fill="<?php echo $regions[4]->getColor(); ?>"/>
            <circle id="region5" class="region" cx="150" cy="300" r="20" fill="<?php echo $regions[5]->getColor(); ?>"/>
            <circle id="region6" class="region" cx="230" cy="450" r="30" fill="<?php echo $regions[6]->getColor(); ?>"/>
            <circle id="region7" class="region" cx="300" cy="400" r="40" fill="<?php echo $regions[7]->getColor(); ?>"/>
            <circle id="region8" class="region" cx="300" cy="500" r="50" fill="<?php echo $regions[8]->getColor(); ?>"/>
            <circle id="region9" class="region" cx="400" cy="500" r="20" fill="<?php echo $regions[9]->getColor(); ?>"/>
            <circle id="region10" class="region" cx="400" cy="600" r="30" fill="<?php echo $regions[10]->getColor(); ?>"/>
            <circle id="region11" class="region" cx="400" cy="700" r="40" fill="<?php echo $regions[11]->getColor(); ?>"/>

        </svg>

    <?php }
        //TODO ?default farben verwenden und dann mittels raphael nach erfolgtem request farben setzen
    }
?>