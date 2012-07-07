<?php

    require_once("../config/config.php");

    class MapView {

        private $regions;
        private $xmlNodes;
        private $style;

        public function __construct() {
            $this->xmlNodes = simplexml_load_file("../views/islandViews/greece.svg");
            $this->style = null;
        }


        public function printDummyMap() {
            $this->regions = $_SESSION['map']->getRegions();
            ?>
        <script type="text/javascript">
            var paper = Raphael('canvas', 700, 800);

            var regionSet = paper.set();
            var transformationMatrix;
            var path;
            var text;
            var group;


                <?php


                $this->parseElement($this->xmlNodes);


                //for($i = 0; $i < NUM_OF_REGIONS; $i++) {
                    ?>
 /*
                    circle = paper.circle(  <?php //echo $this->coordinates[$i]['x']?>,
                                            <?php //echo $this->coordinates[$i]['y']?>,
                                            <?php //echo $this->coordinates[$i]['radius']?>)
                        .attr('fill', "<?php //echo $regions[$i]->getColor(); ?>");

                    circle.data('region', <?php //echo $regions[$i]->getRegionId(); ?>);
                    circle.data('regionOfPlayer', <?php //echo $regions[$i]->getPlayerId(); ?>);

                    circle.attr("title", "<?php //echo $regions[$i]->getRegionId(); ?>");

                    text = paper.text(  <?php //echo $this->coordinates[$i]['x']?>,
                                        <?php //echo $this->coordinates[$i]['y']?>, '');
                    text.data('text', '<?php //echo $regions[$i]->getRegionId(); ?>');
                    text.data('value', '<?php //echo $regions[$i]->getPayment()->__toString(); ?>');
 */
                    <?php
                //}
                ?>


        </script>
        <?php
        }


        private function parseIdAndTitle($inputId){
            // split id and name from the input eg. 00_Evros
            $inputId = explode("_", $inputId);

            // remove the first digit if its a zero
            $inputId[0] = intval($inputId[0]);

            // return the identifier array [0] is id, [1] is the name of the region
            return $inputId;

        }

        private function parseStyle($pathAttributes){
            // create an js style object from the svg
            return "{".str_replace(";", ",", $pathAttributes['style'])."}";
        }

        private function parseTransformation($inputTransformation){

            $count = 0;
            $inputTransformation = str_replace("translate(","", $inputTransformation, $count);

            if($count > 0){
                $inputTransformation = str_replace(")","", $inputTransformation);
                return "t".$inputTransformation;
            }

            $inputTransformation = str_replace("matrix(","", $inputTransformation, $count);
            if($count > 0){
                $inputTransformation = str_replace(")","", $inputTransformation);
                $inputTransformation = explode(",", $inputTransformation);

                for($i = 0; $i < count($inputTransformation); ++$i){
                    $inputTransformation[$i] = floatval($inputTransformation[$i]);
                }

                return $inputTransformation;
            }
            return ""; // einheitsmatrix

        }



        private function parseElement($svgElement, $groupName = "group"){

            if($svgElement->getName() == "svg"){
                foreach($svgElement->children() as $svgChildElement){
                    $this->parseElement($svgChildElement);
                }
            }
            if($svgElement->getName() == "g"){


                echo "paper.setStart();";

                foreach($svgElement->children() as $svgChildElement){
                    $groupAttributes = $svgChildElement->attributes();
                    $groupId = $this->parseIdAndTitle($groupAttributes['id']);
                    $this->parseElement($svgChildElement, isset($groupId) ? $groupId[1] : "group");
                }

                echo "var ".$groupName." = paper.setFinish();";

            }

            else if($svgElement->getName() == "path"){

                $this->parsePath($svgElement);

            }

        }

        private function parsePath($svgElement){
            $pathAttributes = $svgElement->attributes();
            $style = $this->parseStyle($pathAttributes);
            $coords = $pathAttributes['d'];
            $this->drawPath($coords, $style);

        }

        private function drawPath($coords, $style){ ?>
            path = paper.path("<?php echo $coords; ?>");
            path.attr("<?php echo $style; ?>");

        <?php }

    }

?>