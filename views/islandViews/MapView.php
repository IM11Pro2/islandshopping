<?php

    require_once("../config/config.php");

    class MapView {

        private $regions;
        private $svgIterator;

        public function __construct() {
            $this->svgIterator = simplexml_load_file("../views/islandViews/greece.svg");
        }


        public function printMap() {
            $this->regions = $_SESSION['map']->getRegions();
            ?>
        <script type="text/javascript">
            var paper = Raphael('canvas', 700, 800);

            var regionSet = paper.set();
            var textSet = paper.set();
            var path; // last path in hierachy
            var text;


            function drawRegionPath(pathDescription, transformation, pathId, pathName, pathStyle, playerId){
                path = paper.path(pathDescription);
                path.transform(transformation);
                path.attr('title', pathName);
                path.attr(pathStyle);
                path.data('region', pathId);
                path.data('regionOfPlayer', playerId);
                regionSet.push(path);
            }

            function drawText(path, paymentValue){
                var bBox = path.getBBox();
                text = paper.text(bBox.x + bBox.width/2, bBox.y + bBox.height/2 , '');
                text.attr({'font-size' : 16,'font-family' : "QlassikBold"});

                // before key 'text' and regionId, now pathId
                text.data('pathId', path.id);

                // before key 'value', now paymentValue
                text.data('paymentValue', paymentValue);
                textSet.push(text);
            }

            function bringTextToFront(){
                textSet.toFront();
            }

                <?php

                // iterate over all nodes in first hierachie
                foreach($this->svgIterator as $elementName => $element ){
                    $groupAttributes = $element->attributes();
                    if($elementName == "g" && $groupAttributes['id'] == "regions"){ // find the groupnode with the regions paths

                        $transformation = $this->parseTransformation($groupAttributes['transform']);
                        $index = 0;
                        foreach($element as $pathElement){ // iterate over all paths in the group
                            $regionPathParams = array();

                            foreach($pathElement->attributes() as $attribute){
                                array_push($regionPathParams, "".$attribute."");
                            }
                            $regionPathParams[1] = $this->parseIdAndTitle($regionPathParams[1]);
                            $regionPathParams[2] = $this->parseStyle($regionPathParams[2], $this->regions[$index]->getColor());
                            ?>

                            drawRegionPath( "<?php echo $regionPathParams[0]; ?>" ,
                                        "<?php echo $transformation; ?>",
                                        <?php echo $regionPathParams[1][0]; ?>,
                                        "<?php echo $regionPathParams[1][1]; ?>",
                                        <?php echo $regionPathParams[2]; ?>,
                                        <?php echo $this->regions[$index]->getPlayerId(); ?>
                            );

                            drawText(path, "<?php echo $this->regions[$index]->getPayment(); ?>");
                            <?php
                            ++$index;
                        }
                    }
                }
            ?>
            bringTextToFront();
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

        private function parseStyle($style, $color = null){

            if(isset($color)){
                $style = str_replace("fill:none", "fill:".$color, $style);
            }
            // create an js style object from the svg
            $style = "{".str_replace(";", ",", $style)."}";
            return str_replace(
                        array("{", ":", ",", "}"), // search
                        array("{'", "':'", "','", "'}" ), // replace
                        $style);
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
            return ""; // identity matrix

        }


    }

?>