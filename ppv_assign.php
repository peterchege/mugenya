<?php
	$msg = $err_msg = '';
	
	// Get a list of videos. Do not use this function every time. You can save data in your DataBase.
	$video_list = getVideosList();
	
	$error_code = arrayVal($video_list, 'error_code'); // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code);
	}
	// If no errors, data will contains two arrays:
	// 1.ref_numbers => array with all reference numbers of videos.
	// 2.titles => array with all titles of videos.
	$data = arrayVal($video_list, 'data');
	
	$ref_numbers = arrayVal($data, 'ref_numbers', array());
	$titles = arrayVal($data, 'titles', array());
	$videos = array();
	if(!empty($ref_numbers) && !empty($titles)) {
		$videos = array_combine($ref_numbers, $titles); // array which contains ref_numbers for key & titles for values
	}
	$video_ref = postVal('video_ref');
	$videos_combo = arrayToCombo('video_ref', $video_ref, $videos, 'style="width:375px;"'); // create a SelectBox with all videos.
	/***/
	
	// Get a list of advanced packages. Do not use this function every time. You can save data in your DataBase.
	$advanced_packages_list = getAdvancedPackagesList();
	
	$error_code = arrayVal($advanced_packages_list, 'error_code'); // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code);
	}
	// If no errors, data will contains two arrays:
	// 1.ref_numbers => array with all reference numbers of advanced packages.
	// 2.titles => array with all titles of videos.
	$data = arrayVal($advanced_packages_list, 'data');
	
	$ref_numbers = arrayVal($data, 'ref_numbers', array());
	$names = arrayVal($data, 'names', array());
	$advanced_packages = array();
	if(!empty($ref_numbers) && !empty($names)) {
		$advanced_packages = array_combine($ref_numbers, $names); // array which contains ref_numbers for key & packages names for values
	}
	$advanced_package_ref = isset($_POST['advanced_package_ref']) ? $_POST['advanced_package_ref'] : '';
	$advanced_packages_combo = arrayToCombo('advanced_package_ref', $advanced_package_ref, $advanced_packages, 'style="width:375px;"'); // create a SelectBox with all advanced packages.
	/***/
	
	$layouts = array('default' => 'Default', 'advanced' => 'Advanced');
	$layout = postVal('layout');
	$layouts_combo = arrayToCombo('layout', $layout, $layouts, 'onchange="toggleTicketLayout();"'); // create a SelectBox with all layouts.
	
	$protection_types = array('payment' => 'Payment', 'password' => 'Password');
	$protection_type = postVal('protection_type');
	$advanced_protection_type = postVal('advanced_protection_type');
	$protection_types_combo = arrayToCombo('protection_type', $protection_type, $protection_types, 'onchange="toggleProtectionType();"'); // create a SelectBox with all protection types.
	$advanced_protection_types_combo = arrayToCombo('advanced_protection_type', $advanced_protection_type, $protection_types); // create a SelectBox with all protection types.
	
	$global = postVal('global');
	$playlist = postVal('playlist');
	$single = postVal('single');
	$ticket_title = postVal('ticket_title');
	$ticket_description = postVal('ticket_description');
	$ticket_price = postVal('ticket_price');
	$expiry_days = postVal('expiry_days');
	$expiry_months = postVal('expiry_months');
	$expiry_years = postVal('expiry_years');
	$total_allowed_views = postVal('total_allowed_views');
	
	$submit = postVal('submit');
	if($submit) {
		$all_tickets = array();
		if($global) $all_tickets[] = 'global';
		if($playlist) $all_tickets[] = 'playlist';
		if($single) $all_tickets[] = 'single';
		
		$service_response = assignVideoTickets($video_ref, $layout, implode(',', $all_tickets), $advanced_package_ref, $layout === 'advanced' ? $advanced_protection_type : $protection_type, $ticket_title, $ticket_description, $ticket_price, $expiry_days, $expiry_months, $expiry_years, $total_allowed_views);
		
		$error_code = arrayVal($service_response, 'error_code'); // check for error.
		// If error occurs - display the error.
		if($error_code) {
			$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
		}
		else {
			// If no errors, data will contains the reference number of new uploaded video.
			$data = arrayVal($service_response, 'data');
			$result = arrayVal($data, 'result');
			$msg = 'Result of service is '.$result.' !';
		}
	}
