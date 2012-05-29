<?php
    class Decision implements IDecisionTreeNode {

        private $trueBranch;
        private $falseBranch;

        function getBranch(){
           // return false;
        }

        function makeDecision() {
            if($this->getBranch()){
                if($this->trueBranch == NULL){
                    return NULL;
                }
                else {
                    return $this->trueBranch->$this->makeDecision();
                }
            }
            else {
                if($this->falseBranch == NULL){
                    return NULL;
                }
                else {
                    return $this->falseBranch->$this->makeDecision();
                }
            }
        }
    }
?>
