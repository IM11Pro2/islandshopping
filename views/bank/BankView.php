<?php
    class BankView {

        public function printBankView() {
            ?>
        <h3>Mein Kapital</h3>
        <?php
            $_SESSION['activePlayers'];
            $name = $_SESSION['activePlayers'][0]->getCountry()->getName();
            $value = $_SESSION['activePlayers'][0]->getCountry()->getPayment()->getValue();
            $currency = $_SESSION['activePlayers'][0]->getCountry()->getPayment()->getCurrency();
            echo $name . ":  ".$value . " " . $currency;

            ?>
        <h3>Kapital der Gegner</h3>
        <?php
            for($i=1; $i < count($_SESSION['activePlayers']); $i++){
                $enemyName = $_SESSION['activePlayers'][$i]->getCountry()->getName();
                $enemyValue = $_SESSION['activePlayers'][$i]->getCountry()->getPayment()->getValue();
                $enemyCurrency = $_SESSION['activePlayers'][$i]->getCountry()->getPayment()->getCurrency();

                echo $enemyName . ":  " . $enemyValue . " ".$enemyCurrency ."<br />";
            }

        }
    }
?>

