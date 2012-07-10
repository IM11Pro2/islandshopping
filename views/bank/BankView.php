<?php
    class BankView {

        public function printBankView() {
            ?>

        <div class="left">
        <h3>Mein Kapital</h3>
        <?php
            $playersArray = $_SESSION['activePlayers'];
            $name = $playersArray[0]->getCountry()->getName();
            $bankCapital = $playersArray[0]->getCountry()->getPayment();
            $currency = $playersArray[0]->getCountry()->getPayment()->getCurrency();
            $translation = $playersArray[0]->getCountry()->getPayment()->getCurrencyTranslation();
            $color = $playersArray[0]->getCountry()->getColor();
            ?>
            <?php

              echo "<span style='color:".$color.";'>" . $name . "</span>:  <span id=\"".$name."Bank\">" . $bankCapital . "</span>  ";


            ?>
        </div>
        <div class="right interests">
            <h3>Zinsen</h3>
            <?php echo "<span id=\"".$name."Interest\">0 ".$currency."</span>"; ?>
        </div>
        <div class="clear"></div>
        <div class="left">
        <h3>Kapital der Gegner</h3>
        <?php
            for($i = 1; $i < count($playersArray); $i++) {
                $enemyName = $playersArray[$i]->getCountry()->getName();
                $enemyBankCapital = $playersArray[$i]->getCountry()->getPayment();
                $enemyCurrency[$i] = $playersArray[$i]->getCountry()->getPayment()->getCurrency();
                $enemyTranslation[$i] = $playersArray[$i]->getCountry()->getPayment()->getCurrencyTranslation();
                $enemyColor[$i] = $playersArray[$i]->getCountry()->getColor();

                echo "<span style='color:".$enemyColor[$i].";'>". $enemyName . "</span>:  <span id=\"".$enemyName."Bank\" >" . $enemyBankCapital . "</span> <br />";
            }
            ?>
        </div>
        <div class="right interests">
            <h3>Zinsen</h3>
            <?php
            for($i = 1; $i < count($playersArray); $i++) {
                $enemyName = $playersArray[$i]->getCountry()->getName();
                $enemyCurrency[$i] = $playersArray[$i]->getCountry()->getPayment()->getCurrency();
                echo "<span id=\"".$enemyName."Interest\">0 ".$enemyCurrency[$i]."</span><br />";
            }
            ?>
        </div>
        <div class="clear"></div>
        <h3>Wechselkurse</h3>
        <div class="left">
            <?php
            echo "1 " . $currency;
            ?>
        </div>
        <div class="right">
            <?php
            for($i = 1; $i < count($playersArray); $i++) {
                $enemyTranslation[$i];
                $enemyCurrency[$i];

                $specificTranslation = 1 / $translation *  $enemyTranslation[$i];
                echo sprintf("%01.2f", $specificTranslation) . " " . $enemyCurrency[$i] . "<br />";
            }



            ?>
        </div>
        <div class="clear"></div>

        <?php
        }
    }

?>

