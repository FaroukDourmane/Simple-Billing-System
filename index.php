<?php
	require_once("config/config.php");

	/*foreach ($_SESSION["packages"] as $key => $value) {
		echo $key." </br><hr> ";
		echo var_dump($value["services"]);
	}
	exit;
	*/

	$company_name = (isset($_SESSION["company_informations"]["company_name"])) ? $_SESSION["company_informations"]["company_name"] : "" ;
	$company_representive = (isset($_SESSION["company_informations"]["company_representive"])) ? $_SESSION["company_informations"]["company_representive"] : "" ;
	$phone_number = (isset($_SESSION["company_informations"]["phone_number"])) ? $_SESSION["company_informations"]["phone_number"] : "" ;
	$gsm_number = (isset($_SESSION["company_informations"]["gsm_number"])) ? $_SESSION["company_informations"]["gsm_number"] : "" ;


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
<link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<div class="loadingContainer unblur"></div>

	<!-- Ajax box -->
	<div id="ajaxBox" class="ajaxBox unblur loading">
		<div class="loadingContainer"></div>

		<div class="title">
			<span> <?php __("services_package"); ?> </span>
			<div class="tools-wrapper">
				<a class="closeBox"> <img src="assets/svg/close.svg" /> </a>
				<a class="approveAjax approve"> <img src="assets/svg/check.svg" /> </a>
			</div>
		</div>
		<form class="input-container newService">
			<div class="loadingContainer"></div>

			<input type="text" name="service" value="" placeholder="<?php __("service"); ?>" required />
			<label>
				<input type="submit" style="display:none;" value="" />
				<a class="submit"> <img src="assets/svg/add.svg" /> <?php __("add"); ?> </a>
			</label>
		</form>

		<div class="wrapper">
		<!--
			<div class="item">
				<div class="tools"> <a href="#" class="delete"> <img src="assets/svg/delete.svg" /> </a>  <a href="#"> <img src="assets/svg/edit.svg" /> </a> </div>
				<p>  logo tasarım </p>
			</div>
		-->
		</div>

		<div class="count-wrapper">
			<div class="top">
				<div class="cost">
					<b> <input type="number" name="service_cost" min="0" placeholder="0" /> TL</b>
					<i><?php __("service_cost"); ?></i>
				</div>

				<div class="discount">
					<b> <input type="number" name="service_discount" placeholder="0" /> TL</b>
					<i><?php __("discount"); ?></i>
				</div>
			</div>

			<div class="bottom">
				<b> <span class="ajaxTotal">0</span> TL </b>
				<i><?php __("total"); ?></i>
			</div>
		</div>
	</div>
	<!-- END Ajax box -->

  <!-- Right side -->
  <div class="right-side">
		<div class="lang">
			<a href="?lang=ar"> <img src="assets/svg/ar.svg" alt="AR" /> </a>
			<a href="?lang=en"> <img src="assets/svg/en.svg" alt="EN" /> </a>
			<a href="?lang=tr"> <img src="assets/svg/tr.svg" alt="TR" /> </a>
		</div>
    <a class="download ajaxPreview"> <img src="assets/svg/download.svg" /> <?php __("preview"); ?> </a>
		<a class="reset ajaxReset"> <img src="assets/svg/reset.svg" /> <?php __("reset_informations"); ?> </a>
  </div>
  <!-- END Right side -->

  <div class="top">
    <img src="assets/svg/logo-colorful.svg" class="logo" />
    <span> <img src="assets/svg/receipt.svg" /> <?php __("billing_system"); ?> </span>
  </div>

  <div class="content">
		<!-- Client's informations -->
    <h1> <?php __("client_informations"); ?> </h1>
    <div class="form mainForm">
      <input type="text" name="company_name" value="<?php echo $company_name; ?>" placeholder="<?php __("company_name"); ?>" />
      <input type="text" name="company_representive" value="<?php echo $company_representive; ?>" placeholder="<?php __("company_representive"); ?>" />
      <input type="text" name="phone_number" value="<?php echo $phone_number; ?>" placeholder="<?php __("phone_number"); ?>" />
      <input type="text" name="gsm_number" value="<?php echo $gsm_number; ?>" placeholder="<?php __("gsm_number"); ?>" />
    </div>
		<!-- END Client's informations -->

		<!-- Notes container -->
		<h1> <?php __("notes"); ?> </h1>
    <form class="inline-form noteForm">
			<div class="loadingContainer"></div>

			<input type="text" name="note" value="" placeholder="<?php __("note"); ?>" required />
      <label>
				<input type="submit" style="display:none;" value="" />
				<a class="submit"> <img src="assets/svg/add.svg" /> <?php __("add"); ?> </a>
			</label>
    </form>

		<div class="notes-container">
			<?php if ( count($_SESSION["notes"]) > 0 ) { foreach ($_SESSION["notes"] as $key => $value) { ?>
			<div class="item" id="<?php echo $key; ?>">
				<div class="loadingContainer"></div>
				<div class="tools"> <a href="#" class="delete"> <img src="assets/svg/delete.svg" /> </a>  <a href="#" class="edit"> <img src="assets/svg/edit.svg" /> </a> </div>
				<p><?php echo $value; ?></p>
			</div>
			<?php }} ?>
		</div>
		<!-- END Notes container -->

		<!-- Services -->
    <h1> <?php __("services"); ?> </h1>
    <div class="form">
      <a href="#" class="addPackage" id="addPackage"> <span> <img src="assets/svg/add.svg" /> </span> <?php __("add_services_package"); ?> </a>
    </div>

		<div class="services-container">
			<?php
				if ( count($_SESSION["packages"]) > 0 ) {
					foreach ($_SESSION["packages"] as $key => $value) {

						$servicesTotal += $_SESSION["packages"][$key]["total"];
			?>
			<!-- Service item -->
			<div class="item <?php echo $key; ?>" id="<?php echo $key; ?>">
				<div class="loadingContainer"></div>
				<div class="title">
					<p><?php __("services_package"); ?></p>
					<div class="options">
						<a class="delete"> <img src="assets/svg/delete.svg" /> </a>
						<a class="edit"> <img src="assets/svg/edit.svg" /> </a>
					</div>
				</div>

				<img src="assets/svg/sale-tag.svg" class="tag" />
				<ul>
					<?php if ( count($_SESSION["packages"][$key]['services']) > 0 ) { foreach ($_SESSION["packages"][$key]['services'] as $k => $v) { ?>
						<li class="<?php echo $k; ?>"><?php echo $v; ?></li>
					<?php }} ?>
				</ul>

				<div class="cost-container">
					<div class="top">
						<div class="cost">
							<b><i class="costNumber"><?php echo $_SESSION["packages"][$key]["cost"]; ?></i>  TL</b>
							<i><?php __("service_cost"); ?></i>
						</div>

						<div class="discount">
							<b><i class="discountNumber"><?php echo $_SESSION["packages"][$key]["discount"]; ?></i> TL</b>
							<i><?php __("discount"); ?></i>
						</div>
					</div>
					<div class="bottom">
						<p><?php __("total"); ?></p>
						<p><span class="totalNumber"><?php echo $_SESSION["packages"][$key]["total"]; ?></span> TL</p>
					</div>
				</div>
			</div>
			<!-- END Service item -->
		<?php }} ?>
		</div>
		<!-- END Services -->

		<!-- Total container -->
		<div class="total-container">
			<div class="primary"><?php __("total"); ?></div>
			<p> <span class="servicesTotal"><?php echo $servicesTotal; ?></span> TL + KDV </p>
		</div>
		<!-- END Total -->
  </div>

	<div class="editor"></div>

	<input type="hidden" name="hidden_token" value="<?php echo $_SESSION["_TOKEN"]; ?>" />
</body>
<footer>
  <!-- jQuery (Necessary for All JavaScript Plugins) -->
  <script src="assets/js/jquery/jquery.min.js"></script>
	<script type="text/javascript">
		var apply = "<?php __("apply"); ?>";
		var cancel = "<?php __("cancel"); ?>";
		var note = "<?php __("note"); ?>";
		var services_package = "<?php __("services_package"); ?>";
		var service_cost = "<?php __("service_cost"); ?>";
		var discount = "<?php __("discount"); ?>";
		var total = "<?php __("total"); ?>";
	</script>
	<script type="text/javascript" src="assets/js/html2canvas.min"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
  <script src="assets/js/main.js"></script>
</footer>
</html>
<?php //session_destroy(); ?>
