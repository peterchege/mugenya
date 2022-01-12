<?php
	if(!session_id()) session_start();
	require('constants.php'); // constant defines
	require('svp_api_lib.php'); // functions library
	
	$msg = $err_msg = '';
	
	$order_key = postVal('order_key');
	$email = postVal('email');
	$first_name = postVal('first_name');
	$last_name = postVal('last_name');
	$transaction_id = postVal('transaction_id');
	$item_name = postVal('item_name');
	$amount = postVal('amount');
	$currency = postVal('currency');
	$return = postVal('return');
	$cancel = postVal('cancel');
	
	if(!$order_key || !$amount || !$currency || !$return || !$cancel) {
		exit("Invalid input parameters");
	}
	else {
		$pay_action = postVal('pay_action');
		if($pay_action) {
			$service_response = confirmPPVOrder($order_key, $email, $first_name, $last_name, $transaction_id);
			
			$error_code = arrayVal($service_response, 'error_code'); // check for error.
			// If error occurs - display the error.
			if($error_code) {
				$err_msg = 'Error code '.$error_code.': '.getErrorMessage($error_code).', Response:'.arrayVal($service_response, 'response');
			}
			else {
				// If no errors, data will contains the reference number of new uploaded video.
				$data = arrayVal($service_response, 'data');
				$result = arrayVal($data, 'result');
				if($result === 'OK') {
					header("Location: {$return}");
					exit();
				}
			}
		}
		
		$cancel_action = postVal('cancel_action');
		if($cancel_action) {
			header("Location: {$cancel}");
			exit();
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//'EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>SVP PPV API - Custom Payment Test Application</title>
	</head>
	<body style="background-color:#FFFFFF;color:#000000;">
		<form method="post" name="form">
			<input type="hidden" name="order_key" value="<?=$order_key?>">
			<input type="hidden" name="item_name" value="<?=$item_name?>">
			<input type="hidden" name="amount" value="<?=$amount?>">
			<input type="hidden" name="currency" value="<?=$currency?>">
			<input type="hidden" name="return" value="<?=$return?>">
			<input type="hidden" name="cancel" value="<?=$cancel?>">
			<div style="margin:0 auto;width:760px;border: 1px solid #EFEFEF;">
				<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
					SVP PPV API - Custom Payment Test Application
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
					Item name :
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<?=$item_name?>
				</div>
			</div>
			<div style="width:760px;background-color:#fff;float:left;" align="center">
				<div align="right" style="width:375px; float:left; padding-right: 5px;">
					Amount :
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<?=$amount.' '.$currency?>
				</div>
			</div>
			<div style="width:760px;background-color:#fff;float:left;" align="center">
				<div align="right" style="width:375px; float:left; padding-right: 5px;">
					Email :
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<input type="text" name="email" value="<?=$email?>">
				</div>
			</div>
			<div style="width:760px;background-color:#fff;float:left;" align="center">
				<div align="right" style="width:375px; float:left; padding-right: 5px;">
					First Name :
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<input type="text" name="first_name" value="<?=$first_name?>">
				</div>
			</div>
			<div style="width:760px;background-color:#fff;float:left;" align="center">
				<div align="right" style="width:375px; float:left; padding-right: 5px;">
					Last Name :
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<input type="text" name="last_name" value="<?=$last_name?>">
				</div>
			</div>
			<div style="width:760px;background-color:#fff;float:left;" align="center">
				<div align="right" style="width:375px; float:left; padding-right: 5px;">
					Transaction ID :
				</div>
				<div style="width:375px;float:left;padding-left: 5px;" align="left">
					<input type="text" name="transaction_id" value="<?=$transaction_id?>">
				</div>
			</div>
			<div style="width:760px;height:50px;background-color:#EFEFEF;font-weight:bold; display:table-cell; vertical-align: middle;" align="center">
				<input type="submit" name="pay_action" value="Pay" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
				<input type="submit" name="cancel_action" value="Cancel" style="background-color:orange;cursor:pointer;font-size:14px;color:#144AAD;">
			</div>
		</form>
	</body>
</html>