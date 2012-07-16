<?php
    class MenuStateView implements IStateView {

        const ViewType = "MenuStateView";

        public function __construct() {
            $_SESSION['view'] = $this;
        }


        function printView() {
            ?>
        <div id="container">
            <div id="content">
                <h1 class="menu">Island (s)hopping</h1>
                <form name="menuForm1" method="POST">
                    <h2 class="menu">Welcher Spieler m&ouml;chtest du sein?</h2>
                    <fieldset>
                    <?php

                    $status = "";
                    $all_countries_array = getCountriesArray();

                    foreach($all_countries_array as $country => $value) {

                        if(PLAYER_VALUE == $value) {
                            $status = 'checked="checked"';
                        }

                        ?>
                        <label><input type='radio' name='playerCountry'
                                      value="<?php echo $value ?>" <?php echo $status ?> /><?php echo $country ?>
                        </label>
                        <?php
                        $status = "";
                    }
                    ?>
                    </fieldset>
                    <h2 class="menu">W&auml;hle deine Mitstreiter:</h2>
                    <fieldset>
                    <?php
                    $status = "";
                    foreach($all_countries_array as $country => $value) {
                        if(ENEMY_VALUE == $value) {
                            $status = 'checked="checked"';
                        }

                        if(PLAYER_VALUE == $value) {
                            $status = $status . ' disabled="disabled"';
                        }
                        ?>
                        <label><input type="checkbox" name="enemyCountries[]"
                                      value="<?php echo $value ?>"
                                      id="enemy_<?php echo $country ?>" <?php echo $status ?> /><?php echo $country ?>
                        </label>                  <?php
                        $status = "";
                    }

                   ?>
                    </fieldset>
                    <input type="button" name="MenuSubmit" value="Zur Mapauswahl" id="menuSubmit" />
                </form>
            </div>
            <!--end #content -->
        </div><!-- end #container-->
        <?php
        }

        function getViewType() {
            return self::ViewType;
        }
    }

?>

