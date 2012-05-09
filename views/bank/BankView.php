<?php
    class BankView {

        public function printBankView() {
            ?>
        <h3>Mein Kapital</h3>
        <?php
            $playersArray = $_SESSION['activePlayers'];
            $name =$playersArray[0]->getCountry()->getName();
            $value =$playersArray[0]->getCountry()->getPayment()->getValue();
            $currency = $playersArray[0]->getCountry()->getPayment()->getCurrency();
            $translation = $playersArray[0]->getCountry()->getPayment()->getCurrencyTranslation();
            echo $name . ":  ".$value * $translation . " " . $currency;

        ?>
        <h3>Kapital der Gegner</h3>
        <?php
            for($i=1; $i < count($playersArray); $i++){
                $enemyName = $playersArray[$i]->getCountry()->getName();
                $enemyValue = $playersArray[$i]->getCountry()->getPayment()->getValue();
                $enemyCurrency = $playersArray[$i]->getCountry()->getPayment()->getCurrency();
                $enemyTranslation = $playersArray[$i]->getCountry()->getPayment()->getCurrencyTranslation();

                echo $enemyName . ":  " . $enemyValue * $enemyTranslation . " ".$enemyCurrency ."<br />";
            }

        }
    }
?>

