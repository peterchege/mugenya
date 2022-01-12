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
	
	$protection_walls = array('immediately' => 'Immediately', 'timed' => 'Timed');
	$protection_wall = postVal('protection_wall');
	$protection_walls_combo = arrayToCombo('protection_wall', $protection_wall, $protection_walls, 'onchange="toggleProtectionWall();"'); // create a SelectBox with all protection walls.
	
	$seconds = postVal('seconds');
	
	$submit = postVal('submit');
	if($submit) {
		$service_result = setVideoProtectionMode($video_ref, $protection_wall, $seconds);
		
		$error_code = arrayVal($service_result, 'error_code'); // check for error.
		// If error occurs - display the error.
		if($error_code) {
			$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
		}
		else {
			// If no errors, data will contains the reference number of new uploaded video.
			$data = arrayVal($service_result, 'data');
			$result = arrayVal($data, 'result');
			$msg = 'Result of service is '.$result.' !';
		}
	}
?>
<form method="post" name="ppv_free_form">
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<div align="center" style="font-weight:bold;">
			Protection Video Test
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
	<div style="width:760px;background-color:#fff;float:left;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Video <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$videos_combo?>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Display protection wall to viewer <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$protection_walls_combo?>
		</div>
	</div>
	<div id="seconds_layout" style="width:760px;background-color:#fff;float:left;display:none;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Seconds Free Preview <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="seconds" size="10" value="<?=$seconds?>">
		</div>
	</div>
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<input type="submit" name="submit" value="Submit" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
	</div>
</form>
<script type="text/javascript">
function toggleProtectionWall() {
	var field = document.getElementById('protection_wall');
	if(field.selectedIndex === 1) {
		document.getElementById('seconds_layout').style.display = '';
	}
	else {
		document.getElementById('seconds_layout').style.display = 'none';
	}
}
toggleProtectionWall();
</script>