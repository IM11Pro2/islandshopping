<?php

    class RegionGraph {

        private static $relations;

        private $graphNodes;

        public function __construct() {
            $this->graphNodes = array();

        }

        public function generateGraph() {
            if(!isset(self::$relations)) {
                self::$relations = self::initRelations();
            }

            // generate all the nodes
            for($i = 0; $i < NUM_OF_REGIONS; ++$i) {
                    array_push($this->graphNodes, new GraphNode($i));
            }


            // connect nodes together
            for($i = 0; $i < NUM_OF_REGIONS; ++$i) {
                $actualNode = $this->graphNodes[$i]; // get the node with the id $i

                $neighbours = self::$relations[$i]; // get the array with the neighbour ids

                for($j = 0; $j < count($neighbours); ++$j) {

                    $actualNode->addAsNeighbour( $this->graphNodes[$neighbours[$j]] );

                }
            }

        }

        public function getNode($regionId) {
            return $this->graphNodes[$regionId];
        }

        public static function initRelations() {
            $lookUp = array( /*
            array(1,68), // Evras
            array(0,2), // Rodopol
            array(1,4,5), // Xanthi
            array(4,68), // Thasos
            array(2,3,5,6), // Kavala
            array(2,4,6), // Drama
            array(5,4,7,8), // Serres
            array(6,8,11), // Kilkis
            array(6,7,11,12,9), // Thessaloniki
            array(8,10), // Chalkidiki
            array(9,68), // Athos
            array(7,8,12,14,15), // Pella
            array(8,11,13,15), // Imathia
            array(12,15,16), // Piera
            array(11,15,18), // Florina
            array(11,12,13,14,16,18,19), // Kozani
            array(13,15,17,19,20,21), // Larisa
            array(16,31,34,35), // Magnisia
            array(14,15,19,22), // Kastoria
            array(15,16,18,20,22), // Grevena
            array(16,19,21,22,26), // Trikala
            array(16,20,26,27,30,31), // Karditsa
            array(18,19,20,23,25,26), //Ioannina
            array(22,25,24), // Thesprotia
            array(23,25), // Korfu
            array(22,23,24,26,27), // Preveza
            array(20,21,22,23,25,27), // Arta
            array(21,25,26,28,29,30,31,32,42,69), // Atolien
            array(27,29,69), // Lefkada
            array(27,28,69), // Ithaka
            array(21,27,31), // Evrytania
            array(17,21,30,32,33,34), //Fthiotida
            array(27,31,33,42), // Fokida
            array(31,32,34,36,38), // Bootien
            array(17,31,33,35,36,65,66,67,68), // Euboa
            array(17,34), //Sporaden
            array(33,34,37,38,59), // Ostattika
            array(36,38,39), // Attika
            array(33,36,37,40), // Westattika
            array(37,41,46,47), // Inseln
            array(38,41,42,44), // Korinthia
            array(39,40,44), // Argolis
            array(27,32,40,43,44), // Achaia
            array(42,44,45,70), // Elis
            array(40,41,42,43,45,46), // Arkadien
            array(43,44,46), // Messenien
            array(39,44,45), // Lakonien
            array(39,48), // Chania
            array(47,49), // Rethymno
            array(48,50), // Iraklio
            array(49,51), // Lasithi
            array(50,52), // Karpathos
            array(51,53), // Rhodos
            array(52,54), // Kos
            array(53,55,56,62,63), // Kalymnos
            array(54,56,57,61,62), // Naxos
            array(54,55,57,58), // Thira
            array(55,56,58,60), // Paros
            array(56,57,59), // Milos
            array(36,58,60,65), // Kea
            array(57,59,61,64,65), // Syros
            array(55,60,64), // Mykonos
            array(54,55,63,66), // Ikaria
            array(54,62,66), // Samos
            array(60,61,65), // Tinos
            array(34,59,60), // Andros
            array(34,62,63,67), // Chios
            array(34,66,68), // Lesbos
            array(0,3,10,34,67), // Limnos
            array(27,28,29,70), // Kefalonia
            array(69,43), // Zakynthos*/



            array(1, 34),   //Evros
            array(0, 2, 3),   //Drama
            array(1, 5),   //Thasos
            array(1, 4),   //Serres
            array(3, 5, 6),   //Thessaloniki
            array(2, 4, 34),   //Chalkidiki
            array(4, 7, 8, 13),   //Imathia
            array(6, 8, 9),   //Florina
            array(6, 7, 9, 12, 13),   //Kozani
            array(7, 8, 10, 11, 12),   //Ionnina
            array(9, 11),   //Korfu-Thesprotia
            array(9, 10, 12, 16),   //Arta
            array(8, 9, 11, 13, 16, 17),   //Trikala
            array(6, 8, 12, 14, 17),   //Larisa
            array(13, 17, 19),   //Magnisia-Sporaden
            array(16, 22),   //Lefkada-Zakynthos
            array(11, 12, 15, 17, 18, 22),   //Akarnanien
            array(12, 13, 14, 16, 18, 19),   //Evrytania
            array(16, 17, 19, 20, 22),   //Fokida
            array(14, 17, 18, 20, 30, 32),   //Euboa
            array(18, 19, 21, 24, 25, 30),   //Attika-Inseln
            array(20, 22, 23),   //Korinthia
            array(15, 16, 18, 21, 23, 24),   //Elis
            array(21, 22, 24),   //Arkadien
            array(20, 22, 23),   //Lakonien
            array(20, 26),   //Chania
            array(25, 27),   //Iraklio
            array(26, 28),   //Karpathos-Rhodos
            array(27, 29, 31),   //Kos-Kalymnos
            array(28, 30),   //Milos-Naxos
            array(19, 20, 29, 31),   //Andros-Mykonos
            array(28, 30, 32),   //Ikaria-Samos
            array(19, 31, 33),   //Chiros
            array(32, 34),   //Lesbos
            array(0, 5, 33)   //Limnos

           /*

            array(2, 3),
                array(4, 5, 6, 7),
                array(0, 3, 5),
                array(0, 2, 4, 5),
                array(1, 3, 5), array(1, 2, 3, 4),
                array(1, 7, 8), array(1, 6, 8),
                array(6, 7, 9), array(8, 10),
                array(9, 11),
                array(10)*/);


            return $lookUp;
        }
    }

    class GraphNode {

        private $id;
        private $neighbours;


        public function __construct($id) {
            $this->id = $id;
            $this->neighbours = array();
        }

        public function getId() {
            return $this->id;
        }

        public function getNeighbours() {
            return $this->neighbours;
        }

        public function addAsNeighbour(GraphNode $node) {
            array_push($this->neighbours, $node);
        }

    }

?>