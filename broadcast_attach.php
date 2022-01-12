<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 1/19/15
 * Time: 4:38 PM
  */

	$msg = $err_msg = '';
	
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
	
	
	// creates resource type combo
	$type = postVal('type');
	$type_combo = arrayToCombo('type', $type, array('live' => 'Live','rtsp' => 'RTSP'), 'style="width:375px;" onchange="change_type(this.value);"'); // create a SelectBox with all videos.



	
	$rtsp_ref = postVal('rtsp_ref');

	$rtsp_list = getRTSPList();
	$error_code = arrayVal($rtsp_list, 'error_code'); // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code);
	}
	// If no errors, data will contains two arrays:
	// 1.ref_numbers => array with all reference numbers of rtsp cameras.
	// 2.names => array with all titles of rtsp cameras.
	$data = arrayVal($rtsp_list, 'data');
	$ref_numbers = arrayVal($data, 'ref_numbers');
	$names = arrayVal($data, 'names');

	$rtsps = array();
	if(!empty($ref_numbers) && !empty($names)) {
		$rtsps = array_combine($ref_numbers, $names); // array which contains ref_numbers for key & titles for values
	}
	$rtsp_combo = arrayToCombo('rtsp_ref', $rtsp_ref, $rtsps, 'style="width:375px;"'); // create a SelectBox with all videos.
	
	$submit = postVal('submit');
	if($submit){
		$service_response = broadcastAttachResource(array('video_ref' => $video_ref,'type' => $type,'rtsp_ref'=> $rtsp_ref));

		$error_code = arrayVal($service_response, 'error_code'); // check for error.
		// If error occurs - display the error.
		if($error_code) {
			$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
		}
		else {
			if($type === 'rtsp'){
				$msg = 'Successfully attached RTSP cam with reference No: ' . $rtsp_ref . ' to video with reference No: ' . $video_ref .  '. Response:'.arrayVal($service_response, 'response');
			}elseif($type === 'live'){
				$msg = 'Successfully detached video with reference No: ' . $video_ref .  '. Response:'.arrayVal($service_response, 'response');
			}
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
			Video <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$videos_combo?>
		</div>
	</div>
	
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			Resource Type <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$type_combo?>
		</div>
	</div>
	<div id="rtsp_only" style="display:none;">
		<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
			<div align="right" style="width:375px; float:left; padding-right: 5px;">
				RTSP cam <span style="color:#A00000;">*</span>:
			</div>
			<div style="width:375px;float:left;padding-left: 5px;" align="left">
				<?=$rtsp_combo?>
			</div>
		</div>
	</div>
	
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<input type="submit" name="submit" value="Submit" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
	</div>
</form>

<script type="text/javascript">
	function change_type(e){
		if(e === 'rtsp'){
			document.getElementById('rtsp_only').style.display = 'block';
		}else{
			document.getElementById('rtsp_only').style.display = 'none';
		}
	}
</script>