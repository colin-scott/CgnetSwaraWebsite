<?php
include "loudblog/custom/config.php";
include "loudblog/inc/database/adodb.inc.php";
include "loudblog/inc/connect.php";
include "loudblog/inc/functions.php";

//create some important globals
if (!isset($db['host'])) { die("<br /><br />Cannot find a valid configuration file! <a href=\"install.php\">Install Loudblog now!</a>"); }
$GLOBALS['prefix'] = $db['pref'];
$GLOBALS['path'] = $lb_path;
$GLOBALS['audiopath'] = $lb_path . "/audio/";
global $cat_table;


function getLastFeedFromSuperdesk(){
	$fileHandler = fopen("lastSuperDeskFeed","r");
	$feedId = fread($fileHandler, filesize("lastSuperDeskFeed")).trim();
	fclose($fileHandler);
	return $feedId;
}

function saveFeedId($id){
	$fileHandler = fopen("lastSuperDeskFeed","w");
	fwrite($fileHandler, $id);
	fclose($fileHandler);
}

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
	// ID of the last previously updated post from superdesk to the database
	$feedId = getLastFeedFromSuperdesk();
	
	//Fetch the posted time of the last post from the database
	$queryToFetchTime = "SELECT posted from {$GLOBALS['prefix']}lb_postings WHERE id = " . intval($feedId) .";";
	$lastFeedPostedTime = $GLOBALS['lbdata']->Execute($queryToFetchTime);
	$lastFeedPostedTime = $lastFeedPostedTime->fields['posted'];
	
	//To store the ID in order to remove duplicate posts, if any, from superdesk
	$prevId = null;

	//To store the last post's ID
	$lastId = null;
	
	foreach($response_array->_items as $itemnumber => $feed_item){
		if(!empty($feed_item->headline)){
			//print_r($feed_item);

		?>
		<div style="width:90%;border:1px solid #efefef;margin:10px auto;padding:5px;">	

		<h2><?php echo $feed_item->headline; ?></h2>
		<p><?php echo $feed_item->body_html; ?></p>
		
		<?php
				$DOM = new DOMDocument();
				$DOM->loadHTML($feed_item->body_html);
				$msgInput = $DOM -> getElementsByTagName('p');
				$c = 0;
				foreach($msgInput as $ms){
					$c += 1;
					$inp = $ms -> nodeValue;
					if ($c>1)
						break;
				}

				//Extracting the URL of embedded images and/or audio files
				$audios = $DOM->getElementsByTagName('source');
				$images = $DOM->getElementsByTagName('img');

				foreach($audios as $audio){
		?>
		<p><b>Audio Embed URL: </b><?php echo $audio->getAttribute('src'); ?></p>
		<?php
					//Extracting the name of the audio file from the url
					$audioFile = end(explode('/', $audio->getAttribute('src')));
				}

				foreach($images as $image){
		?>
		<p><b>Image Embed URL: </b><?php echo $image->getAttribute('src'); ?></p>
		<?php
					//Extracting the name of the image file from the url
					$imageFile = end(explode('/', $image->getAttribute('src')));
				}
		?>	
				
		<?php
				//Extracting the post id
				$id = $feed_item -> postid;
				//Extractiong the original audio link from the feed
				$a = $DOM->getElementsByTagName('a');
				
				foreach($a as $link){
					if(end(explode('.', $link->getAttribute('href'))) == 'mp3'){
						//Extracting the post ID from the name of the original audio recording, since superdesk does not retain this ID after publishing
						//$id = explode('.',end(explode('/', $link->getAttribute('href'))))[0];
		?>
		<p><b>Audio URL: </b><?php echo $link->getAttribute('href'); ?></p>
		<?php
					}
				}
		?>
		<p><b>Post ID: </b><?php echo $id; ?></p>	
		</div> 
		<?php
		
		//If there are no new posts from superdesk, abort the process
		if ($itemnumber == 0 && $id==$feedId){
			//No new posts
			echo "No new posts";
			exit;
		}
		
		//To avoid duplicate posts from superdesk
		if ($id==$prevId){
			continue;
		}
		else{
			$prevId = $id;
		}

		//Date extracted from superdesk is further processed
		$extractedDate = strtotime(str_replace('T',' ',$feed_item->_created));
		$date = date('Y-m-d H:i:s', $extractedDate);
		$date = preg_replace('/[^0-9:-]/',' ',$date);
		
		//Save the last post's ID and abort the process since the posts before this date have already been updated in the database
		if($date<=$lastFeedPostedTime){
			saveFeedId($lastId);
			exit;
		}
		
		//Audio filename, message body and title of the post from superdesk
		$audioFile = isset($audioFile) ? $audioFile : $id.".mp3";
		$msg = $feed_item->body_html;
		
		$title = $feed_item->headline;
		$author_name = $feed_item->byline;
		$author_name = str_replace(" ", "_", $author_name);
		$subject_tags = $feed_item -> subject;
		//print_r($subject_tags[0]->name);
		$tags = $author_name." ";

		foreach($subject_tags as $subject_tag) {
			if ($subject_tag->parent=="04000000"){
				$tags .= $subject_tag->name." ";}
		}
		
		//echo "Tags ".$tags;		

		//Query to update the post in the databse
		$dosql  = sprintf("UPDATE {$GLOBALS['prefix']}lb_postings 
				   SET  posted = '".$date."',
					message_input='%s',
				        
					edited = 1, 
					tags = %s,
				        audio_file='%s', 
					title='%s',
					message_html = '%s'
				   WHERE id=%d", $inp, $tags, $audioFile, $title, $msg, intval($id));//status = 4,
		$dosql = "UPDATE {$GLOBALS['prefix']}lb_postings 
			SET  	posted = '".$date."',
				message_input='".$inp."',
				
				edited = 1, 
				tags = '".$tags."',
				audio_file='".$audioFile."', 
				title='".$title."', 
				message_html = '".$msg."'";//status = 3
		
		//If an image is embedded, the query further sets the image filename
		if (isset($imageFile)){
			$dosql .= " , image_on = 1, image_file = '".$imageFile."'";
		}
		$dosql .= " WHERE id= ". intval($id) .";";
		
		//Query executution
		$check = $GLOBALS['lbdata']->Execute($dosql) or die("<br>Error");
		if ($check){
			$lastId =$id;
			echo "Query executed";				
		}
		//echo "Feed id ".$feedId;
		}
	}
	
}
