<?php
	if(!session_id()) session_start();
	require('constants.php'); // constant defines
	require('svp_api_lib.php'); // functions library

	$operations = array('start' => 'Start', 'stop' => 'Stop');
	$selected_operation = getVal('operation', key($operations));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//'EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>SVP RECORDING API - Test Application</title>
	</head>
	<body style="background-color:#FFFFFF;color:#000000;">
		<div style="margin:0 auto;width:760px;border: 1px solid #EFEFEF;">
			<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
				SVP RECORDING API - Test Application
			</div>
			<div style="height:50px;background-color:#fff;font-weight:bold;" align="center">
				<div align="center" style="font-weight:bold;">
					Select operation:<br/>
					<? foreach($operations as $operation => $label) {?>
					<input type="radio" name="operation" id="<?=$operation?>" value="<?=$operation?>" <?if($operation === $selected_operation){?>checked="checked"<?}?> onclick="applyOperation(this.value);"> <?=$label?>
					<?}?>
				</div>
			</div>
			<? if($selected_operation) {
				include "recording_".$selected_operation.".php";
			}
			?>
		</div>
	</body>
	<script type="text/javascript">
	function applyOperation(value) {
		window.location.href = '?operation=' + value;
		return false;
	}
	</script>
</html>