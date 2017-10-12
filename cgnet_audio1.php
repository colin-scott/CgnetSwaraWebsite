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
		?>
		<div style="width:90%;border:1px solid #efefef;margin:10px auto;padding:5px;">	

		<h2><?php echo $feed_item->headline; ?></h2>
		<p><?php echo $feed_item->body_html; ?></p>

		<?php
				$DOM = new DOMDocument();
				$DOM->loadHTML($feed_item->body_html);
				$audios = $DOM->getElementsByTagName('source');
				foreach($audios as $audio){
		?>
		<p><b>Audio Embed URL: </b><?php echo $audio->getAttribute('src'); ?></p>
		<?php
				}
		?>	
		<?php
				$a = $DOM->getElementsByTagName('a');
				foreach($a as $link){
					if(end(explode('.', $link->getAttribute('href'))) == 'mp3'){
		?>
		<p><b>Audio URL: </b><?php echo $link->getAttribute('href'); ?></p>
		<?php
					}
				}
		?>	
		</div> 
		<?php
		}
	}
}
