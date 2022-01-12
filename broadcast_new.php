<?php
	$msg = $err_msg = '';

	// Get a list of channels. Do not use this function every time. You can save data in your DataBase.
	$channels_list = getChannelsList();

	$error_code = arrayVal($channels_list, 'error_code'); // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code);
	}
	// If no errors, data will contains two arrays:
	// 1.ref_numbers => array with all reference numbers of channels.
	// 2.titles => array with all titles of channels.
	$data = arrayVal($channels_list, 'data');

	$ref_numbers = arrayVal($data, 'ref_numbers', array());
	$titles = arrayVal($data, 'titles', array());
	$channels = array('' => 'None');
	if(!empty($ref_numbers) && !empty($titles)) {
		$channels += array_combine($ref_numbers, $titles); // array which contains ref_numbers for key & titles for values
	}
	$channel_ref = postVal('channel_ref');
	$channels_combo = arrayToCombo('channel_ref', $channel_ref, $channels, 'style="width:375px;"'); // create a SelectBox with all channels.
	/***/

	$whitelabels = array('no' => 'No', 'yes' => 'Yes');
	$whitelabel = postVal('whitelabel');
	$whitelabels_combo = arrayToCombo('whitelabel', $whitelabel, $whitelabels); // create a SelectBox with all whitelabels.

	$video_title = postVal('video_title');
	$tag_number = postVal('tag_number');
	$tag_string = postVal('tag_string');

	$submit = postVal('submit');
	if($submit) {
		$service_response = newBroadcastFeed($whitelabel, $video_title, $tag_number, $tag_string, $channel_ref);

		$error_code = arrayVal($service_response, 'error_code'); // check for error.
		// If error occurs - display the error.
		if($error_code) {
			$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
		}
		else {
			$data = arrayVal($service_response, 'data');
			$result = arrayVal($data, 'result');
			$ref_no = arrayVal($data, 'ref_no');
			$video_key = arrayVal($data, 'video_key');
			$msg = 'Result of service is '.$result.'. Broadcast with reference No '.$ref_no.' and video key '.$video_key.' was created successfully!';
		}
	}
?>
<form method="post" name="form">
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<div align="center" style="font-weight:bold;">
			New Broadcast Feed Test
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
			Whitelabel:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$whitelabels_combo?>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Video title:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="video_title" style="width:370px;" value="<?=$video_title?>"/>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Tag number:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="tag_number" style="width:370px;" value="<?=$tag_number?>"/>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Tag string:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="tag_string" style="width:370px;" value="<?=$tag_string?>"/>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Channel <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$channels_combo?>
		</div>
	</div>
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<input type="submit" name="submit" value="Submit" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
	</div>
</form>