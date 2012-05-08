<?php

    class Map {

        //Linked List for Regions?
        private $regions;
        private $regionGraph;
        private $playerList;

        public function __construct($playerList) {
            $this->playerList = $playerList;
            $this->regions = array();
            $this->regionGraph = new RegionGraph();
            $this->regionGraph->generateGraph();
            $_SESSION['map'] = $this;
        }

        public function randomizeRegions() {
            $numberOfPlayers = count($this->playerList);
            $regionsPerPlayer = floor(NUM_OF_REGIONS / $numberOfPlayers);

            for($i = 0; $i < ($numberOfPlayers * $regionsPerPlayer); ++$i) {
                // select a random player-id
                $playerId = mt_rand(0, $numberOfPlayers - 1);

                while(true) {
                    //lookup how many regions belong to the player
                    $regionsOfPlayer = $this->getRegionsOfPlayer($playerId);

                    if($regionsOfPlayer < $regionsPerPlayer) {
                        $region = new Region($this->playerList[$playerId]);
                        $region->initNeighbourRegions($this->regionGraph->getNode($region->getRegionId()));
                        array_push($this->regions, $region);
                        break;
                    }

                    // if the random selected player has allready its regions, select the next player
                    $playerId = ($playerId + 1) % $numberOfPlayers;
                }
            }

            if(($numberOfPlayers * $regionsPerPlayer) < NUM_OF_REGIONS) {

                $restRegions = NUM_OF_REGIONS - ($numberOfPlayers * $regionsPerPlayer);
                $playerId = mt_rand(0,($numberOfPlayers-1));
                for($i = 0; $i < $restRegions; ++$i) {
                    $region = new Region($this->playerList[$playerId]);
                    $region->initNeighbourRegions($this->regionGraph->getNode($region->getRegionId()));
                    array_push($this->regions, $region);
                    $playerId++;
                }
            }
        }

        // search for regions of a player
        // maybe its better to make an array of regions in player-class
        private function getRegionsOfPlayer($playerId) {
            $regionsOfPlayer = 0;
            foreach($this->regions as $region) {
                if($region->getPlayerId() == $playerId) {
                    $regionsOfPlayer++;
                }
            }

            return $regionsOfPlayer;
        }

        public function getRegions(){
            return $this->regions;
        }
    }

?>