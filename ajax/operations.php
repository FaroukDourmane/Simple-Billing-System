<?php
require_once("../config/config.php");

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

  $type = "error";
  $message = "No request received";
  $key = 0;
  $reference = 0;
  $total_number = 0;

  if ( isset($_POST["action"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["_TOKEN"])
  {

    switch ($_POST["action"]) {
      case 'addNote':
        $note = $_POST["note"];
        $_SESSION["notes"][] = $note;
        $type = "success";
        end($_SESSION["notes"]);
        $key = key($_SESSION["notes"]);
        $message = $note;
        break;

      case "newService":
        $service = $_POST["service"];
        createPackage();
        $key = insertService($service);
        $type = "success";
        $message = $service;
        $reference = $_SESSION["current_package"];
      break;

      case "newPackageInitialize":
        $_SESSION["current_package"] = generatePackageNumber();
        $type = "success";
        $message = $_SESSION["current_package"];
      break;

      case "deletePackage":
        $id = $_POST["id"];
        if ( isset($_SESSION["packages"][$id]) )
        {
          unset($_SESSION["packages"][$id]);
          if ( $_SESSION["current_package"] == $id ){
            unset($_SESSION["current_package"]);
          }
          $type = "success";
          $message = "Package deleted successfully !";
        }else{
          $type = "error";
          $message = "Package was not found";
        }
      break;


      case "editPackage":
        $id = $_POST["id"];

        if ( isset($_SESSION["packages"][$id]) )
        {
          $_SESSION["current_package"] = $id;
          $type = "success";
          $message = [];

          if ( count($_SESSION["packages"][$id]["services"]) > 0 ) {
            foreach ($_SESSION["packages"][$id]["services"] as $key => $value) {
              $message[$key] = [$value];
            }
          }

          $message = json_encode($message);

          $reference = [
            "cost" => $_SESSION["packages"][$id]['cost'],
            "discount" => $_SESSION["packages"][$id]['discount'],
            "total" => $_SESSION["packages"][$id]['total']
          ];

          $reference = json_encode($reference);

        }else{
          $type = "error";
          $message = "Package was not found";
        }
      break;

      case "deleteNote":
        $id = $_POST["id"];
        if ( isset($_SESSION["notes"][$id]) )
        {
          unset($_SESSION["notes"][$id]);
          $type = "success";
          $message = "note deleted successfully !";
        }else{
          $type = "error";
          $message = "Note was not found";
        }
      break;

      case "editNote";
        $key = $_POST["key"];
        $note = $_POST["note"];

        if ( isset($_SESSION["notes"][$key]) )
        {
          $_SESSION["notes"][$key] = $note;
          $type = "success";
          $message = $note;
        }else{
          $type = "error";
          $message = "Note was not found";
        }
      break;

      case "approvePackage":
        $cost = intval($_POST["cost"]);
        $discount = intval($_POST["discount"]);
        $key = (isset($_SESSION["current_package"])) ? $_SESSION["current_package"] : 0;
        $total_number = 0;

        $total = ($cost > $discount) ? $cost-$discount : 0;
        if ( $key )
        {
          if ( isset($_SESSION["packages"][$key]) && count($_SESSION["packages"][$key]["services"]) > 0 ){

            $_SESSION["packages"][$key]['cost'] = $cost;
            $_SESSION["packages"][$key]['discount'] = $discount;
            $_SESSION["packages"][$key]['total'] = $total;

            if ( count($_SESSION["packages"]) > 0 ) {
    					foreach ($_SESSION["packages"] as $key => $value) {
    						$total_number += intval($_SESSION["packages"][$key]["total"]);
            }}

            $type = "success";
            $message = "Package approved successfully";
            $reference = $key;
          }else{
            $type = "error";
            $message = "You should enter services first";
          }
        }else{
          $type = "error";
          $message = "You should enter services first";
        }
      break;

      case "deleteService":
        $id = $_POST["id"];
        $key = $_SESSION["current_package"];
        if ( isset($_SESSION["packages"][$key]['services'][$id]) )
        {
          unset($_SESSION["packages"][$key]['services'][$id]);
          $type = "success";
          $mssage = "service deleted successfully";
        }else{
          $type = "error";
          $message = "service was not found";
        }
      break;

      case "editService":
        $id = $_POST["id"];
        $value = $_POST["value"];
        $key = (isset($_SESSION["current_package"])) ? $_SESSION["current_package"] : 0;

        if ( isset($_SESSION["packages"][$key]["services"][$id]) )
        {
          $_SESSION["packages"][$key]["services"][$id] = $value;
          $type = "success";
          $message = "service updated successfully";
        }else{
          $type = "error";
          $message = "service not found";
        }
      break;

      case "resetAll";
        session_regenerate_id();
        session_destroy();
        $type = "success";
        $message = "Reset success";
      break;


      default:
        // code...
        break;
    }
  }

  $response = [
    "type" => $type,
    "message" => $message,
    "key" => $key,
    "reference" => $reference,
    "total_number" => $total_number,
  ];

  $json_response = json_encode($response);
  echo $json_response;

?>
