<?php

    require_once("../config/config.php");

    class DummyView{

        private $regionsArray;

        public function printDummyMap(){
            $regions = $_SESSION['map']->getRegions();
            $this->regionsArray = $regions;

            ?>
        <script type="text/javascript">
        var paper = Raphael('canvas',700,800);
        var circle;

        circle = paper.circle(100,100,20).attr('fill', "<?php echo $regions[0]->getColor(); ?>");
            circle.node.setAttribute('data-region', '<?php echo $regions[0]->getRegionId(); ?>');
            //circle.node.setAttribute('data-value','<?php echo $regions[0]->getPayment()->getValue(); ?>');
            circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[0]->getPlayerId(); ?>);
        text = paper.text(100,100,'<?php echo $this->calculateRegionValue(0) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[0]->getRegionId(); ?>');

        circle = paper.circle(200,390,30).attr('fill', "<?php echo $regions[1]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[1]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[1]->getPlayerId(); ?>);
        text = paper.text(200,390,'<?php echo $this->calculateRegionValue(1) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[1]->getRegionId(); ?>');

        circle = paper.circle(100,200,40).attr('fill', "<?php echo $regions[2]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[2]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[2]->getPlayerId(); ?>);
        text = paper.text(100,200,'<?php echo $this->calculateRegionValue(2) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[2]->getRegionId(); ?>');

        circle = paper.circle(200,200,50).attr('fill', "<?php echo $regions[3]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[3]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[3]->getPlayerId(); ?>);
        text = paper.text(200,200,'<?php echo $this->calculateRegionValue(3) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[3]->getRegionId(); ?>');

        circle = paper.circle(200,300,30).attr('fill', "<?php echo $regions[4]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[4]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[4]->getPlayerId(); ?>);
        text = paper.text(200,300,'<?php echo $this->calculateRegionValue(4) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[4]->getRegionId(); ?>');

        circle = paper.circle(150,300,20).attr('fill', "<?php echo $regions[5]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[5]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[5]->getPlayerId(); ?>);
        text = paper.text(150,300,'<?php echo $this->calculateRegionValue(5) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[5]->getRegionId(); ?>');

        circle = paper.circle(230,450,30).attr('fill', "<?php echo $regions[6]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[6]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[6]->getPlayerId(); ?>);
        text = paper.text(230,450,'<?php echo $this->calculateRegionValue(6) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[6]->getRegionId(); ?>');

        circle = paper.circle(300,400,40).attr('fill', "<?php echo $regions[7]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[7]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[7]->getPlayerId(); ?>);
        text = paper.text(300,400,'<?php echo $this->calculateRegionValue(7) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[7]->getRegionId(); ?>');

        circle = paper.circle(300,500,50).attr('fill', "<?php echo $regions[8]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[8]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[8]->getPlayerId(); ?>);
        text = paper.text(300,500,'<?php echo $this->calculateRegionValue(8) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[8]->getRegionId(); ?>');

        circle = paper.circle(400,500,20).attr('fill', "<?php echo $regions[9]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[9]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[9]->getPlayerId(); ?>);
        text = paper.text(400,500,'<?php echo $this->calculateRegionValue(9) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[9]->getRegionId(); ?>');

        circle = paper.circle(400,600,30).attr('fill', "<?php echo $regions[10]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[10]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[10]->getPlayerId(); ?>);
        text = paper.text(400,600,'<?php echo $this->calculateRegionValue(10) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[10]->getRegionId(); ?>');

        circle = paper.circle(400,700,40).attr('fill', "<?php echo $regions[11]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[11]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'regionOfPlayer'+<?php echo $regions[11]->getPlayerId(); ?>);
        text = paper.text(400,700,'<?php echo $this->calculateRegionValue(11) ?>');
                text.node.setAttribute('data-text', '<?php echo $regions[11]->getRegionId(); ?>');

        </script>
    <?php }

        function calculateRegionValue($regionID) {
            return $this->regionsArray[$regionID]->getPayment()->getValue() * $this->regionsArray[$regionID]->getPayment()->getCurrencyTranslation() ;
        }
    }
?>