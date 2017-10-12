<?php
$savephone= "";
$savename = "";

?>
<?php
//bug in comments section removed
echo "<h1>".bla("hl_rec2")."</h1>";

include ('inc/functions_record.php');
include ('inc/navigation.php');



 error_reporting(E_ALL);
//where did we get the audio file from? go to appropriate function!
//and don't come back without the id of the posting we will edit later!

if (isset($_GET['do'])) {

 $source = "./test.txt";
   $destination = "./copy.txt";
                 
   

if ($_GET['do'] == "browser") {
    if (isset($_GET['id'])){
		$edit_id = upload_browser($_GET['id']);
		/*$oldDir = "/sounds/";
		$newDir = "/sounds/original/";
		$oldFile = $oldDir . $edit_id . ".wav";
		$newFile = $newDir . $edit_id . ".wav";		
		echo "<a href=$oldFile>oldFile</a>";
		echo "</br>";
		echo "<a href=$newFile>newFile</a>";
		echo "</br>";*/
		exec("mv sounds/$edit_id.wav sounds/original/$edit_id.wav");
		exec("/usr/local/bin/lame audio/$edit_id.mp3 --decode sounds/$edit_id.wav", $output = array(),$result);		
		exec("sox -V sounds/$edit_id.wav -r 8000 -c 1 sounds/$edit_id.raw");
		
	
	/*if ($result !== 0) {
		echo 'Command failed!<br>';
		print_r($command_output);
		die();
	}

	echo 'success!';
	print_r($output);  */ 
			
									
		
		}
    else $edit_id = upload_browser(false);
}

if ($_GET['do'] == "cgi") {
    if (isset($_GET['id'])) $edit_id = cgi_copy($_GET['id']);
    else $edit_id = cgi_copy(false);
}
    
if (($_GET['do'] == "web") AND ($_POST['method'] == "link")) {
    if (isset($_GET['id'])) $edit_id = link_web($_GET['id']);
    else $edit_id = link_web(false); 
}
    
if (($_GET['do'] == "web") AND ($_POST['method'] == "copy")) {
    if (isset($_GET['id'])) $edit_id = fetch_web($_GET['id']);
    else $edit_id = fetch_web(false); 
}
//in following line, replaced "ftp" with "transfer" to avoid problems where mod security is installed
if ($_GET['do'] == "transfer") {
    if (isset($_GET['id'])) $edit_id = copy_ftp($_GET['id']);
    else $edit_id = copy_ftp(false); 
}

if ($_GET['do'] == "nofile") {
    if (isset($_GET['id'])) $edit_id = nofile($_GET['id']);
    else $edit_id = nofile(false); 
}


//oh, we only got here from the postings-page to edit some posting?
//oh, we only got back here from the id3edit-page?
if ($_GET['do'] == "edit")
$edit_id = $_GET['id']; 

//we want to save some changes? here we go!
# bill - also check if there is any post data, otherwise this will clear everything
$edit_id = $_GET['id']; 
if ($_GET['do'] == "save" and "" != trim($_POST['title'])) {
    //build the date string
    if (!isset($_POST['now'])) {
    $posted = $_POST['post1'] . "-" . $_POST['post2'] . "-" .
              $_POST['post3'] . " " . $_POST['post4'] . ":" .
              $_POST['post5'] . ":00";
    } else { 
      date_default_timezone_set("Asia/Kolkata");
      $posted = date("Y-m-d H:i:s"); }

  //sticky postings
    if (!isset($_POST['sticky']))
    $sticks = "0";
    else $sticks = "1";

    //make a valid temp-title and put textile onto the posted bodytext
    $temptitle = htmlentities($_POST['title'], ENT_QUOTES, "UTF-8");
    $tempmess = htmlentities($_POST['message'], ENT_QUOTES, "UTF-8");
    $tempsms = htmlentities($_POST['sms_summary'], ENT_QUOTES, "UTF-8");
    
    //extract duration- and size integer from input 
    $pieces = explode (" ", $_POST['audio_length']);
    $lengthint = round ($pieces[0]);
    $pieces2 = explode (" ", $_POST['audio_size']);
    $sizeint = round ($pieces2[0]) * 1024 * 1024;
    
    //use preferred html-helper tool
    $temphtml = makehtml($_POST['message']);
    
    //get the data for comment-options
    if ($_POST['comment_on'] == "on") $comments = "1"; else $comments = "0";    
   
    //get the data for image-options
    if (isset($_POST['image_on']) AND $_POST['image_on'] == "on") $show_image = "1"; else $show_image = "0";
 
    //get the old posting date (for comparing later on)
    $dosql = "SELECT posted FROM ".$GLOBALS['prefix']."lb_postings 
          WHERE id = '". $edit_id ."'";
    $result = $GLOBALS['lbdata']->GetArray($dosql);
    $olddate = $result[0]['posted'];
    
    //get the tag string ready for posting
	$_POST['tags'] = str_replace(',', ' ', $_POST['tags']);
	$tags = htmlentities($_POST['tags'], ENT_QUOTES, "UTF-8");

//modify the tag so that it include state dis block subject
$tags = $tags." ".$_POST["state"]." ".$_POST["dis"]." ".$_POST["block"]." ".$_POST["subject"];
print "The tag is : ".$tags;

//write things from post-data into database
    $dosql = "UPDATE ".$GLOBALS['prefix']."lb_postings SET
    
              title         = '" . $temptitle . "',
              message_input = '" . $tempmess . "',
              sms_summary = '" . $tempsms . "',
              message_html  = '" . $temphtml . "',
              posted        = '" . $posted . "',
              comment_on    = '" . $comments . "',
              audio_length  = '" . $lengthint . "', 
              audio_size    = '" . $sizeint . "', 
              comment_size  = '" . $_POST['comment_size'] . "',
		  category1_id  = '" . $_POST['cat1'] . "',
		  category2_id  = '" . $_POST['cat2'] . "',
		  category3_id  = '" . $_POST['cat3'] . "',
	        category4_id  = '" . $_POST['cat4'] . "',
              tags  	    = '" . $tags . "',
		  sticky	    = '" . $sticks . "' ,
              audio_type    = '" . $_POST['audio_type'] . "',
              author_id     = '"  .$_POST['author']. "',
              status        = '" . $_POST['status'] . "',
              circle_un = '" . $_POST['circle_un'] . "',
              circle_ap = '" . $_POST['circle_ap'] . "',
              circle_as = '" . $_POST['circle_as'] . "',
              circle_br = '" . $_POST['circle_br'] . "',
              circle_ch = '" . $_POST['circle_ch'] . "',
              circle_dl = '" . $_POST['circle_dl'] . "',
              circle_gj = '" . $_POST['circle_gj'] . "',
              circle_hp = '" . $_POST['circle_hp'] . "',
              circle_hr = '" . $_POST['circle_hr'] . "',
              circle_jk = '" . $_POST['circle_jk'] . "',
              circle_kl = '" . $_POST['circle_kl'] . "',
              circle_ka = '" . $_POST['circle_ka'] . "',
              circle_ko = '" . $_POST['circle_ko'] . "',
              circle_mh = '" . $_POST['circle_mh'] . "',
              circle_mp = '" . $_POST['circle_mp'] . "',
              circle_mu = '" . $_POST['circle_mu'] . "',
              circle_ne = '" . $_POST['circle_ne'] . "',
              circle_or = '" . $_POST['circle_or'] . "',
              circle_pb = '" . $_POST['circle_pb'] . "',
              circle_rj = '" . $_POST['circle_rj'] . "',
              circle_tc = '" . $_POST['circle_tc'] . "',
              circle_tn = '" . $_POST['circle_tn'] . "',
              circle_ue = '" . $_POST['circle_ue'] . "',
              circle_uw = '" . $_POST['circle_uw'] . "',
              circle_wb = '" . $_POST['circle_wb'] . "',
              playon_main = '" . $_POST['playon_main'] . "',
              playon_gondi = '" . $_POST['playon_gondi'] . "',

              image_on = '" . $show_image . "'
              WHERE id = '" . $edit_id . "'";

//print "<br><br>The qry is : ".$dosql."<br><br>";
    $GLOBALS['lbdata']->Execute($dosql);

$dosql = "UPDATE ".$GLOBALS['prefix']."lb_settings SET
		       value  = '" . $_POST['previews'] . "'
           WHERE name = 'previews'";
           $GLOBALS['lbdata']->Execute($dosql);


    //deleting links from database
    $dosql = "DELETE FROM ".$GLOBALS['prefix']."lb_links
              WHERE posting_id = '" . $edit_id . "'";
    $GLOBALS['lbdata']->Execute($dosql);

    //put posted links into database
    for ($i = 0; $i< $settings['showlinks']; $i++) {
        $temptit = "linktit" . $i;
        $tempurl = "linkurl" . $i;
        $tempdes = "linkdes" . $i;

        $writetit = htmlentities($_POST[$temptit], ENT_QUOTES, "UTF-8");
        $writedes = htmlentities($_POST[$tempdes], ENT_QUOTES, "UTF-8");
        $writeurl = htmlentities($_POST[$tempurl], ENT_QUOTES, "UTF-8");

        if (strrpos($writeurl, "://") == false) {
        	$writeurl = "http://".$writeurl;
        }

        if ($_POST[$tempurl] != "") {
        $dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_links
                 (posting_id, linkorder, title, url, description)
                 VALUES
                 (
                 '" . $edit_id . "', '" . $i . "',
                 '" . $writetit . "', '" . $writeurl . "', '" . $writedes . "'
                 )";
        $GLOBALS['lbdata']->Execute($dosql);
        }
    }


   $sph = $_POST["authorphone"];
   $sna = $_POST["authorname"];
print "The phone is : ".$sph;
print "The name is : ".$sna;
$savephone = $sph;
$savename = $sna;

if($savephone == "yourself" || $savename =="Unknown"){}
else
{
$pqry = 'insert into phonebook (phone,name) select * from(select "'.$savephone.'","'.$savename.'" ) as temp where not exists (select name,phone from phonebook where phone = "'.$savephone.'")limit 1;';

mysql_query($pqry);

$modqry = 'insert into moderator values ("'.$edit_id.'","'.$_POST['mod'].'");';
mysql_query($modqry);
}

    //ping via xml-rpc if possible and if posting is "live"
    if (
    ($settings['ping'] == "1") AND
    (ini_get('allow_call_time_pass_reference') == "1") AND
    ($_POST['status'] == 3)
    ) {
        echo '<script type="text/javascript">active_popup("index.php?page=ping","ping", 170,35);</script>';
    }

    //create fresh version of the static xml feeds
    if ($settings['staticfeed'] == "1") { include ('inc/staticfeeds.php'); }
}


//gets all the data from the posting-id we want to edit
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_postings
          WHERE id = '". $edit_id ."'";
$result = $GLOBALS['lbdata']->GetArray($dosql);
$fields = $result[0];


//if posting date has changed, try to rename audio file, too
$newdate = $fields['posted'];
if (isset($olddate)
AND ($newdate != $olddate)
AND ($fields['filelocal'] == "1")
AND ($settings['rename'] == "1")) {
    $suffix = strrchr($fields['audio_file'], ".");
    $newname = buildaudioname($suffix, $settings['filename'], $newdate);
    //rename the file
    $oldpath = $GLOBALS['audiopath'] . $fields['audio_file'];
    $newpath = $GLOBALS['audiopath'] . $newname;

    if(rename ($oldpath, $newpath))  {
    $fields['audio_file'] = $newname;
    $dosql = "UPDATE ".$GLOBALS['prefix']."lb_postings SET
        posted =     '" . $newdate . "',
        audio_file = '" . $newname . "'
        WHERE id = '" . $edit_id . "'";
    $GLOBALS['lbdata']->Execute($dosql);
    }
}


// --------------------------------------------------------------------------
$settings = getsettings();

include "changepost.php";

//show us the (pre-filled) forms to change the details!!!
echo "<form action=\"index.php?page=record2&amp;do=save&amp;id=". $edit_id;
echo "\" method=\"post\" enctype=\"multipart/form-data\">";


?>

<div id="leftcolumn">

<!--                                      title  -->
<?php
echo "  <h3>".bla("rec2_title")."</h3>\n";
echo "  <input id=\"title\" type=\"text\" name=\"title\"";
echo readonly($edit_id);
echo " value=\"" . urldecode($fields['title']) . "\" />\n\n";
?>


<!--                                      text message  -->
<?php
echo "  <h3>".bla("rec2_message")."</h3>\n";
echo "  <textarea " . readonly($edit_id) . " name=\"message\">";
echo trim($fields['message_input']);
echo "</textarea>";
?>


<!--                                      sms message  -->
<?php
echo "<h3> Hindi Message : </h3>\n";
echo "  <textarea " . readonly($edit_id) . " name=\"sms_summary\">";
echo trim($fields['sms_summary']);
echo "</textarea>";
?>


<!--                                      tags          -->
<script>

function selectItem(li) {

}

function formatItem(row) {
	return row;
}

$(document).ready(function() {
	$("#suggest").autocomplete('autocomplete.php', { minChars:1, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1, mode:"multiple",multipleSeparator:" " });
});
</script>

<?php
print "<br><br>";
echo "<h3>".bla("rec2_tags")."</h3>\n";


echo readonly($edit_id);
$valtag = urldecode($fields['tags']);

//print "The val is : ".$valtag;

$tagarr = explode(" ",$valtag);
//print "The tag 0 is : ".$tagarr[0];
$tagcount = count($tagarr);
$res = "";


for($i= 0;$i<$tagcount-4;$i++)
{
   $res = $res.$tagarr[$i]." ";
}
//print "The res is : ".$res;
echo "<input id=\"suggest\" type=\"text\" class=\"tags\" name=\"tags\" ";
echo readonly($edit_id);
$res = substr($res,0,-1);
echo "value=\"".$res."\"/>";

//echo "The value found is : ".$valtag;
?>

<!--                                      categories  -->
<?php
/*
echo "<h3>".bla("rec2_cats")."</h3>\n";

//getting all data from category-table
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_categories ORDER BY id";
$cats = $GLOBALS['lbdata']->GetArray($dosql);

//show four lists with categories
for ($i=1; $i<5; $i++) {
    echo "<select class=\"category\" ". readonly($edit_id);
    echo " name=\"cat" . $i . "\">\n";
    echo "<option value=\"\">---</option>\n";

    //show all items in each list
    $j = 0;
    foreach ($cats as $showcat) {
        echo "<option value=\"" . $cats[$j]['id'] . "\"";
        $temp = "category" . ($i) . "_id";
        if ($fields[$temp] == $cats[$j]['id']) echo " selected";
        echo ">" . urldecode($cats[$j]['name']) . "</option>\n";
        $j += 1;
    }
    echo "</select>\n";
}
*/
?>
<?php






$tstate = $tagarr[$tagcount-4];
$tdis = $tagarr[$tagcount-3];
$tblock = $tagarr[$tagcount-2];
$tsub = $tagarr[$tagcount-1];


$state = ["sta","stb","stc"];
$dist = [
		["dis1","dis2","dis3"],
		["dis4","dis5"],
		["dis6","dis7","dis8","dis9"],
	];
$block = [
		[
			["b1","b2","b3"],
			["b4","b5"],
			["b6","b7","b8"],
		],		
		[
			["b9"],
			["b10"],
		],
		[
			["b11","b12"],
			["b13","b14"],
			["b15"],
			["b16","b17"],
		],
	];
$subject = ["s1","s2","s3","s4"];

$sg = 0;
$dg = 0;

$state_sel = '<select id="state" name="state" class="scat" onchange="f1();" >';
$state_sel_op = "\n";
for($i=0;$i<count($state);$i++)
{
	if(strcmp($tstate,$state[$i])==0)
	{
		$sg = $i;
		$state_sel_op = $state_sel_op . "<option selected= 'selected' >".$tstate."</option>\n";
	}
	else
	{
		$state_sel_op = $state_sel_op . "<option>".$state[$i]."</option>\n";
	}
}
$state_sel = $state_sel.$state_sel_op."</select>\n\n";

$dist_sel = '<select id="dis" name="dis" class="scat" onchange="f2();">';
$dist_sel_op = "\n";
for($i=0;$i<count($dist[$sg]);$i++)
{
	if(strcmp($tdis,$dist[$sg][$i])==0)
	{
		$dg = $i;
		$dist_sel_op = $dist_sel_op . "<option selected= 'selected' >".$tdis."</option>\n";
	}
	else
	{
		$dist_sel_op = $dist_sel_op . "<option>".$dist[$sg][$i]."</option>\n";
	}
}
$dist_sel = $dist_sel.$dist_sel_op."</select>\n\n";


$block_sel = '<select id="block" name="block" class="scat">';
$block_sel_op = "\n";
for($i=0;$i<count($block[$sg][$dg]);$i++)
{
	if(strcmp($tblock,$block[$sg][$dg][$i])==0)
	{
		$block_sel_op = $block_sel_op . "<option selected= 'selected' >".$tblock."</option>\n";
	}
	else
	{
		$block_sel_op = $block_sel_op . "<option>".$block[$sg][$dg][$i]."</option>\n";
	}
}
$block_sel = $block_sel.$block_sel_op."</select>\n\n";


$subject_sel = '<select id="subject" name="subject" class="scat">';
$subject_sel_op = "\n";
for($i=0;$i<count($subject);$i++)
{
	if(strcmp($tsub,$subject[$i])==0)
	{
		$subject_sel_op = $subject_sel_op . "<option selected= 'selected' >".$tsub."</option>\n";
	}
	else
	{
		$subject_sel_op = $subject_sel_op . "<option>".$subject[$i]."</option>\n";
	}
}
$subject_sel = $subject_sel.$subject_sel_op."</select>\n\n";



print $state_sel." ";
print $dist_sel." ";
print $block_sel." ";
print $subject_sel." ";

print "<br>";

?>
<script src="./menu.js"></script>

<?php
$mqry = "select * from moderator where id = '".$_GET['id']."';";
//print "<br>The querry is : ".$mqry."</br>";
$res = $GLOBALS['lbdata']->GetArray($mqry);
$cx = count($res) - 1;
/*
$mod ="";
$modv = "";
if($mod =mysql_fetch_array($res))  
{
     $modv = $mod[1];
}            
*/
print"\n<br><br><font color='white'>MODERATOR : <input type='text' name='mod' value='".$res[$cx]['moderator']."'></font>\n<br><br>";
?>


<!--      author     -->
 <?php

 
if(getuserrights("admin"))  {
    $dosql = "SELECT id, nickname FROM ".$GLOBALS['prefix']."lb_authors where id = '".$fields['author_id']."';";
    $authorarray = $GLOBALS['lbdata'] -> GetArray($dosql);
    foreach ($authorarray as $author)  {
      if($author['id'] == $fields['author_id']) {
                            $me = $author['nickname'];
                            $my_id = $author['id'];
      }
    }

echo "<input type=\"hidden\" name=\"author\" value=\"".$my_id."\">";
/*
    echo "<h3>".bla("rec2_author")."</h3>\n\n";
    echo "<select name = \"author\" class = \"author\">\n";
    echo "<option value =\"".$my_id."\">".$me."</option>";
    foreach ($authorarray as $author)  {
      if ($author['id'] == $my_id) { continue;}
      echo "<option value = \"".$author['id']."\">".$author['nickname']."</option>\n"; }
    echo"</select><br />";
*/
} else  {

        echo "<input type=\"hidden\" name=\"author\" value=\"".$fields['author_id']."\">";

} 

?>

<?php
extract($_GET);
?>
<?php
if($phone == "")
{
$phone = $_POST['authorphone'];

}
echo "<br><font color='white'>AUTHOR OF THE  POSTING : </font><input type='text' id='authorphone' name='authorphone' readonly value='".$phone."'>";

//echo "<input type='hidden' value = '".$phone."' name = 'author'></input>";
?>

<?php
if($name == "")
{
$name = $_POST['authorname'];
}
echo "<br><br><font color='white'>NAME OF THE AUTHOR : </font><input type='text' id='authorname' name='authorname' value='".$name."'>";

?>

<!--                                      sticky posting  -->
<?php
#echo "<h3>".bla("rec2_sticky")."</h3>\n";
#echo "<select name=\"sticky\" class=\"sticky\">";

#echo "<option class=\"sticky\" value=\"1\" ";
#if (urldecode($fields['sticky'])) echo 'selected="selected"';
#echo " >" . bla("yes");

#echo "<option class=\"sticky\" value=\"0\" ";
#if (!urldecode($fields['sticky'])) echo 'selected="selected"';
#echo " >" . bla("no");

#echo "</option>";
#echo "</select>";
#echo readonly($edit_id);
#echo "value=\"".urldecode($fields['sticky'])."\"/>";
?>
</div>

<div id="rightcolumn">


<!--                                      file  -->

<?php
//$tempauth = getnickname($fields['author_id']);
//if ($tempauth == $_SESSION['nickname']) { $tempauth = "yourself"; }

if (!empty($fields['audio_file']) AND ($fields['audio_file'] != "NULL")) {

echo "<h3>".bla("rec2_audio");
//echo " (".bla("rec2_by")." ".$tempauth.")";
echo "</h3>\n";

//do we have a local file? then show detailled information!
if ($fields['filelocal'] == 1) {

//showing and linking to audiofile. local or not?
$link = $settings['url'] . "/audio/" . $fields['audio_file'];
$type = $fields['audio_type'];



//can...only...play...mp3...with...flash...player
if ($fields['audio_type'] == 1) {
    echo showflash ("backend/emff_rec2.swf?src=".$link, 295, 9);
	
	
	
}

//can...play...several...other...formats...with...quicktime
if (($type == "2") OR
    ($type == "5") OR
    ($type == "6") OR
    ($type == "9") OR
    ($type == "12") OR
    ($type == "14") OR
    ($type == "10") OR
    ($type == "13") OR
    ($type == "7")) {
    $src = $settings['url']."/loudblog/backend/clicktoplayback2.mov";

        $target = "myself";

        //ist it a video or enhanced podcast? Link to external player!
        if (($type == "14") OR
            ($type == "10") OR
            ($type == "13") OR
            ($type == "7")) {
            $target = "quicktimeplayer";
        }
    //build html-code for quicktime plugin (audio)
    echo showquicktime ($src, $link, 295, 16, $target, "false");
}


//preparing data
$id3 = getid3data($GLOBALS['audiopath'].$fields['audio_file'],"back");

//showing data for that mp3 file
echo "<table id=\"audiodata\">\n";
echo "<tr><td>".bla("rec2_filename")."</td><td><a href=\"" . $link . "\">";
echo $fields['audio_file'] . "</a></td></tr>\n";

echo "<tr><td>".bla("rec2_sizedur")."</td><td>".getmegabyte($id3['size'])."MB / ". $id3['duration'].bla("short_minutes")."</td></tr>\n";

echo "<tr><td>".bla("rec2_quality")."</td><td>";
echo ($id3['audio']['bitrate'] / 1000)."kb/s (";
echo strtoupper($id3['audio']['bitrate_mode']).") / ";
echo ($id3['audio']['sample_rate'] / 1000)."kHz / ";
echo ($id3['audio']['channelmode']);
echo "</td></tr>\n";

echo "<tr><td>".bla("rec2_id3")."</td><td>".$id3['title'];
echo " (".$id3['track'].")</td></tr>\n";
echo "</table>\n";

//button for manipulating id3-tags
if ((allowed(1,$edit_id)) AND ($fields['audio_type'] == "1")) {
    echo "<input href=\"index.php?page=id3&amp;id={$fields['id']}\" ";
    echo "class=\"audiobutton\" value=\"".bla("but_editid3")."\" type=\"button\" ";
    echo "onClick=\"link_popup(this,780,400); return false\" />";
}

//button for changing the audio file
if (allowed(1,$edit_id)) {
    echo "<input class=\"audiobutton right\" value=\"".bla("but_changeaudio")."\" type=\"button\"";
    echo " onClick=\"self.location.href='index.php?page=record1&amp;do=update&amp;id={$fields['id']}'\" />";
}


//sending size/length/type information for database
echo "<input type=\"hidden\" name=\"audio_length\" value=\"".getseconds($id3['duration'])."\" />";
echo "<input type=\"hidden\" name=\"audio_size\" value=\"".getmegabyte($id3['size'])."\" />";
echo "<input type=\"hidden\" name=\"audio_type\" value=\"".type_suffix($fields['audio_file'])."\" />";

} else {
//we have only a link to a remote file? show other data!

$link = $fields['audio_file'];
$type = $fields['audio_type'];

//can...only...play...mp3...with...flash...player
if ($type == "1") {
    echo showflash("backend/emff_rec2.swf?src=".$link, 295, 9);
}

//can...play...several...other...formats...with...quicktime
if (($type == "2") OR
    ($type == "5") OR
    ($type == "6") OR
    ($type == "9") OR
    ($type == "12") OR
    ($type == "14") OR
    ($type == "10") OR
    ($type == "13") OR
    ($type == "7")) {
    $src = $settings['url']."/loudblog/backend/clicktoplayback2.mov";

    $target = "myself";

        //ist it a video or enhanced podcast? Link to external player!
        if (($type == "14") OR
            ($type == "10") OR
            ($type == "13") OR
            ($type == "7")) {
            $target = "quicktimeplayer";
        }
    //build html-code for quicktime plugin (audio)
    echo showquicktime($src, $link, 295, 16, $target, "false");
}

//showing plain link
echo "<table id=\"audiodata\">\n";
echo "<tr><td><a href=\"" . $link . "\">";
echo wordwrap($fields['audio_file'], 50, "<br />", 1) . "</a></td></tr>\n";
echo "</table>\n";

echo "<input type=\"hidden\" name=\"audio_type\" value=\"".type_suffix($fields['audio_file'])."\" />";

?>

<hr />


<!--                                      size  -->
<div class="rec2size">
<?php
echo "<h3>".bla("rec2_size")."</h3>\n";
if ($fields['filelocal'] == 1) {
echo "<input ". readonly($edit_id) . "type=\"text\" readonly=\"readonly\" ";
echo "value=\"" . getmegabyte($fields['audio_size']) . " MB\" />";
echo "<input type=\"hidden\" name=\"audio_size\" ";
echo "value=\"" . getmegabyte($fields['audio_size']) . "\" />";
} else {
echo "<input ". readonly($edit_id) . "type=\"text\" name=\"audio_size\"";
echo "value=\"" . getmegabyte($fields['audio_size']) . " MB\" />";
}
?>

</div>


<!--                                      length  -->

<div class="rec2size">
<?php
echo "<h3>".bla("rec2_dur")."</h3>\n";
echo "<input " . readonly($edit_id) . "type=\"text\" name=\"audio_length\" value=\"" . $fields['audio_length']." ".bla("short_seconds")."\" />"; ?>
</div>


<div class="fileinfo right">
<?php
echo "<h3>".bla("rec2_audiofile")."</h3>";

//button for changing the audio file

if (allowed(1,$edit_id)) {
    echo "<input class=\"audiobutton change\" value=\"".bla("but_changeaudio")."\" type=\"button\"";
    echo " onClick=\"self.location.href='index.php?page=record1&amp;do=update&amp;id=";
    echo $fields['id'] . "'\" />";
}
echo "</div>\n";


}

echo "\n\n<hr />\n\n";

} else {
// We have no audio file at all? Huh? Okay then...

echo "<h3>".bla("rec2_noaudio")."</h3>\n";
echo "<div class=\"fileinfo left\">\n";
echo "<p>".bla("rec2_audiolater")."</p>\n";
echo "</div>\n\n";

//show hidden input fields with zero values
echo "<input type=\"hidden\" name=\"audio_length\" value=\"0\" />";
echo "<input type=\"hidden\" name=\"audio_size\" value=\"0\" />";
echo "<input type=\"hidden\" name=\"audio_type\" value=\"0\" />";

echo "<div class=\"fileinfo right\">";
if (allowed(1,$edit_id)) {
    echo "<input class=\"audiobutton change\" value=\"".bla("but_addaudio")."\" type=\"button\"";
    echo " onClick=\"self.location.href='index.php?page=record1&amp;do=update&amp;id=";
    echo $fields['id'] . "'\" />";
}
echo "</div>";
echo "<hr />";
}

?>

<!-- image file, if present -->
<?php
if (!empty($fields['image_file']) AND ($fields['image_file'] != "NULL")) {
    if (isset($fields['image_on']) AND $fields['image_on'] == 1) { $temp1 = 'checked="checked"'; $temp2 = ''; }
                             else { $temp1 = ''; $temp2 = 'checked="checked"'; }
    $image_link = $settings['url'] . "/audio/" . $fields['image_file'];
?>
    <div style="display:block;">
        <h3>IMAGE FILE</h3>
        <img src="<?php echo $image_link; ?>" style="margin:5px 0; width:70%; display:block;" />
        <input <?php echo readonly($edit_id); echo $temp1; ?> class="radio" type="radio" name="image_on" value="on" />on&nbsp;&nbsp;
        <input <?php echo readonly($edit_id); echo $temp2; ?> class="radio" type="radio" name="image_on" value="off"/>off
    </div>
    <hr/>
<?php
}
?>


<!--                                      comments  -->


<?php
//preparing preselection of the comments-on/off-switch
if ($fields['comment_on'] == 1) { $temp1 = 'checked="checked"'; $temp2 = ''; }
                          else { $temp1 = ''; $temp2 = 'checked="checked" '; }

//preparing preselection of the comments-size-menue
$tempcommsize = array(
    "0"=>bla("rec2_noaudioallowed"),
    "204800"=>"200 KB",
    "512000"=>"500 KB",
    "1048576"=>"1 MB",
    "1572864"=>"1.5 MB",
    "2097152"=>"2 MB",
    "5242880"=>"5 MB",
    "10485760"=>"10 MB",
    "999999999"=>bla("rec2_nolimit")
)
?>

<div class="fileinfo left">
<?php echo "<h3>".bla("rec2_comments")."</h3>";

echo "<input ".readonly($edit_id)." class=\"radio\" type=\"radio\" name=\"comment_on\" value=\"on\" ".$temp1." />".bla("on")."&nbsp;&nbsp;\n";

echo "<input ".readonly($edit_id)." class=\"radio\" type=\"radio\" name=\"comment_on\" value=\"off\" ".$temp2." />".bla("off");
?>

</div>


<div class="fileinfo right">
<?php
echo "<h3>".bla("rec2_sizelimit")."</h3>";
echo "<select ".readonly($edit_id)." name=\"comment_size\">";

//generating dropdownmenue with comment-sizes
foreach ($tempcommsize as $tempsize => $tempshow) {
    echo "<option " . readonly($edit_id) . "value=\"".$tempsize."\"";
    if ($tempsize == $fields['comment_size']) echo " selected";
    echo ">".$tempshow."</option>\n";
}
?>
</select>
</div>

<hr />




<!--                                      status -->

<?php
//very un-elegant preparing for showing the current status
$temp = array ("", "", "", "");



print"<br><br>";

include "chk_blank.php";

$type = $fields['audio_type'];
print "<br>".$link;

$n_arr = explode ("/", $link);
$n_len = count($n_arr)-1;
$f_name = $n_arr[$n_len];

print "The name is : ".$f_name;

$call_value = chk_blank($f_name);
print "<br>The call value is : ".$call_value;
print "<br><br>";

$got_value = (int)$fields['status'];

$temp[$fields['status']] = "checked=\"checked\"";

if($got_value == 1)
{
	$call_value = chk_blank($f_name);
	$call_value = (int) $call_value;
	if($call_value == 0)
	{
		$temp[7] = "checked=\"checked\"";
	}
	else
	{
		$tem[1] = "checked=\"checked\"";
	}

}
else
{
	$temp[$fields['status']] = "checked=\"checked\"";
}


echo "<h3>".bla("rec2_status")."</h3>";
?>
<input type="radio" name="status" value="1"
        <?php echo readonly($edit_id).$temp[1]." />".bla("DRAFT"); ?> &nbsp;&nbsp;

<input type="radio" name="status" value="2"
        <?php echo readonly($edit_id) . $temp[2]." />".bla("FINISHED"); ?> &nbsp;&nbsp;


<input type="radio" name="status" value="3"

        <?php if (!allowed(2,$fields['id'])) { echo "readonly=\"readonly\""; }
        echo $temp[3]." />".bla("ON AIR"); ?>

<br>
<input type="radio" name="status" value="4"
        <?php echo readonly($edit_id) . $temp[4]." />".bla("STR"); ?> &nbsp;&nbsp;
	      
<input type="radio" name="status" value="5"
        <?php echo readonly($edit_id) . $temp[5]." />".bla("ES"); ?> &nbsp;
<input type="radio" name="status" value="6"
        <?php echo readonly($edit_id) . $temp[6]." />".bla("MODENC"); ?> &nbsp;
	      
<input type="radio" name="status" value="7"
        <?php echo readonly($edit_id) . $temp[7]." />".bla("NTR"); ?> &nbsp;

<input type="radio" name="status" value="8"
        <?php echo readonly($edit_id) . $temp[8]." />".bla("n1"); ?> &nbsp;

<input type="radio" name="status" value="9"
        <?php echo readonly($edit_id) . $temp[9]." />".bla("n2"); ?> &nbsp;

<input type="radio" name="status" value="10"
        <?php echo readonly($edit_id) . $temp[10]." />".bla("n3"); ?> &nbsp;

<input type="radio" name="status" value="11"
        <?php echo readonly($edit_id) . $temp[11]." />".bla("m1"); ?> &nbsp;

<input type="radio" name="status" value="12"
        <?php echo readonly($edit_id) . $temp[12]." />".bla("m2"); ?> &nbsp;

<input type="radio" name="status" value="13"
        <?php echo readonly($edit_id) . $temp[13]." />".bla("m3"); ?> &nbsp;

<hr />

<!.... CIRCLE SETTING ......-->
<script language="JavaScript">
function toggle(val) {
  document.getElementsByName('circle_un')[0].checked = val;
  document.getElementsByName('circle_ap')[0].checked = val;
  document.getElementsByName('circle_as')[0].checked = val;
  document.getElementsByName('circle_br')[0].checked = val;
  document.getElementsByName('circle_ch')[0].checked = val;
  document.getElementsByName('circle_dl')[0].checked = val;
  document.getElementsByName('circle_gj')[0].checked = val;
  document.getElementsByName('circle_hp')[0].checked = val;
  document.getElementsByName('circle_hr')[0].checked = val;
  document.getElementsByName('circle_jk')[0].checked = val;
  document.getElementsByName('circle_kl')[0].checked = val;
  document.getElementsByName('circle_ka')[0].checked = val;
  document.getElementsByName('circle_ko')[0].checked = val;
  document.getElementsByName('circle_mh')[0].checked = val;
  document.getElementsByName('circle_mp')[0].checked = val;
  document.getElementsByName('circle_mu')[0].checked = val;
  document.getElementsByName('circle_ne')[0].checked = val;
  document.getElementsByName('circle_or')[0].checked = val;
  document.getElementsByName('circle_pb')[0].checked = val;
  document.getElementsByName('circle_rj')[0].checked = val;
  document.getElementsByName('circle_tc')[0].checked = val;
  document.getElementsByName('circle_tn')[0].checked = val;
  document.getElementsByName('circle_ue')[0].checked = val;
  document.getElementsByName('circle_uw')[0].checked = val;
  document.getElementsByName('circle_wb')[0].checked = val;
}
</script>

<?php
echo "<h3>".bla("rec2_channel")."</h3>";
?>
<input type="checkbox" value="1" name="playon_main" <?php if ($fields['playon_main']==1) echo 'checked';?> /> Main channel
<br><input type="checkbox" value="1" name="playon_gondi" <?php if ($fields['playon_gondi']==1) echo 'checked';?> /> Gondi channel
<hr />

<?php
echo "<h3>".bla("rec2_circle")."</h3>";
?>
    <button type="button" onClick="toggle(1)">Select All</button>
    <button type="button" onClick="toggle(0)">Select None</button>
       <br><input type="checkbox" value="1" name="circle_un" <?php if ($fields['circle_un']==1) echo 'checked';?> /> Unknown callers (circle not detected)

<br><input type="checkbox" value="1" name="circle_ap" <?php if ($fields['circle_ap']==1) echo 'checked';?> /> AP - Andhra Pradesh
<br><input type="checkbox" value="1" name="circle_as" <?php if ($fields['circle_as']==1) echo 'checked';?> /> AS - Assam
<br><input type="checkbox" value="1" name="circle_br" <?php if ($fields['circle_br']==1) echo 'checked';?> /> BR - Bihar & Jharkhand
<br><input type="checkbox" value="1" name="circle_ch" <?php if ($fields['circle_ch']==1) echo 'checked';?> /> CH - Chennai Metro (includes Chennai, MEPZ & Mahabalipuram).
<br><input type="checkbox" value="1" name="circle_dl" <?php if ($fields['circle_dl']==1) echo 'checked';?> /> DL - Delhi Metro (includes NCR, Faridabad, Ghaziabad, Gurgaon & Noida).						  
<br><input type="checkbox" value="1" name="circle_gj" <?php if ($fields['circle_gj']==1) echo 'checked';?> /> GJ - Gujarat (includes Daman & Diu, Dadra & Nagar Haveli).							  
<br><input type="checkbox" value="1" name="circle_hp" <?php if ($fields['circle_hp']==1) echo 'checked';?> /> HP - Himachal Pradesh 												  
<br><input type="checkbox" value="1" name="circle_hr" <?php if ($fields['circle_hr']==1) echo 'checked';?> /> HR - Haryana (excludes Faridabad, Gurgaon & Panchkula).								  
<br><input type="checkbox" value="1" name="circle_jk" <?php if ($fields['circle_jk']==1) echo 'checked';?> /> JK - Jammu & Kashmir 												  
<br><input type="checkbox" value="1" name="circle_kl" <?php if ($fields['circle_kl']==1) echo 'checked';?> /> KL - Kerala (includes Lakshadweep).										  
<br><input type="checkbox" value="1" name="circle_ka" <?php if ($fields['circle_ka']==1) echo 'checked';?> /> KA - Karnataka 													  
<br><input type="checkbox" value="1" name="circle_ko" <?php if ($fields['circle_ko']==1) echo 'checked';?> /> KO - Kolkata Metro (includes parts of Howrah, Hooghly, North & South 24 Parganas and Nadia Districts).		  
<br><input type="checkbox" value="1" name="circle_mh" <?php if ($fields['circle_mh']==1) echo 'checked';?> /> MH - Maharashtra (includes Goa but excludes Mumbai, Navi Mumbai & Kalyan).					  
<br><input type="checkbox" value="1" name="circle_mp" <?php if ($fields['circle_mp']==1) echo 'checked';?> /> MP - Madhya Pradesh & Chhattisgarh 										  
<br><input type="checkbox" value="1" name="circle_mu" <?php if ($fields['circle_mu']==1) echo 'checked';?> /> MU - Mumbai Metro (includes Navi Mumbai & Kalyan)								  
<br><input type="checkbox" value="1" name="circle_ne" <?php if ($fields['circle_ne']==1) echo 'checked';?> /> NE - North East India (includes Arunachal Pradesh, Meghalaya, Mizoram, Nagaland, Manipur and Tripura).		  
<br><input type="checkbox" value="1" name="circle_or" <?php if ($fields['circle_or']==1) echo 'checked';?> /> OR - Orissa 													  
<br><input type="checkbox" value="1" name="circle_pb" <?php if ($fields['circle_pb']==1) echo 'checked';?> /> PB - Punjab (includes Chandigarh & Panchkula).									  
<br><input type="checkbox" value="1" name="circle_rj" <?php if ($fields['circle_rj']==1) echo 'checked';?> /> RJ - Rajasthan 													  
<br><input type="checkbox" value="1" name="circle_tc" <?php if ($fields['circle_tc']==1) echo 'checked';?> /> TC - (Not clear what this circle is)
<br><input type="checkbox" value="1" name="circle_tn" <?php if ($fields['circle_tn']==1) echo 'checked';?> /> TN - Tamil Nadu (excludes CH Chennai, MEPZ, Mahabalipuram & Minjur and includes Pondicherry except Yanam & Mahe). 
<br><input type="checkbox" value="1" name="circle_ue" <?php if ($fields['circle_ue']==1) echo 'checked';?> /> UE - Uttar Pradesh (East)											  
<br><input type="checkbox" value="1" name="circle_uw" <?php if ($fields['circle_uw']==1) echo 'checked';?> /> UW - Uttar Pradesh (West) & Uttarakhand (excludes Ghaziabad & Noida).						  
<br><input type="checkbox" value="1" name="circle_wb" <?php if ($fields['circle_wb']==1) echo 'checked';?> /> WB - West Bengal (includes Andaman & Nicobar, Sikkim excludes Calcutta Telecom District).

<hr />

<!....PREVIEW SETTING......-->
<?php
echo "<h3>".bla("rec2_preview")."</h3>";

 $temp = array ("", "");
    $temp[$settings['previews']] = "checked=\"checked\""; ?>
    <input class="radio" name="previews" type="radio" value="1" <?php echo $temp[1]."/>Yes"; ?>&nbsp;&nbsp;
    <input class="radio" name="previews" type="radio" value="0" <?php echo $temp[0]."/>No"; ?>


<hr />


</div>

<div id="postsave">



<!--                                      Posted  -->

<?php echo "<h3>".bla("rec2_posttime")."</h3>"; ?>
<div id="date">



<input id="year" type="text" name="post1" maxlength="4" value="<?php
    echo date("Y", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>
<input type="text" name="post2" maxlength="2" value="<?php
    echo date("m", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>
<input type="text" name="post3" maxlength="2" value="<?php
    echo date("d", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>
<h4> <?php echo bla("at"); ?> </h4>
<input type="text" name="post4" maxlength="2" value="<?php
    echo date("H", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>
<h4>:</h4>
<input type="text" name="post5" maxlength="2" value="<?php
    echo date("i", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>

<h4> <?php echo bla("rec2_setnow"); ?> </h4>
<input <?php echo readonly($edit_id); ?> id="now" type="checkbox" name="now" />
<?php if (urldecode($fields['sticky']))
{$checked = 'checked = "checked"';}
else {$checked = '';} ?>
<h4> <?php echo bla("rec2_sticks"); ?> </h4>
<input <?php echo readonly($edit_id); ?> id="sticky" type="checkbox" name="sticky" <?php echo $checked; ?> />
<br/>



</div>




<!--                                      submit-button  -->
<div class="submit">
<?php
if (allowed(1,$edit_id)) {
echo "<input class=\"save\" type=\"submit\" value=\"".bla("but_saveall")."\" />";
}
?>
</div>

</div>


<div id="hyperlinks">



<!--                                      links  -->
<?php

//getting existing links from database
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_links 
          WHERE posting_id = '" . $edit_id . "' 
          ORDER BY linkorder ASC";
$links = $GLOBALS['lbdata']->GetArray($dosql);

echo "<table class=\"plain topspace\">\n<tr>\n<th>".bla("rec2_linkurl")."</th>\n";
echo "<th>".bla("rec2_linkname")."</th>\n<th>";
echo bla("rec2_linkdesc")."</th>\n</tr>\n";

for ($i = 0; $i < $settings['showlinks']; $i++) {
    
    //no entry in database? generate empty data!
    if (!isset($links[$i]['linkorder'])) {
        $links[$i]['url'] = "";
        $links[$i]['title'] = "";
        $links[$i]['description'] = "";
    }
 
    //show the link-forms
    echo "<tr>";
    
    echo "<td class=\"left\"><input ". readonly($edit_id) . " type=\"text\" value=\"" . $links[$i]['url'];
    echo "\" name=\"linkurl" . $i . "\" /></td>\n";
    
    echo "<td class=\"center\"><input ". readonly($edit_id) . " type=\"text\" value=\"" . $links[$i]['title'];
    echo "\" name=\"linktit" . $i . "\" /></td>\n";
    
    echo "<td class=\"right\"><input ". readonly($edit_id) . " type=\"text\" value=\"" . $links[$i]['description'];
    echo "\" name=\"linkdes" . $i . "\" /></td>\n";
    
    echo "</tr>";
}

?>
</table>

</div>



<!--                                      submit-button  -->
<div class="submit">

<?php
if (allowed(1,$edit_id)) {
echo "<input class=\"save\" type=\"submit\" value=\"".bla("but_saveall")."\" />";
}
?>
</div>



</form>

<?php 


    
}

//no audio file? error message!
else { 

echo "<p class=\"msg\">".bla("msg_noaudio")."</p>\n\n"; 

}


?>
