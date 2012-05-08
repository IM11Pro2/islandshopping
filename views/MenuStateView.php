<?php
    class MenuStateView implements /*IEventListener,*/
        IStateView {

        const ViewType = "MenuStateView";

        public function __construct() {
            //GameEventManager::getInstance()->addEventListener($this, UpdateViewEvent::TYPE);
            $_SESSION['view'] = $this;
        }

/*
        function handleEvent(IEvent $event) {
            if($event->getEventType() == UpdateViewEvent::TYPE) {
                //echo "Update Menu State View Event<br />";
            }
        }
*/
        function printView() {
            ?>
        <div id="container">
            <div id="content">
                <form name="menuForm1" method="POST">
                    <h2>Welches Land m&ouml;chtest du sein ?</h2>
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

                    <h2>W&auml;hle deine Gegner</h2>

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
                    <br/><br/>

                    <input type="button" name="MenuSubmit" value="Start">
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

