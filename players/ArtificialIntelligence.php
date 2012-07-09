<?php
    class ArtificialIntelligence extends IPlayer {

        static $AI_ID_counter = 1;

        public function __construct($countryName, $colorArray) {
            $this->playerId = self::$AI_ID_counter;
            self::$AI_ID_counter++;

            $this->setPlayerState(self::INACTIVE);
            $this->init($countryName, $colorArray, 0);
        }

        public function makeInitPayOff($allAiPlayerRegions, $regions){
            $possibleInitPayoffDecisions = array();

            for($i=0; $i<count($allAiPlayerRegions); $i++){
                $actualRegion = $allAiPlayerRegions[$i];
                $actualRegionId = $actualRegion->getRegionId();

                $neighbours = $actualRegion->getNeighbours();

                for($j=0; $j< count($neighbours); $j++){
                    // if neighbour region is of other country
                    if($regions[$neighbours[$j]]->getCountry() != $this->getCountry()){
                        // if neighbour region has more/equal money --> payoff
                        if($regions[$neighbours[$j]]->getPayment()->getValue() >= $actualRegion->getPayment()->getUsableValue()){
                            array_push($possibleInitPayoffDecisions, array("initPayOff" => $actualRegionId));
                        }
                    }
                }

            }

            if(count($possibleInitPayoffDecisions) > 0){
                $randomPayoff = rand(0, (count($possibleInitPayoffDecisions)-1));
            }
            else{
                return null;
            }

            return $possibleInitPayoffDecisions[$randomPayoff];
        }

        public function payOffDecision($allAiPlayerRegions, $regions){
            $possiblePayOffDecisions = array();
            $bankList = $_SESSION['state']->getBankList();

            $nextPhaseCounter = 0;

            for($i=0; $i<count($allAiPlayerRegions); $i++){
                $actualRegion = $allAiPlayerRegions[$i];
                $actualRegionId = $actualRegion->getRegionId();

                $neighbours = $actualRegion->getNeighbours();

                for($j=0; $j< count($neighbours); $j++){
                    // if neighbour region is of other country
                    if($regions[$neighbours[$j]]->getCountry() != $this->getCountry()){
                        // if neighbour region has more/equal money --> payoff
                        if($regions[$neighbours[$j]]->getPayment()->getValue() > $actualRegion->getPayment()->getUsableValue()){
                            //if there is enough money left on the bank
                            if($bankList[$this->playerId]->getPlainCapital() > 2*BASIC_CAPITAL_REGION){
                                array_push($possiblePayOffDecisions, array("payOff" => $actualRegionId, "nextPhase" => false));
                            }
                            else {
                                // was gebe ich als payoff region zurück, wenn ich kein geld mehr habe ?
                                array_push($possiblePayOffDecisions, array("payOff" => $actualRegionId, "nextPhase" => true));
                            }
                        }
                    }
                }

                if($nextPhaseCounter == 3){
                    array_push($possiblePayOffDecisions, array("payOff" => $actualRegionId, "nextPhase" => true));
                    $nextPhaseCounter = 0;
                }

                $nextPhaseCounter++;

            }

            if(count($possiblePayOffDecisions) > 0){
                $randomPayoff = rand(0, (count($possiblePayOffDecisions)-1));
            }
            else{
                //return null;
                return $this->attackDecision($allAiPlayerRegions, $regions);
            }

            return $possiblePayOffDecisions[$randomPayoff];
        }

        public function attackDecision($allAiPlayerRegions, $regions){
            $possibleAttackDecisions = array();

            $nextPhaseCounter = 0;

            for($i=0; $i<count($allAiPlayerRegions); $i++){
                $actualRegion = $allAiPlayerRegions[$i];
                $actualRegionId = $actualRegion->getRegionId();

                $neighbours = $actualRegion->getNeighbours();

                for($j=0; $j< count($neighbours); $j++){
                    // if neighbour region is of other country
                    if($regions[$neighbours[$j]]->getCountry() != $this->getCountry()){
                        // if neighbour region has less money --> attack
                        if($regions[$neighbours[$j]]->getPayment()->getValue() < $actualRegion->getPayment()->getUsableValue()){
                            $enemyRegionId = $regions[$neighbours[$j]]->getRegionId();

                            array_push($possibleAttackDecisions, array("attack" => $enemyRegionId, "actualRegionId" => $actualRegionId, "nextPhase" => false));

                            if($nextPhaseCounter == 3){
                                array_push($possibleAttackDecisions, array("attack" => $enemyRegionId, "actualRegionId" => $actualRegionId, "nextPhase" => true));
                                $nextPhaseCounter = 0;
                            }
                            $nextPhaseCounter++;
                        }
                    }
                }
            }

            if(count($possibleAttackDecisions) > 0){
                $randomAttack = rand(0, (count($possibleAttackDecisions)-1));
            }
            else{
                //return null;
                return $this->depositDecision($allAiPlayerRegions, $regions);
            }

            return $possibleAttackDecisions[$randomAttack];
        }



        public function depositDecision($allAiPlayerRegions, $regions){
            $possibleDepositDecisions = array();

            $nextPhaseCounter = 0;
            $sameCountryNeighbours = 0;
            $otherCountryNeighbours = 0;

            for($i=0; $i<count($allAiPlayerRegions); $i++){
                $actualRegion = $allAiPlayerRegions[$i];
                $actualRegionId = $actualRegion->getRegionId();

                $neighbours = $actualRegion->getNeighbours();

                for($j=0; $j< count($neighbours); $j++){
                    // if neighbour country is one of own
                    if($regions[$neighbours[$j]]->getCountry() != $this->getCountry()){
                        $otherCountryNeighbours++;
                    }
                    else {
                        $sameCountryNeighbours++;
                    }
                }

                if($otherCountryNeighbours == 0) {
                    if(($actualRegion->getPayment()->getUsableValue()) >= BASIC_CAPITAL_REGION){
                         array_push($possibleDepositDecisions, array("deposit" => $actualRegionId, "nextPhase" => false));

                        if($nextPhaseCounter == 3){
                            array_push($possibleDepositDecisions, array("deposit" => $actualRegionId, "nextPhase" => true));
                            $nextPhaseCounter = 0;
                        }
                        $nextPhaseCounter++;
                    }
                }
         /*       else if($sameCountryNeighbours > 3) {
                    if(($actualRegion->getPayment()->getUsableValue()) >= BASIC_CAPITAL_REGION){
                         array_push($possibleDepositDecisions, array("deposit" => $actualRegionId, "nextPhase" => false));

                        if($nextPhaseCounter == 3){
                            array_push($possibleDepositDecisions, array("deposit" => $actualRegionId, "nextPhase" => true));
                            $nextPhaseCounter = 0;
                        }
                        $nextPhaseCounter++;
                    }
                } */
            }

            if(count($possibleDepositDecisions) > 0){
                $randomDeposit = rand(0, (count($possibleDepositDecisions)-1));
            }
            else{
                return null;
            }

            return $possibleDepositDecisions[$randomDeposit];
        }


        public function makeDecision($allEnemyRegions, $regions){
            $possibleDecisions = array();
            $nextPlayerCounter = 0;
            $sameCountryNeighbours = 0;

            for($i=0; $i<count($allEnemyRegions); $i++){
                $actualRegion = $allEnemyRegions[$i];
                $actualRegionId = $actualRegion->getRegionId();

                //Look if Neighbour-Attack is possible
                $neighbours = $actualRegion->getNeighbours();
                for($j=0; $j< count($neighbours); $j++){
                    // if neighbour region is of other country
                    if($regions[$neighbours[$j]]->getCountry() != $this->getCountry()){
                        // if neighbour region has less money --> attack
                        if($regions[$neighbours[$j]]->getPayment()->getValue() < $actualRegion->getPayment()->getUsableValue()){
                            $enemyRegionId = $regions[$neighbours[$j]]->getRegionId();

                            array_push($possibleDecisions, array("attack" => $enemyRegionId, "actualRegionId" => $actualRegionId));
                        }
                        // if neighbour region has more money --> payoff
                        else if($regions[$neighbours[$j]]->getPayment()->getValue() > $actualRegion->getPayment()->getUsableValue()){
                            array_push($possibleDecisions, array("payOff" => $actualRegionId));
                        }
                        // if neighbour region has equal money
                        else {
                            // if there is minimum half of the startcapital of money on the bank -- pay off
                            $bankList = $_SESSION['state']->getBankList();
                            if($bankList[$this->playerId]->getPlainCapital() > (START_CAPITAL_COUNTRY/3)){
                                array_push($possibleDecisions, array("payOff" => $actualRegionId));
                            }
                            else {
                                array_push($possibleDecisions, array("nextPlayer" => $actualRegionId));
                            }
                        }

                        if($nextPlayerCounter == 3){
                            array_push($possibleDecisions, array("nextPlayer" => $actualRegionId));
                            $nextPlayerCounter = 0;
                        }

                        $nextPlayerCounter++;
                    }
                    // if neighbour is of same country
                    else {
                        $sameCountryNeighbours++;

                        if($sameCountryNeighbours == 2) {
                            if(($regions[$neighbours[$j]]->getPayment()->getValue()-BASIC_CAPITAL_REGION) >= BASIC_CAPITAL_REGION){
                                 array_push($possibleDecisions, array("deposit" => $actualRegionId));
                            }
                        }
                        // in case of no previous decisions --> nextPlayer
/*                        else{
                            array_push($possibleDecisions, array("nextPlayer" => $actualRegionId));
                        }*/
                    }
                }
            }

            if(count($possibleDecisions) > 0){
                //chooses a random number between 0 and length-1 of array
                $random = rand(0, count($possibleDecisions)-1);
            }
            else {
                return null;
            }

/*            echo "Possible Decisions Array";
            print_r($possibleDecisions);
            echo "<br/>";
            echo "random: " . $random . "<br/>";*/

            //returns a random decison of all possible decisions
            return $possibleDecisions[$random];
        }
    }
