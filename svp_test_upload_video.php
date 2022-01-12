<?php
	if(!session_id()) session_start();
	require('constants.php'); // constant defines
	require('svp_api_lib.php'); // functions library

	// Get a list of upload sources. Do not use this function every time. You can save data in your DataBase.
	$upload_sources_list = getUploadSourcesList();

	$error_code = $upload_sources_list['error_code']; // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = getErrorMessage($error_code);
		die('Error code '.$error_code.': '.$err_msg);
	}
	// If no errors, data will contains two arrays:
	// 1.ref_numbers => array with all reference numbers of upload sources.
	// 2.names => array with all names of upload sources.
	$data = $upload_sources_list['data'];
	$ref_numbers = $data['ref_numbers'] ? $data['ref_numbers'] : array();
	$names = $data['names'] ? $data['names'] : array();
	$upload_sources = array();
	if(!empty($ref_numbers) && !empty($names)) {
		$upload_sources = array_combine($ref_numbers, $names); // array which contains ref_numbers for key & upload sources names for values
	}
	$upload_location_ref = isset($_POST['upload_location_ref']) ? $_POST['upload_location_ref'] : '';
	$upload_sources_combo = arrayToCombo('upload_location_ref', $upload_location_ref, $upload_sources); // create a SelectBox with all upload sources.

	// Get a list of upload sources. Do not use this function every time. You can save data in your DataBase.
	$channels_list = getChannelsList();

	$error_code = $channels_list['error_code']; // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = getErrorMessage($error_code);
		die('Error code '.$error_code.': '.$err_msg);
	}

	// If no errors, data will contains two arrays:
	// 1.ref_numbers => array with all reference numbers of TV channels.
	// 2.titles => array with all titles of channels.
	$data = $channels_list['data'];
	$ref_numbers = $data['ref_numbers'] ? $data['ref_numbers'] : array();
	$titles = $data['titles'] ? $data['titles'] : array();
	$channels = array();
	if(!empty($ref_numbers) && !empty($titles)) {
		$channels = array_combine($ref_numbers, $titles); // array which contains ref_numbers for key & channel titles for values
	}
	$channels = array(0 => '--Select Video Playlist--') + $channels; // set default value, channel_ref is optional parameter.
	$channel_ref = isset($_POST['channel_ref']) ? $_POST['channel_ref'] : '';
	$channels_combo = arrayToCombo('channel_ref', $channel_ref, $channels); // create a SelectBox with all TV channels.

	// Get a list of users. Do not use this function every time. You can save data in your DataBase.
	$users_list = getUsersList();

	$error_code = $channels_list['error_code']; // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = getErrorMessage($error_code);
		die('Error code '.$error_code.': '.$err_msg);
	}

	// If no errors, data will contains three arrays:
	// 1.ref_numbers => array with all reference numbers of users.
	// 2.user_names => array with all names of users.
	// 3.login_names => array with all login names of users.
	$data = isset($users_list['data']) ? $users_list['data'] : '';
	$ref_numbers = isset($data['ref_numbers']) ? $data['ref_numbers'] : array();
	$user_names = isset($data['user_names']) ? $data['user_names'] : array();
	$login_names = isset($data['login_names']) ? $data['login_names'] : array();
	// create a new user_info array in which values look like user_name(login_name)
	$user_info = array();
	foreach($ref_numbers as $key => $value) {
		$user_info[$key] = $user_names[$key].' ('.$login_names[$key].')';
	}
	$users = array();
	if(!empty($ref_numbers) && !empty($user_info)) {
		$users = array_combine($ref_numbers, $user_info); // array which contains ref_numbers for key & user_info for values
	}
	$users = array(0 => '--Select User--') + $users; // set default value, user_ref is optional parameter.
	$user_ref = isset($_POST['user_ref']) ? $_POST['user_ref'] : '';
	$users_combo = arrayToCombo('user_ref', $user_ref, $users); // create a SelectBox with all users.
	$msg = $err_msg = $file_name = $video_title = $tag_number = $tag_string = '';

	if($_POST) {
		$upload_location_ref = (int) $_POST['upload_location_ref'];
		$channel_ref = (int) $_POST['channel_ref'];
		$file_name = $_POST['file_name'];
		if(!$file_name) {
			$err_msg = 'Please enter a file name !'; // file name is required
		}
		$video_title = $_POST['video_title'];
		$tag_number = (int) $_POST['tag_number'];
		$tag_string = $_POST['tag_string'];
		$user_ref = (int) $_POST['user_ref'];

		if(!$err_msg) {
			// Make new upload.
			$upload_video = uploadVideo(urlencode($upload_location_ref), urlencode($channel_ref), urlencode($file_name), urlencode($video_title), urlencode($tag_number), urlencode($tag_string), urlencode($user_ref));

			$error_code = $upload_video['error_code']; // check for error.
			// If error occurs - display the error.
			if($error_code) {
				$err_msg = getErrorMessage($error_code);
				die('Error code '.$error_code.': '.$err_msg);
			}
			// If no errors, data will contains the reference number of new uploaded video.
			$data = $upload_video['data'];
			$ref_no = $data['ref_no'];
			$msg = 'The new video with reference code '.$ref_no.' was uploaded !';
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//'EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>SVP Upload Video - Test Application</title>
	</head>
	<body style="background-color:#FFFFFF;color:#000000;">
		<form method="post" name="upload_video_form">
			<table style="width:760px;height:50px;background-color:#EFEFEF;" align="center" border="0" cellpadding="4" cellspacing="2">
				<tr>
					<td align="center" style="font-weight:bold;">
						API - Upload Video
					</td>
				</tr>
				<?if($err_msg) {?>
				<tr>
					<td align="center" style="font-weight:bold;color:#A00000;">
						<?=$err_msg?>
					</td>
				</tr>
				<?}?>
				<?if($msg) {?>
				<tr>
					<td align="center" style="font-weight:bold;color:#0000F0;">
						<?=$msg?>
					</td>
				</tr>
				<?}?>
			</table>
			<table style="width:760px;background-color:#EFEFEF;" align="center" border="0" cellpadding="4" cellspacing="2">
				<tr style="background-color:#FFFFFF;">
					<td align="right" style="width:280px;">
						User:
					</td>
					<td align="left">
						<?=$users_combo?>
					</td>
				</tr>
				<tr style="background-color:#FFFFFF;">
					<td align="right" style="width:280px;">
						Upload location <span style="color:#A00000;">*</span>:
					</td>
					<td align="left">
						<?=$upload_sources_combo?>
					</td>
				</tr>
				<tr style="background-color:#FFFFFF;">
					<td align="right" style="width:280px;">
						Video Playlist:
					</td>
					<td align="left">
						<?=$channels_combo?>
					</td>
				</tr>
				<tr style="background-color:#FFFFFF;">
					<td align="right" style="width:280px;">
						File name <span style="color:#A00000;">*</span>:
					</td>
					<td align="left">
						<input type="text" name="file_name" size="40" value="<?=$file_name?>">
					</td>
				</tr>
				<tr style="background-color:#FFFFFF;">
					<td align="right" style="width:280px;">
						Video title:
					</td>
					<td align="left">
						<input type="text" name="video_title" size="40" value="<?=$video_title?>">
					</td>
				</tr>
				<tr style="background-color:#FFFFFF;">
					<td align="right" style="width:280px;">
						Tag number:
					</td>
					<td align="left">
						<input type="text" name="tag_number" size="10" value="<?=$tag_number?>">
					</td>
				</tr>
				<tr style="background-color:#FFFFFF;">
					<td align="right" style="width:280px;">
						Tag string:
					</td>
					<td align="left">
						<input type="text" name="tag_string" size="20" maxlength="100" value="<?=$tag_string?>">
					</td>
				</tr>
				<tr>
					<td align="right" style="width:280px;height:50px;">
						&nbsp;
					</td>
					<td align="left">
						<input type="submit" name="upload_video" value="Upload video" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>