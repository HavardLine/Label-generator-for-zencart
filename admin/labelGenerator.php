<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | This file have three parts: init, data processing, visualization     |
// +----------------------------------------------------------------------+
// $Id: ordersImport.php,v0.1 2012 071203line@gmail.com $
//

//start of init
require_once ('includes/application_top.php');
@set_time_limit(300); // if possible, let's try for 5 minutes before timeouts

$version = '0.1';
$adressBookId = NULL; //get parameter
$controlArray = NULL;
//end of init

// start of data processing
$adressBookId = (isset($_GET['addressBookID'])) ? $_GET['addressBookID'] : $adressBookId;
if (is_numeric($adressBookId)){
  printLabel($adressBookId);
}
$controlArray = getAdresses();
// end of data processing
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css"
	href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
		<!--
		function init()
		{
		cssjsmenu('navbar');
		if (document.getElementById)s_values as v_products_options_values
		FROM ".TABLE_OR
		{
		var kill = document.getElementById('hoverJS');
		kill.disabled = true;
		}
		}
		// -->
	</script>
</head>
<body onLoad="init()">
	<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
	<!-- header_eof //-->
	<!-- body //-->
	<!-- body_text //-->
<?php
echo zen_draw_separator('pixel_trans.gif', '1', '10');
?>
	<table align="center" border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td class="pageHeading" align="left"><?php echo LABEL_GENERATOR_HEADING; ?>
			</td>
		</tr>
	</table>
	<?php
	echo zen_draw_separator('pixel_trans.gif', '1', '10');
	
	display_addresses($controlArray);
	?>
	<!-- body_text_eof //-->
	<!-- body_eof //-->
	<br />
	<center>
	<?php echo LABEL_VERSION . $version; ?>
	</center>
	<!-- footer //-->
	<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
	<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>