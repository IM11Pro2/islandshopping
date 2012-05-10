<?php

    require_once("../config/config.php");

    class DummyView{


        public function printDummyMap(){
            $regions = $_SESSION['map']->getRegions();

            ?>
        <script type="text/javascript">
        var paper = Raphael('canvas',700,800);

        var circle;
        circle = paper.circle(100,100,20).attr('fill', "<?php echo $regions[0]->getColor(); ?>");
            circle.node.setAttribute('data-region', '<?php echo $regions[0]->getRegionId(); ?>');
            circle.node.setAttribute('class', 'region');
        text = paper.text(100,100,'');
                text.node.setAttribute('data-text', '<?php echo $regions[0]->getRegionId(); ?>');

        circle = paper.circle(200,390,30).attr('fill', "<?php echo $regions[1]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[1]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(200,390,'');
                text.node.setAttribute('data-text', '<?php echo $regions[1]->getRegionId(); ?>');

        circle = paper.circle(100,200,40).attr('fill', "<?php echo $regions[2]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[2]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(100,200,'');
                text.node.setAttribute('data-text', '<?php echo $regions[2]->getRegionId(); ?>');

        circle = paper.circle(200,200,50).attr('fill', "<?php echo $regions[3]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[3]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(200,200,'');
                text.node.setAttribute('data-text', '<?php echo $regions[3]->getRegionId(); ?>');

        circle = paper.circle(200,300,30).attr('fill', "<?php echo $regions[4]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[4]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(200,300,'');
                text.node.setAttribute('data-text', '<?php echo $regions[4]->getRegionId(); ?>');

        circle = paper.circle(150,300,20).attr('fill', "<?php echo $regions[5]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[5]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(150,300,'');
                text.node.setAttribute('data-text', '<?php echo $regions[5]->getRegionId(); ?>');

        circle = paper.circle(230,450,30).attr('fill', "<?php echo $regions[6]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[6]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(230,450,'');
                text.node.setAttribute('data-text', '<?php echo $regions[6]->getRegionId(); ?>');

        circle = paper.circle(300,400,40).attr('fill', "<?php echo $regions[7]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[7]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(300,400,'');
                text.node.setAttribute('data-text', '<?php echo $regions[7]->getRegionId(); ?>');

        circle = paper.circle(300,500,50).attr('fill', "<?php echo $regions[8]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[8]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(300,500,'');
                text.node.setAttribute('data-text', '<?php echo $regions[8]->getRegionId(); ?>');

        circle = paper.circle(400,500,20).attr('fill', "<?php echo $regions[9]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[9]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(400,500,'');
                text.node.setAttribute('data-text', '<?php echo $regions[9]->getRegionId(); ?>');

        circle = paper.circle(400,600,30).attr('fill', "<?php echo $regions[10]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[10]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(400,600,'');
                text.node.setAttribute('data-text', '<?php echo $regions[10]->getRegionId(); ?>');

        circle = paper.circle(400,700,40).attr('fill', "<?php echo $regions[11]->getColor(); ?>");
                circle.node.setAttribute('data-region', '<?php echo $regions[11]->getRegionId(); ?>');
                circle.node.setAttribute('class', 'region');
        text = paper.text(400,700,'');
                text.node.setAttribute('data-text', '<?php echo $regions[11]->getRegionId(); ?>');

        </script>
    <?php }
    }
?>