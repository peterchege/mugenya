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

	$numbers = array();
	for($i = 1; $i <= 4; $i++) {
		$numbers[$i] = $i;
	}

	$number = postVal('number');
	$numbers_combo = arrayToCombo('number', $number, $numbers); // create a SelectBox with all numbers.

	$types = array('direct' => 'Direct', 'store' => 'Store');
	$type = postVal('type');
	$types_combo = arrayToCombo('type', $type, $types); // create a SelectBox with all types.

	$url = postVal('url', 'http://www.streamingvideoprovider.com/img/SVPVideo_Hosting.jpg');

	$submit = postVal('submit');
	if($submit) {
		$service_response = setVideoImage($video_ref, $number, $url, $type);

		$error_code = arrayVal($service_response, 'error_code'); // check for error.
		// If error occurs - display the error.
		if($error_code) {
			$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
		}
		else {
			$data = arrayVal($service_response, 'data');
			$result = arrayVal($data, 'result');
			$msg = 'Result of service is '.$result.' !';
		}
	}
?>
<form method="post" name="form">
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<div align="center" style="font-weight:bold;">
			Set Video Image Test
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
			Number <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$numbers_combo?>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			URL <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="url" maxchars="255" style="width:370px;" value="<?=$url?>"/>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Type <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$types_combo?>
		</div>
	</div>
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<input type="submit" name="submit" value="Submit" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
	</div>
</form>