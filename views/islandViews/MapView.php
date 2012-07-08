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
            //var group;

            function setMatrices(groupSet, a,b,c,d,e,f){
                param = {a : a, b : b, c : c, d : d, e : e, f : f};
                groupSet.forEach(function(el, param){
                    el.matrix.add(param.a,param.b,param.c,param.d,param.e,param.f);
                });
            }

                <?php


                $this->parseElement($this->xmlNodes, 0);


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
            $outputTransformation = str_replace("translate(","", $inputTransformation, $count);

            if($count > 0){
                $outputTransformation = str_replace(")","", $outputTransformation);
                $outputTransformation = explode(",", $outputTransformation);

                for($i = 0; $i < count($outputTransformation); ++$i){
                    $outputTransformation[$i] = floatval($outputTransformation[$i]);
                }
                return $outputTransformation;
            }

            $outputTransformation = str_replace("matrix(","", $inputTransformation, $count);
            if($count > 0){
                $outputTransformation = str_replace(")","", $outputTransformation);
                $outputTransformation = explode(",", $outputTransformation);

                for($i = 0; $i < count($outputTransformation); ++$i){
                    $outputTransformation[$i] = floatval($outputTransformation[$i]);
                }

                return $outputTransformation;
            }


        }



        private function parseElement($svgElement, $index, $groupName = "group"){

            if($svgElement->getName() == "svg"){
                foreach($svgElement->children() as $svgChildElement){
                    $this->parseElement($svgChildElement, ++$index);
                }
            }else if($svgElement->getName() == "g"){

                echo "paper.setStart();\n";
                foreach($svgElement->children() as $svgChildElement){
                    $childAttributes = $svgChildElement->attributes();
                    $childId = $this->parseIdAndTitle($childAttributes['id']);
                    $this->parseElement($svgChildElement, ++$index, isset($childId[1]) ? $childId[1] : "group");
                }
                echo "var ".$groupName."".$index." = paper.setFinish();\n";

                $groupAttributes = $svgElement->attributes();
                $transformation = $this->parseTransformation($groupAttributes['transform']);

                if(isset($transformation)){
                    $matrixParam = count($transformation) > 2 ? implode(",",$transformation) : "1,0,0,1,".$transformation[0].",".$transformation[1] ;

                    ?>
                    console.log("<?php echo $groupName."".$index; ?>");
                    console.log(<?php echo $groupName."".$index; ?>);
                    //setMatrices(<?php echo $groupName."".$index.",".$matrixParam; ?>);
                <?php
                }


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
        <?php

        }

    }

?>