<?php
	// API entry address
	$entry_point = 'http://www.streamingvideoprovider.com/';
  
	// Enter your publisher API Key and API Code bellow
	$api_key = 'apc-oFGMvFMMIx6K';
	$api_code = 'apc-ypEEM3txnrvs';
	
	// REIMPLEMENT THE FUNCTIONS BELOW WITH A RELIABLE PERSISTENT STORAGE!!!
	// Saving the token locally.
	// Do not use SESSION in server to server environment!
	function saveToken($token) {
		$_SESSION['token'] = $token;
	}

	// Loading the locally saved token.
	// Do not use SESSION in server to server environment!
	function loadToken() {
		return isset($_SESSION['token']) ? $_SESSION['token'] : null;
	}
?>