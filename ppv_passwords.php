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
	
	// Get a list of video playlists. Do not use this function every time. You can save data in your DataBase.
	$channels_list = getChannelsList();
	
	$error_code = arrayVal($channels_list, 'error_code'); // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code);
	}
	
	// If no errors, data will contains two arrays:
	// 1.ref_numbers => array with all reference numbers of video playlists.
	// 2.titles => array with all titles of video playlists.
	$data = arrayVal($channels_list, 'data');
	$ref_numbers = arrayVal($data, 'ref_numbers', array());
	$titles = arrayVal($data, 'titles', array());
	$channels = array();
	if(!empty($ref_numbers) && !empty($titles)) {
		$channels = array_combine($ref_numbers, $titles); // array which contains ref_numbers for key & channel titles for values
	}
	$channels = array(0 => '--Select Video Playlist--') + $channels; // set default value, channel_ref is optional parameter.
	$channel_ref = postVal('channel_ref');
	$channels_combo = arrayToCombo('channel_ref', $channel_ref, $channels); // create a SelectBox with all video playlists.
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
	// 2.names => array with all names of advanced packages.
	$data = arrayVal($advanced_packages_list, 'data');
	
	$ref_numbers = arrayVal($data, 'ref_numbers', array());
	$names = arrayVal($data, 'names', array());
	$advanced_packages = array();
	if(!empty($ref_numbers) && !empty($names)) {
		$advanced_packages = array_combine($ref_numbers, $names); // array which contains ref_numbers for key & packages names for values
	}
	$advanced_package_ref = postVal('advanced_package_ref');
	$advanced_packages_combo = arrayToCombo('advanced_package_ref', $advanced_package_ref, $advanced_packages, 'style="width:375px;"'); // create a SelectBox with all advanced packages.
	/***/
	
	$layouts = array('default' => 'Default', 'advanced' => 'Advanced');
	$layout = postVal('layout');
	$layouts_combo = arrayToCombo('layout', $layout, $layouts, 'onchange="toggleTicketLayout();"'); // create a SelectBox with all layouts.
	
	$protection_types = array('payment' => 'Payment', 'password' => 'Password');
	$protection_type = postVal('protection_type');
	$advanced_protection_type = postVal('advanced_protection_type');
	$protection_types_combo = arrayToCombo('protection_type', $protection_type, $protection_types); // create a SelectBox with all protection types.
	$advanced_protection_types_combo = arrayToCombo('advanced_protection_type', $advanced_protection_type, $protection_types); // create a SelectBox with all protection types.
	
	$global = postVal('global');
	$playlist = postVal('playlist');
	$single = postVal('single');
	
	$ticket = postVal('ticket', 'global');
	
	$expiry_days = postVal('expiry_days');
	$expiry_months = postVal('expiry_months');
	$expiry_years = postVal('expiry_years');
	$total_allowed_views = postVal('total_allowed_views');
	$total_allowed_views_per_video = postVal('total_allowed_views_per_video');
	$count_passwords = postVal('count_passwords');
	
	$submit = postVal('submit');
	if($submit) {
		$service_response = generateTicketPasswords($video_ref, $channel_ref, $layout, $ticket, $advanced_package_ref, $expiry_days, $expiry_months, $expiry_years, $total_allowed_views, $total_allowed_views_per_video, $count_passwords);
		
		$error_code = arrayVal($service_response, 'error_code'); // check for error.
		// If error occurs - display the error.
		if($error_code) {
			$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
		}
		else {
			// If no errors, data will contains the reference number of new uploaded video.
			$data = arrayVal($service_response, 'data');
			$result = arrayVal($data, 'result');
			$passwords = arrayVal($data, 'passwords');
			$msg = 'Result of service is '.$result.' !<br/>Passwords:<br/>'.implode('<br/>', $passwords);
		}
	}
?>
<form method="post" name="form">
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<div align="center" style="font-weight:bold;">
			Passwords Test
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
	</div>
	<div id="default_layout">
		<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
			<div align="right" style="width:375px; float:left; padding-right: 5px;">
				Ticket <span style="color:#A00000;">*</span>:
			</div>
			<div style="width:375px;float:left;padding-left: 5px;" align="left">
				<input type="radio" name="ticket" id="global" value="global" <?if($ticket === 'global'){?>checked="checked"<?}?> onclick="toggleTicketSelection(this.id);"> Global
				<input type="radio" name="ticket" id="playlist" value="playlist" <?if($ticket === 'playlist'){?>checked="checked"<?}?> onclick="toggleTicketSelection(this.id);"> Playlist
				<input type="radio" name="ticket" id="single" value="single" <?if($ticket === 'single'){?>checked="checked"<?}?> onclick="toggleTicketSelection(this.id);"> Single
			</div>
		</div>
		<div id="single_layout" style="display:none;">
			<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
				<div align="right" style="width:375px; float:left; padding-right: 5px;">
					Video <span style="color:#A00000;">*</span>:
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<?=$videos_combo?>
				</div>
			</div>
		</div>
		<div id="playlist_layout" style="display:none;">
			<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
				<div align="right" style="width:375px; float:left; padding-right: 5px;">
					Video Playlist <span style="color:#A00000;">*</span>:
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<?=$channels_combo?>
				</div>
			</div>
		</div>
		<div id="global_layout"></div>
	</div>
		<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
			<div align="right" style="width:375px; float:left; padding-right: 5px;">
				Expiry period:
			</div>
			<div style="width:375px;float:left;padding-left: 5px;" align="left">
				Days <input type="text" name="expiry_days" size="10" value="<?=$expiry_days?>"><br/>
				Months <input type="text" name="expiry_months" size="10" value="<?=$expiry_months?>"><br/>
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
		<div id="playlist_global_layout" style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
			<div align="right" style="width:375px; float:left; padding-right: 5px;">
				Total allowed views per video:
			</div>
			<div style="width:375px;float:left;padding-left: 5px;" align="left">
				<input type="text" name="total_allowed_views_per_video" size="10" value="<?=$total_allowed_views_per_video?>">
			</div>
		</div>
		<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
			<div align="right" style="width:375px; float:left; padding-right: 5px;">
				How many passwords:
			</div>
			<div style="width:375px;float:left;padding-left: 5px;" align="left">
				<input type="text" name="count_passwords" size="10" value="<?=$count_passwords?>">
			</div>
		</div>
		<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
			<input type="submit" name="submit" value="Submit" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
		</div>
</form>
<script type="text/javascript">
var tickets = ['global', 'single', 'playlist'];
function toggleTicketSelection(ticket) {
	for(var i = 0; i < tickets.length; i++) {
		if(tickets[i] !== ticket) {
			document.getElementById(tickets[i] + '_layout').style.display = 'none';
		}
		else {
			document.getElementById(ticket + '_layout').style.display = '';
		}
	}
	if(ticket === 'playlist' || ticket === 'global') {
		document.getElementById('playlist_global_layout').style.display = '';
	}
	else {
		document.getElementById('playlist_global_layout').style.display = 'none';
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
toggleTicketLayout();
</script>