?>
<form method="post" name="form">
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<div align="center" style="font-weight:bold;">
			Assign Video Test
		</div>
		<?if($err_msg) {?>
		<div align="center" style="font-weight:bold;color:#A00000;">
			<?=$err_msg?>
		</div>
		<?}?>
		<?if($msg) {?>
		<div align="center" style="font-weight:bold;color:#0000F0;">
			<?=$msg?>
		</div>
		<?}?>
	</div>
	
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Video <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$videos_combo?>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Layout <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$layouts_combo?>
		</div>
	</div>
	<div id="advanced_layout">
		<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
			<div align="right" style="width:375px; float:left; padding-right: 5px;">
				Advanced package <span style="color:#A00000;">*</span>:
			</div>
			<div style="width:375px;float:left;padding-left: 5px;" align="left">
				<?=$advanced_packages_combo?>
			</div>
		</div>
		<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
			<div align="right" style="width:375px; float:left; padding-right: 5px;">
				Protection type:
			</div>
			<div style="width:375px;float:left;padding-left: 5px;" align="left">
				<?=$advanced_protection_types_combo?>
			</div>
		</div>
	</div>
	<div id="default_layout">
		<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
			<div align="right" style="width:375px; float:left; padding-right: 5px;">
				Ticket <span style="color:#A00000;">*</span>:
			</div>
			<div style="width:375px;float:left;padding-left: 5px;" align="left">
				<input type="checkbox" name="global" id="global" <?if($global){?>checked="checked"<?}?>> Global
				<input type="checkbox" name="playlist" id="playlist" <?if($playlist){?>checked="checked"<?}?>> Playlist
				<input type="checkbox" name="single" id="single" <?if($single){?>checked="checked"<?}?> onclick="toggleSingleTicket();"> Single
			</div>
		</div>
		<div id="single_layout">
			<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
				<div align="right" style="width:375px; float:left; padding-right: 5px;">
					Protection type:
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<?=$protection_types_combo?>
				</div>
			</div>
			<div id="payment_layout">
				<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
					<div align="right" style="width:375px; float:left; padding-right: 5px;">
						Ticket price:
					</div>
					<div style="width:375px;float:left;padding-left: 5px;" align="left">
						<input type="text" name="ticket_price" size="40" value="<?=$ticket_price?>">
					</div>
				</div>
				<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
					<div align="right" style="width:375px; float:left; padding-right: 5px;">
						Ticket description:
					</div>
					<div style="width:375px;float:left;padding-left: 5px;" align="left">
						<input type="text" name="ticket_description" size="40" value="<?=$ticket_description?>">
					</div>
				</div>
				<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
					<div align="right" style="width:375px; float:left; padding-right: 5px;">
						Ticket title:
					</div>
					<div style="width:375px;float:left;padding-left: 5px;" align="left">
						<input type="text" name="ticket_title" size="40" value="<?=$ticket_title?>">
					</div>
				</div>
				<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
					<div align="right" style="width:375px; float:left; padding-right: 5px;">
						Expiry period:
					</div>
					<div style="width:375px;float:left;padding-left: 5px;" align="left">
						Days <input type="text" name="expiry_days" size="10" value="<?=$expiry_days?>">
						Months <input type="text" name="expiry_months" size="10" value="<?=$expiry_months?>">
						Years <input type="text" name="expiry_years" size="10" value="<?=$expiry_years?>">
					</div>
				</div>
				<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
					<div align="right" style="width:375px; float:left; padding-right: 5px;">
						Total allowed views:
					</div>
					<div style="width:375px;float:left;padding-left: 5px;" align="left">
						<input type="text" name="total_allowed_views" size="10" value="<?=$total_allowed_views?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<input type="submit" name="submit" value="Submit" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
	</div>
</form>
<script type="text/javascript">
function toggleSingleTicket() {
	var field = document.getElementById('single');
	if(field.checked) {
		document.getElementById('single_layout').style.display = '';
	}
	else {
		document.getElementById('single_layout').style.display = 'none';
	}
}
function toggleTicketLayout() {
	var field = document.getElementById('layout');
	var value = field.options[field.selectedIndex].value;
	if(value === 'default') {
		document.getElementById('advanced_layout').style.display = 'none';
		document.getElementById('default_layout').style.display = '';
	}
	else {
		document.getElementById('default_layout').style.display = 'none';
		document.getElementById('advanced_layout').style.display = '';
	}
}
function toggleProtectionType() {
	var field = document.getElementById('protection_type');
	var value = field.options[field.selectedIndex].value;
	if(value === 'password') {
		document.getElementById('payment_layout').style.display = 'none';
	}
	else {
		document.getElementById('payment_layout').style.display = '';
	}
}
toggleTicketLayout();
toggleSingleTicket();
toggleProtectionType();
</script>