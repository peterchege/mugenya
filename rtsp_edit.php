<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 1/19/15
 * Time: 3:15 PM
  */

$msg = $err_msg = '';

$rtsp_ref = postVal('rtsp_ref');
$rtsp_name = postVal('rtsp_name');
$rtsp_url = postVal('rtsp_url');
$submit = postVal('submit');
if($submit) {
	$service_response = editRTSPcam(array('rtsp_ref' => $rtsp_ref,'rtsp_name' => $rtsp_name, 'rtsp_url' => $rtsp_url));

	$error_code = arrayVal($service_response, 'error_code'); // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
	}
	else {
		$msg = 'Successfully editted RTSP cam with reference No: ' . $rtsp_ref . '. Response:'.arrayVal($service_response, 'response');
	}
}
// Get a list of rtsp cameras. Do not use this function every time. You can save data in your DataBase.
$rtsp_list = getRTSPList();
$error_code = arrayVal($rtsp_list, 'error_code'); // check for error.
// If error occurs - display the error.
if($error_code) {
	$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code);
}
$data = arrayVal($rtsp_list, 'data');

$ref_numbers = arrayVal($data, 'ref_numbers');
$names = arrayVal($data, 'names');
$urls = arrayVal($data, 'urls');

$rtsps = array();
if(!empty($ref_numbers) && !empty($names)) {
	$rtsps = array_combine($ref_numbers, $names); // array which contains ref_numbers for key & titles for values
	$rtsps_urls = array_combine($ref_numbers, $urls); // array which contains ref_urls for key & urls for values
}
if($rtsps){
	$view = (int)getVal('view'); // view parameter is used to point current editted rtsp cam
	if(!$view) {
		$view = $ref_numbers[0];
	}

	$rtsp_combo = arrayToCombo('rtsp_ref', $view, $rtsps, 'style="width:375px;" onchange="applyView(this.value)"'); // create a SelectBox with all rtsp cameras.

}

?>

<form method="post" name="form">
	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<div align="center" style="font-weight:bold;">
			Edit RTSP Camera Test
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
	<?if($rtsps){?>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			RTSP camera <span style="color:#A00000;">*</span>:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<?=$rtsp_combo?>
		</div>
	</div>
	
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			RTSP title:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="rtsp_name" style="width:370px;" value="<?=$rtsps[$view]?>"/>
		</div>
	</div>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<div align="right" style="width:375px; float:left; padding-right: 5px;">
			RTSP url:
		</div>
		<div style="width:375px;float:left;padding-left: 5px;" align="left">
			<input type="text" name="rtsp_url" style="width:370px;" value="<?=$rtsps_urls[$view]?>"/>
		</div>
	</div>
	

	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<input type="submit" name="submit" value="Submit" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
	</div>
	<?}?>
</form>

<script type="text/javascript">
	function applyView(value) {
		window.location.href = '?operation=edit&view=' + value;
		return false;
	}
</script>