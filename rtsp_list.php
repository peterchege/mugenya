<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 1/19/15
 * Time: 3:17 PM
  */

	$msg = $err_msg = '';
	
	// Get a list of rtsp cameras. Do not use this function every time. You can save data in your DataBase.
	$rtsp_list = getRTSPList();
	
	$error_code = arrayVal($rtsp_list, 'error_code'); // check for error.
	// If error occurs - display the error.
	if($error_code) {
		$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code);
	}
	// If no errors, data will contains five arrays:
	// 1.ref_numbers => array with all reference numbers of the cameras.
	// 2.names => array with all titles of cameras.
	// 3.urls => array with all addresses of cameras.
	// 4.statuses => array with all statuses of cameras.
	// 5.errors => array with all error messages of cameras.
	$data = arrayVal($rtsp_list, 'data');
	
	$ref_numbers = arrayVal($data, 'ref_numbers');
	$names = arrayVal($data, 'names');
	$urls = arrayVal($data, 'urls');
	$statuses = arrayVal($data, 'statuses');
	$errors = arrayVal($data, 'errors');

?>

	<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
		<div align="center" style="font-weight:bold;">
	List RTSP cameras Test
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

<? if(!$error_code) { ?>
	<div style="width:760px;background-color:#fff;float:left;border-bottom: 1px solid #EFEFEF;" align="center">
		<table border="1" >
			<thead>
			<tr>
				<td>Ref No</td>
				<td>Name</td>
				<td>Url</td>
				<td>Status</td>
				<td>Message</td>
			</tr>
			</thead>
			<tbody>
			<? foreach($ref_numbers as $index => $number) {
				$name = arrayVal($names, $index);
				$url = arrayVal($urls, $index);
				$status = arrayVal($statuses, $index);
				$error = arrayVal($errors, $index);
				?>
				<tr>
					<td><?=$number?></td>
					<td ><?=$name?></td>
					<td ><?=$url?></td>
					<td><?=$status?></td>
					<td><?=$error?></td>
				</tr>
			<? } ?>
			</tbody>
		</table>
	</div>
<? } ?>