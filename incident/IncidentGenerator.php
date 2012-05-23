<?php
require_once("../config/config.php");
class IncidentGenerator
{

    const GLOBAL_INCIDENT = 0;
    const LOCAL_INCIDENT = 1;

    const BANK_INCIDENT = 0;
    const REGION_INCIDENT = 1;

    private $isActive;
    private $numberOfMoves;

    private $incidentLevel;
    private $incidentType;

    private $incidentEvent;

    public function __construct(){
        $this->incidentLevel = array(self::GLOBAL_INCIDENT, self::LOCAL_INCIDENT);
        $this->isActive = false;
        $this->numberOfMoves = PHP_INT_MAX;
    }


    public function generateIncident(){

        // random local or global incident
        //$level = $this->incidentLevel[mt_rand(0, (count($this->incidentLevel) -1) )];

        $level = 0;

        $messages = getIncidentMessages(); // from config

        if(self::GLOBAL_INCIDENT == $level){
            $this->incidentType = mt_rand(0,1);

            $message = $messages[self::GLOBAL_INCIDENT][$this->incidentType ];

            if(self::BANK_INCIDENT == $this->incidentType){

                $bank = $_SESSION['listOfBanks'][mt_rand(0, (count($_SESSION['listOfBanks']) -1))];

                $this->incidentEvent = new GlobalBankEvent($message, $bank);

            }
            else if(self::REGION_INCIDENT == $this->incidentType){

                $regionList = $_SESSION['map']->getRegions();

                $region = $regionList[ mt_rand(0, (count($regionList)-1 )) ];

                $this->incidentEvent = new GlobalRegionEvent($message, $region);
            }


        }
        else if(self::LOCAL_INCIDENT == $level){
            //choose country
            //$_SESSION['activePlayers'];
            //choose if positive or negative
        }



        // calculates when the incident occurs, relative to the acctual move
        $this->numberOfMoves = mt_rand(MIN_MOVES_FOR_INCIDENT, MAX_MOVES_FOR_INCIDENT);
        $this->isActive = true;
    }

    public function getMovecount(){
        return $this->numberOfMoves;
    }

    public function isIncidentActive(){

        if($this->numberOfMoves >= 0){

            if($this->numberOfMoves == 0){

                GameEventManager::getInstance()->dispatchEvent( $this->incidentEvent );

                $this->isActive = false;

                return $this->isActive;
            }

            $this->numberOfMoves--;
        }

        return $this->isActive;
    }
}


?>
