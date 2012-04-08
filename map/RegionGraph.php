<?php

class RegionGraph{

    private $numOfRegions;
    private $graphNodes;

    public function __construct($numOfRegions){
        $this->numOfRegions = $numOfRegions;
        $this->graphNodes = array();
    }

    public function generateGraph(){

        for($i = 0; $i < $this->numOfRegions; ++$i){
            array_push($this->graphNodes, new GraphNode($i));
        }

    }
}

class GraphNode{

    private $id;
    private $neighbours;

    public function __construct($id, $neigbours = null){
        $this->$id = $id;
        $this->neighbours = $neigbours;
    }

    public function setNeighbours($neighbours){
        $this->neighbours = $neighbours;
    }

}
?>