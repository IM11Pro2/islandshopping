<?php
    class BankView {

        public function printBankView() {
            ?>
        <div id="interestInfo"></div>
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
/*            echo "<font color='".$color."'>" . $name . "</font>:  <span id=\"".$name."Bank\">" . $value * $translation . "</span>  " . $currency;*/
              echo "<font color='".$color."'>" . $name . "</font>:  <span id=\"".$name."Bank\">" . $bankCapital . "</span>  ";


            ?>
        <h3>Kapital der Gegner</h3>
        <?php
            for($i = 1; $i < count($playersArray); $i++) {
                $enemyName = $playersArray[$i]->getCountry()->getName();
                $enemyBankCapital = $playersArray[$i]->getCountry()->getPayment();
                $enemyCurrency[$i] = $playersArray[$i]->getCountry()->getPayment()->getCurrency();
                $enemyTranslation[$i] = $playersArray[$i]->getCountry()->getPayment()->getCurrencyTranslation();
                $enemyColor[$i] = $playersArray[$i]->getCountry()->getColor();

                echo "<font color='".$enemyColor[$i]."'>". $enemyName . "</font>:  <span id=\"".$enemyName."Bank\" >" . $enemyBankCapital . "</span> <br />";
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

            $bankList = $_SESSION['state']->getBankList();
            $bankState = $bankList[0]->getState();

            ?>
        </div>
        <div class="clear"></div>
        <!--
        <br/>
        <form>
            <label>
            <input type="radio" id="payoff" name="bankstate" value="<?php echo Bank::PAY_OFF ?>" <?php echo ($bankState == Bank::PAY_OFF) ? "checked=checked" :"" ?>>
            Geld verteilen </input>
            </label>

            <label>
                <input type="radio" id="attack" name="bankstate" value="<?php echo Bank::ATTACK ?>" <?php echo ($bankState == Bank::ATTACK) ? "checked=checked" :"" ?>>
                Shoppen</input>
            </label>

            <label>
                <input type="radio" id="deposit" name="bankstate" value="<?php echo Bank::DEPOSIT ?>" <?php echo ($bankState == Bank::DEPOSIT) ? "checked=checked" :"" ?>>
                Bank einzahlen</input>
            </label>

        </form>-->
        <?php
        }
    }

?>

