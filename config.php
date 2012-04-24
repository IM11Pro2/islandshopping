<?php
define("SITE_TITLE", "Island(S)hopping");
//$all_countries_array = array(0 => "EU", 1 =>"Schweiz", 2 =>"Tuerkei", 3 =>"SaudiArabien", 4 =>"USA");

//Countries
//define("COUNTRIES_ARRAY", array(0 => "EU", 1 =>"Schweiz", 2 =>"Türkei", 3 =>"Saudi Arabien", 4 =>"USA"));

function getCountriesArray()
{
   return array
   (
       "EU" => 1,
       "Schweiz" => -1,
       "Türkei" => 0,
       "Saudi Arabien" => 0,
       "USA"=> 0
   );
}

define("NUM_OF_REGIONS", 12);

?>