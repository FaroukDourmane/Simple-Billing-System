<?php
/*
     /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\
    |         DESIGNED & DEVELOPED        |
    |                                     |
    |                 BY                  |
    |                                     |
    |   F A R O U K _ D O  U R  M A  N E  |
    |                                     |
    |       dourmanefarouk@gmail.com      |
    |                                     |
    \/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/
*/

session_start();
require_once(__DIR__."/functions/main.php");
deny_self(basename(__FILE__));

// Default Languange
if( !defined("DEFAULT_LANG") ){
  DEFINE("DEFAULT_LANG","EN"); // Change FR to AR for arabic
}

$av_lang = ["EN","AR","TR"];

if (isset($_GET["lang"])){

  $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

  // Final Link Redirect
  if(strpos($actual_link,"&lang") !== false){
    $remove_extra = explode('&lang', $actual_link, 2);
    $final_link = $remove_extra[0];
  }else if(strpos($actual_link,"?lang") !== false){
    $remove_extra = explode('?lang', $actual_link, 2);
    $final_link = $remove_extra[0];
  }else{
    $final_link = $actual_link;
  }

  switch ($_GET["lang"]) {
    case 'ar':
      $_SESSION["CURRENT_LANG"] = "AR";
      header("Location: ".$final_link."");
      break;

    case 'tr':
      $_SESSION["CURRENT_LANG"] = "TR";
      header("Location: ".$final_link."");
      break;

    case 'en':
      $_SESSION["CURRENT_LANG"] = "EN";
      header("Location: ".$final_link."");
      break;

    case 'fr':
      $_SESSION["CURRENT_LANG"] = "FR";
      header("Location: ".$final_link."");
      break;

    default:
      // code...
      break;
  }
}

if( !isset($_SESSION["CURRENT_LANG"]) ){
  $_SESSION["CURRENT_LANG"] = DEFAULT_LANG;
}


#####################################################
## RESET SIGNUP INPUTS WHEN NOT IN SIGNUP PAGE
#####################################################
$URL = $_SERVER["PHP_SELF"];
$explode = explode("/",$URL);
if (
      !in_array("signup",$explode) && !in_array("step-1",$explode) &&
      !in_array("step-2",$explode) && !in_array("step-3",$explode) && !in_array("signup.php",$explode) &&
      !in_array("uploadCEOAvatar.php", $explode) && !in_array("uploadCEOAvatar", $explode) &&
      !in_array("deleteCeoAvatar.php", $explode) && !in_array("deleteCeoAvatar", $explode) &&
      !in_array("deleteLogo.php", $explode) && !in_array("deleteLogo", $explode) &&
      !in_array("uploadLogo.php", $explode) && !in_array("uploadLogo", $explode) &&
      !in_array("uploadGallery.php", $explode) && !in_array("uploadGallery", $explode) &&
      !in_array("deleteGallery.php", $explode) && !in_array("deleteGallery", $explode)

   )
{
  if ( !empty(trim(fill_input("ceo_avatar"))) )
  {
    $file = fill_input("ceo_avatar");
    delete_file($file);
  }

  if ( !empty(trim(fill_input("company_logo"))) )
  {
    $file = fill_input("company_logo");
    delete_file($file);
  }

  if ( isset($_SESSION["gallery"]) )
  {
    foreach ($_SESSION["gallery"] as $key => $value) {
      delete_file($value);
    }
  }
  reset_inputs();
}
#####################################################
## END
#####################################################

$LANG = array();
?>
