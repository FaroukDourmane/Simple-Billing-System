<?php
	require_once("config/config.php");

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

	if ( !isset($_SESSION["company_informations"]) )
	{
		$_SESSION["company_informations"] = [
			"company_name" => "",
			"company_representive" => "",
			"phone_number" => "",
			"gsm_number" => "",
		];
	}

	if ( isset($_GET["token"]) && $_GET["token"] == $_SESSION["_TOKEN"])
  {
		$company_name = trim($_GET["company_name"]);
		$company_representive = trim($_GET["company_representive"]);
		$phone_number = trim($_GET["phone_number"]);
		$gsm_number = trim($_GET["gsm_number"]);

		$_SESSION["company_informations"]["company_name"] = $company_name;
		$_SESSION["company_informations"]["company_representive"] = $company_representive;
		$_SESSION["company_informations"]["phone_number"] = $phone_number;
		$_SESSION["company_informations"]["gsm_number"] = $gsm_number;

		if ( empty($company_name) OR empty($company_representive) OR empty($phone_number) OR empty($gsm_number) )
		{
			$type = "error";
			$message = __("fill_inputs", true);
		}else{
			if ( isset($_SESSION["packages"]) && count($_SESSION["packages"]) > 0 )
			{

			}else{
				$type = "error";
				$message = __("no_services", true);
			}
		}
	}else{
		$type = "error";
		$message = "";
	}

	$servicesTotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> <?php __("billing_system"); ?> - radar grup </title>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
	<!--===============================================================================================-->
		 <link rel="icon" type="image/png" href="assets/img/ico.png"/>
	<!--===============================================================================================-->

<!--===============================================================================================-->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
<!--===============================================================================================-->

<!-- OWL CAROUSEL =================================================================================-->
<!-- <link rel="stylesheet" href="assets/owlcarousel/css/owl.carousel.min.css"> -->
<!-- <link rel="stylesheet" href="assets/owlcarousel/css/owl.theme.default.css"> -->
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

<!-- Main style -->
<link rel="stylesheet" href="assets/css/printable.css">
<link rel="stylesheet" href="assets/css/printer/print.min.css" />

</head>

	<div class="fixed-tools no-print">
		<a href="index.php" class="back"> <img src="assets/svg/back-arrow.svg" /> </a>
		<!-- <a class="download ajaxPdf"> <img src="assets/svg/download.svg" /> <?php //__("download_pdf"); ?> </a>-->
    <a class="print ajaxPrint"> <img src="assets/svg/print.svg" /> <?php __("print_bill"); ?> </a>
	</div>

<body>

	<div class="page">
		<img src="assets/img/page1.jpg" class="cover full" />
	</div>

	<div class="page">
		<img src="assets/img/page2.jpg" class="cover full" />
	</div>

	<div class="page">
		<img src="assets/img/page3.jpg" class="cover full" />
	</div>

  <div class="page last">
		<div class="printable-container">
	    <div class="item client-informations">
	      <ul>
	        <li> <span>FIRMA ADI</span>  <b>: <?php echo $_SESSION["company_informations"]["company_name"]; ?></b> </li>
	        <li> <span>FIRMA YETKILISI</span>  <b>: <?php echo $_SESSION["company_informations"]["company_representive"]; ?></b> </li>
	        <li> <span>TELEFON</span>  <b>: <?php echo $_SESSION["company_informations"]["phone_number"]; ?></b> </li>
	        <li> <span>GSM</span>  <b>: <?php echo $_SESSION["company_informations"]["gsm_number"]; ?></b> </li>
	      </ul>
	    </div>

	    <?php
				if ( isset($_SESSION["packages"]) && count($_SESSION["packages"]) > 0 ) {
					foreach ($_SESSION["packages"] as $key => $value) {
						$servicesTotal += $_SESSION["packages"][$key]["total"];
			?>
	    <div class="item package">
	      <h1>HIZMET :</h1>
				<ul>
					<?php if ( count($_SESSION["packages"][$key]['services']) > 0 ) { foreach ($_SESSION["packages"][$key]['services'] as $k => $v) { ?>
						<li class="<?php echo $k; ?>"><?php echo $v; ?></li>
					<?php }} ?>
				</ul>
	      <div class="cost">
	        <ul>
	          <li> <span>HIZMET Bedeli</span> <b><?php echo $_SESSION["packages"][$key]['cost']; ?> TL</b> </li>
	          <li> <span>HAKEDILAN  RADAR PUAN</span> <b><?php echo $_SESSION["packages"][$key]['discount']; ?> TL</b> </li>
	          <li class="total"> <span>TOPLAM</span> <b><?php echo $_SESSION["packages"][$key]['total']; ?> TL + KDV</b> </li>
	        </ul>
	      </div>
	    </div>
		<?php }} ?>

			<?php if ( count($_SESSION["notes"]) > 0 ) { ?>
	    <div class="item package">
	      <h1>NOT :</h1>
	      <ul>
					<?php foreach ($_SESSION["notes"] as $key => $value) { ?>
						<li><?php echo $value; ?></li>
					<?php } ?>
	      </ul>
	    </div>
			<?php } ?>

	    <div class="item package toplam">
	      <div class="cost">
	        <ul>
	          <li class="total"> <span>TOPLAM</span> <b><?php echo $servicesTotal; ?> TL + KDV</b> </li>
	        </ul>
	      </div>
	    </div>
	  </div>

	  <div class="footer-container">
	    <div class="signature">
	      <div class="radar">
	        Radar Reklamcılık
	        <img src="assets/img/radar_signature.png" class="sign" />
	      </div>
	      <div class="client">
	        Müşteri
	      </div>
	    </div>

			<div class="fixed-footer">
				<div class="logo-container">
		      <img src="assets/svg/logo-footer.svg" />
		    </div>
		    <div class="informations">
		      <span> <img src="assets/svg/email.svg" /> info@radargrup.com </span>
		      <span> <img src="assets/svg/phone.svg" /> +90 850 495 55 52 </span>
		      <span> <img src="assets/svg/phone-second.svg" /> +90 549 801 08 01 </span>
		      <span> <img src="assets/svg/pointer.svg" /> www.radargrup.com </span>
		      <span> <img src="assets/svg/location.svg" /> Yakuplu Mah. Hürriyet Bulv. No:1 SKYPORT RESIDENCE Kat: 22 -188 Beylikdüzü - İSTANBUL </span>
		    </div>
			</div>
	  </div>
  </div>

<input type="hidden" name="hidden_token" value="<?php echo $_SESSION["_TOKEN"]; ?>" />
</body>
<footer>
  <!-- jQuery (Necessary for All JavaScript Plugins) -->
  <script src="assets/js/jquery/jquery.min.js"></script>
  <!--<script type="text/javascript" src="assets/js/printer/print.min.js"></script> -->
	<script type="text/javascript" src="assets/js/print.js"></script>
</footer>
</html>
<?php //session_destroy(); ?>
