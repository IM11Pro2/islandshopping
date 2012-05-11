<?php

    require_once("../config/config.php");

    class DummyView {

        private $regionsArray;
        private $coordinates;

        public function __construct() {
            $this->coordinates = array(array('x'     => 100,
                                             'y'     => 100,
                                             'radius'=> 20), array('x'     => 200,
                                                                   'y'     => 390,
                                                                   'radius'=> 30), array('x'     => 100,
                                                                                         'y'     => 200,
                                                                                         'radius'=> 40), array('x'     => 200,
                                                                                                               'y'     => 200,
                                                                                                               'radius'=> 50), array('x'     => 200,
                                                                                                                                     'y'     => 300,
                                                                                                                                     'radius'=> 30), array('x'     => 150,
                                                                                                                                                           'y'     => 300,
                                                                                                                                                           'radius'=> 20), array('x'     => 230,
                                                                                                                                                                                 'y'     => 450,
                                                                                                                                                                                 'radius'=> 30), array('x'     => 300,
                                                                                                                                                                                                       'y'     => 400,
                                                                                                                                                                                                       'radius'=> 40), array('x'     => 300,
                                                                                                                                                                                                                             'y'     => 500,
                                                                                                                                                                                                                             'radius'=> 50), array('x'     => 400,
                                                                                                                                                                                                                                                   'y'     => 500,
                                                                                                                                                                                                                                                   'radius'=> 20), array('x'     => 400,
                                                                                                                                                                                                                                                                         'y'     => 600,
                                                                                                                                                                                                                                                                         'radius'=> 30), array('x'     => 400,
                                                                                                                                                                                                                                                                                               'y'     => 700,
                                                                                                                                                                                                                                                                                               'radius'=> 40));
        }


        public function printDummyMap() {
            $regions = $_SESSION['map']->getRegions();
            $this->regionsArray = $regions;

            ?>
        <script type="text/javascript">
            var paper = Raphael('canvas', 700, 800);
            var circle;
            var text;
                <?php

                for($i = 0; $i < NUM_OF_REGIONS; $i++) {
                    ?>

                    circle = paper.circle(  <?php echo $this->coordinates[$i]['x']?>,
                                            <?php echo $this->coordinates[$i]['y']?>,
                                            <?php echo $this->coordinates[$i]['radius']?>)
                        .attr('fill', "<?php echo $regions[$i]->getColor(); ?>");

                    circle.data('region', <?php echo $regions[$i]->getRegionId(); ?>);
                    circle.data('regionOfPlayer', <?php echo $regions[$i]->getPlayerId(); ?>);

                    //circle.node.setAttribute('data-region', '<?php /*echo $regions[$i]->getRegionId();*/ ?>');
                    //circle.node.setAttribute('data-value','<?php /*echo $region->getPayment()->getValue();*/ ?>');
                    //circle.node.setAttribute('class', 'regionOfPlayer' +<?php /*echo $regions[$i]->getPlayerId();*/ ?>);

                    text = paper.text(  <?php echo $this->coordinates[$i]['x']?>,
                                        <?php echo $this->coordinates[$i]['y']?>,
                                        '<?php echo $this->calculateRegionValue($regions[$i]->getRegionId()) ?>');
                    text.data('text', '<?php echo $regions[$i]->getRegionId(); ?>');
                    //text.node.setAttribute('data-text', '<?php /*echo $regions[$i]->getRegionId(); */?>')

                    <?php
                }
                ?>


        </script>
        <?php
        }

        function calculateRegionValue($regionID) {
            return $this->regionsArray[$regionID]->getPayment()->getValue() * $this->regionsArray[$regionID]->getPayment()->getCurrencyTranslation();
        }
    }

?>