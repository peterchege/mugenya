<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 1/19/15
 * Time: 11:35 AM
  */
/*	
	if(!session_id()) session_start();
	require('constants.php'); // constant defines
	require('svp_api_lib.php'); // functions library
	*/
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
	
	$combo_params = array('no' => 'No', 'yes' => 'Yes');
	$whitelabel = postVal('whitelabel');
	$whitelabels_combo = arrayToCombo('whitelabel', $whitelabel, $combo_params); // create a SelectBox with all whitelabels.
	
	$new_broadcast = postVal('new_broadcast');
	$new_broadcast_combo = arrayToCombo('new_broadcast', $new_broadcast, $combo_params,'onchange="have_broadcast(this.value)"'); // create a SelectBox with is new option.

	$rtsp_name = postVal('rtsp_name');
	$rtsp_url = postVal('rtsp_url');
	$video_title = postVal('video_title');
	$tag_number = postVal('tag_number');
	$tag_string = postVal('tag_string');

	$submit = postVal('submit');
	if($submit) {
		$service_response = newRTSPcam($rtsp_name,$rtsp_url,$new_broadcast,$whitelabel, $video_title, $tag_number, $tag_string, $channel_ref); // create RTSP
	
		$error_code = arrayVal($service_response, 'error_code'); // check for error.
		// If error occurs - display the error.
		if($error_code) {
			$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
		}
		else {
			$data = arrayVal($service_response, 'data');
			$result = arrayVal($data, 'result');
			$ref_no = arrayVal($data, 'ref_no');
			$clip = arrayVal($data, 'clip');
			$msg = 'Result of service is '.$result.'. RTSP camera with reference No '.$ref_no.' was created successfully!';
			if($clip){
				$msg .= ' Also created a new clip with ID: ' .$clip . ' and attached the camera to it!';
			}
		}
	}


?>

<form method="post" name="form">
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<div align="center" style="font-weight:bold;">
			New RTSP Test
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
			RTSP title <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="rtsp_name" style="width:370px;" value="<?=$rtsp_name?>"/>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			RTSP url <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="rtsp_url" style="width:370px;" value="<?=$rtsp_url?>"/>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			New broadcast:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$new_broadcast_combo?>
		</div>
	</div>
	<div id="have_broadcast" style="display:none;">
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

	</div>
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<input type="submit" name="submit" value="Submit" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
	</div>
</form>

<script type="text/javascript">
	function have_broadcast(e){
		if(e === 'yes'){
			document.getElementById('have_broadcast').style.display = 'block';
		}else{
			document.getElementById('have_broadcast').style.display = 'none';
		}
	}
</script>