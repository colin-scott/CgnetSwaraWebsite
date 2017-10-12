<?php


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://52.2.107.80/api/auth");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"username\": \"admin\",
  \"password\": \"superdesk\"
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json;charset=UTF-8"
));

$response = curl_exec($ch);
curl_close($ch);

$response_array = json_decode($response);
$auth_token = $response_array->token;

$ch = curl_init();

$headr = array();
$headr[] = 'Content-Type: application/json';
$headr[] = 'Authorization: Token '.$auth_token;

curl_setopt($ch, CURLOPT_URL, "http://52.2.107.80/api/published");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);

$response = curl_exec($ch);
curl_close($ch);

$response_array = json_decode($response);


if($response_array){
	foreach($response_array->_items as $feed_item){
		if(!empty($feed_item->headline)){
		echo "<h1>1</h1>";
		print_r($feed_item);
		exit;
		?>
		<div style="width:90%;border:1px solid #efefef;margin:10px auto;padding:5px;">		

		<h2><?php echo $feed_item->headline; ?></h2>
		<p><?php echo $feed_item->body_html; ?></p>
		<p><?php echo $feed_item->firstcreated; ?></p>
		
		
		 
		<?php
		/* To extract link from body */
		$body_data = $feed_item->body_html;
		$startpos = strrpos($body_data, "http");
		$endpos = strpos($body_data, '"', $startpos);
		$link = substr($body_data, $startpos, $endpos - $startpos);
		
		?>
		<p>Link: <?php echo $link; ?></p>
		</div>
		<?php
		}
		
	}
}
