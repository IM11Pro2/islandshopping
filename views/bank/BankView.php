<?php
    class BankView {

        public function printBankView() {
            ?>
        <h3>Mein Kapital</h3>
        <?php
            $playersArray = $_SESSION['activePlayers'];
            $name = $playersArray[0]->getCountry()->getName();
            $value = $playersArray[0]->getCountry()->getPayment()->getValue();
            $currency = $playersArray[0]->getCountry()->getPayment()->getCurrency();
            $translation = $playersArray[0]->getCountry()->getPayment()->getCurrencyTranslation();
            $color = $playersArray[0]->getCountry()->getColor();
            ?>
            <?php
            echo "<font color='".$color."'>" . $name . "</font>:  " . $value * $translation . " " . $currency;

            ?>
        <h3>Kapital der Gegner</h3>
        <?php
            for($i = 1; $i < count($playersArray); $i++) {
                $enemyName = $playersArray[$i]->getCountry()->getName();
                $enemyValue = $playersArray[$i]->getCountry()->getPayment()->getValue();
                $enemyCurrency[$i] = $playersArray[$i]->getCountry()->getPayment()->getCurrency();
                $enemyTranslation[$i] = $playersArray[$i]->getCountry()->getPayment()->getCurrencyTranslation();
                $enemyColor[$i] = $playersArray[$i]->getCountry()->getCOlor();

                echo "<font color='".$enemyColor[$i]."'>". $enemyName . "</font>:  " . $enemyValue * $enemyTranslation[$i] . " " . $enemyCurrency[$i] . "<br />";
            }
            ?>
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
        <br/>
        <form>
                    <label>
                    <input type="radio" name="bankstate" value="<?php echo Bank::PAY_OFF ?>">
                    PayOff</input>
                    </label>

                    <label>
                    <input type="radio" name="bankstate" value="<?php echo Bank::DEPOSIT ?>">
                    Deposite</input>
                    </label>

                    <label>
                    <input type="radio" name="bankstate" value="<?php echo Bank::ATTACK ?>">
                    Attack</input>
                    </label>
                </form>
        <?php
        }
    }

?>

