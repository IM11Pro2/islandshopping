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


                <?php


                $layerElements = $this->xmlNodes->g;

                foreach($layerElements as $childNode){ // layer of the svg

                    $attributes = $childNode->attributes();

                    if($attributes['id'] == "regions"){



                        $parentGroupNode = $childNode;


                        foreach($parentGroupNode->children() as $regionNode){

                            if($regionNode->getName() == "path"){ // draw the region-paths

                                // save style from the svg to fill the raphaeljs attributes
                                $pathAttributes = $regionNode->attributes();
                                $this->drawPath($pathAttributes);

                            }
                            else if($regionNode->getName() == "g"){ // draw the paths of groups
                                $pathGroup = $regionNode;
                                $this->drawGroup($pathGroup);

                            }
                        }


                        $transformation = null;

                        if($attributes['transform'] != null){
                            $transformation = $this->parseTransformation($attributes['transform']);
                        }
                        if($transformation != null){
                        ?>
                        // modify transformation for the region set
                        regionSet.transform("<?php echo $transformation; ?>");

                        regionSet.forEach(function(el){
                            if(el.type == "set"){
                                el.forEach(function(elm){

                                    if(elm.type == "set"){

                                        elm.forEach(function(islands){
                                            var m = islands.matrix.clone();
                                            m.add(transformationMatrix);
                                            islands.transform(m.toTransformString());
                                        });

                                    }
                                    else{
                                        var m = elm.matrix.clone();
                                        m.add(transformationMatrix);
                                        elm.transform(m.toTransformString());
                                    }


                                });



                            }
                        });
                            <?php
                        }

                    }

                    }

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


        private function parseIdAndTitle($inputString){
            // split id and name from the input eg. 00_Evros
            $inputString = explode("_", $inputString);

            // remove the first digit if its a zero
            $inputString[0] = intval($inputString[0]);

            // return the identifier array [0] is id, [1] is the name of the region
            return $inputString;

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

        public function drawPath($pathAttributes){
            if($this->style == null){
                $this->style = $this->parseStyle($pathAttributes);
            }
            $pathCoordinates = $pathAttributes['d'];

            $regionId = null;
            $regionTitle = null;
            $region = null;
            if($pathAttributes['id'] != null){
                $uniqueName = $this->parseIdAndTitle($pathAttributes['id']);
                $regionId = $uniqueName[0];
                $regionTitle = $uniqueName[1];
                $region = $this->regions[$regionId];
            }

        ?>
            path = paper.path("<?php echo $pathCoordinates; ?>");
            path.attr("<?php echo $this->style; ?>");
            path.attr('fill', "<?php  echo isset($region) ? $region->getColor() : "#000000"; ?>");

            path.data('region', <?php echo $regionId; ?>);
            path.data('regionOfPlayer', <?php  echo isset($region) ? $region->getPlayerId() : "-1"; ?>);

            path.attr("title", "<?php echo $regionTitle; ?>");
            regionSet.push(path);
        <?php
        }

        public function drawGroup($regionPaths){

        ?>
            var groupSet = paper.set();

        <?php
            $groupAttributes = $regionPaths->attributes();

            $transformation = null;
            if($groupAttributes['transform'] != null){
                $transformation = $this->parseTransformation($groupAttributes['transform']);
            }

            foreach($regionPaths->children() as $regionPath ){


                if($regionPath->getName() == "g"){
                    //island group
                    ?>
                    var islandGroup = paper.set();
                   <?php
                    $islands = $regionPath->children();


                    foreach($islands as $islandPath){

                        $islandAttributes = $islandPath->attributes();
                        $islandCoords = $islandAttributes['d'];
                        ?>
                        path = paper.path("<?php echo $islandCoords; ?>");
                        path.attr("<?php echo $this->style; ?>");
                        islandGroup.push(path);
                        <?php
                    }

                    ?>
                        groupSet.push(islandGroup);
                    <?php


                }
                else if($regionPath->getName() == "path"){

                    // region
                    $pathAttributes = $regionPath->attributes();

                    if($this->style == null){
                        $this->style = $this->parseStyle($pathAttributes);
                    }
                    $pathCoordinates = $pathAttributes['d'];



                    ?>
                        path = paper.path("<?php echo $pathCoordinates; ?>");
                        path.attr("<?php echo $this->style; ?>");
                        groupSet.push(path);

                    <?php

                    if($transformation != null){
                        ?>
                        transformationMatrix = Raphael.matrix(  <?php echo $transformation[0]; ?>,
                                                                    <?php echo $transformation[1]; ?>,
                                                                    <?php echo $transformation[2]; ?>,
                                                                    <?php echo $transformation[3]; ?>,
                                                                    <?php echo $transformation[4]; ?>,
                                                                    <?php echo $transformation[5]; ?>);

                        <?php
                    }

                    $regionId = null;
                    $regionTitle = null;
                    $region = null;
                    if($groupAttributes['id'] != null){
                        $uniqueName = $this->parseIdAndTitle($groupAttributes['id']);
                        $regionId = $uniqueName[0];
                        $regionTitle = $uniqueName[1];
                        $region = $this->regions[$regionId];
                    }


                }

            }
            ?>
            groupSet.attr('fill', "<?php  echo isset($region) ? $region->getColor() : "#000000"; ?>");

            groupSet.data('region', <?php echo $regionId; ?>);
            groupSet.data('regionOfPlayer', <?php  echo isset($region) ? $region->getPlayerId() : "-1"; ?>);

            groupSet.attr("title", "<?php echo $regionTitle; ?>");
            regionSet.push(groupSet);

    <?php
        }
        private function parseElement($svgElement){

            if($svgElement->getName() == "g"){
                foreach($svgElement->children() as $svgChildElement){

                    parseElement($svgChildElement);

                }
            }
            else if($svgElement->getName() == "path"){

            }

        }

        private function parsePath(){

        }

        /*private function drawPath(){

        }*/

    }

?>