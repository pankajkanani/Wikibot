<?php
include('db.php');
$input=file_get_contents('php://input');
$data=json_decode($input);
$uname=$data->message->from->first_name;
$chat_id=$data->message->chat->id;
$text=$data->message->text;

if($text=='/start'){
	$msg="Welcome $uname. %0APlease enter your url";
}else{
	$text=urlencode(ucwords($text));
	$url="https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro&explaintext&titles=$text";
	$data=file_get_contents($url);
	$data=json_decode($data,true);
	if(isset($data['query']['pages'])){
		$arr=$data['query']['pages'];
		if(isset($arr['-1'])){
			$msg="No data found";
		}else{
			foreach($arr as $list){
				$msg=urlencode($list['extract']);
			}
		}
	}else{
		$msg="Something went wrong. Please try after sometime";
	}
}
$url="https://api.telegram.org/botTOKEN/sendMessage?text=$msg&chat_id=$chat_id&parse_mode=html";
file_get_contents($url);
?>