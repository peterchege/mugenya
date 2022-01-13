<?php
$url = "https://portal.pioneerassurance.co.ke:8080/afintegration/confirmcode";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = <<<DATA
{
"transcode": "QAD0JI4EEG",
"amount":1
}
DATA;

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

$resp = curl_exec($curl);
curl_close($curl);

$resp2=json_decode($resp,true);

// if(json_decode($resp, true)){
//     echo "invalid amount payed, retry";
// };
// else{
//     echo "Payment successful"
// };

// print $resp2->{"status"};
// echo $resp2
if($resp2["status"]==true){
   echo "true";
}else{
   echo "false";
}
// var_dump($resp2["status"]);
?>
