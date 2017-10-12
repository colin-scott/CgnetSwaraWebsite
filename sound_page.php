<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 

  <head profile="http://gmpg.org/xfn/11"> 


	<link rel="stylesheet" type="text/css" href="CssFiles/menu.css">
	<link rel="icon" type="image/png" href="http://cgnetswara.org/loudblog/custom/templates/default/swaraicon.png">
	<link rel="stylesheet" type="text/css" href="CssFiles/upload_page.css" />
	<link rel="stylesheet" type="text/css" href="CssFiles/master.css" />

	<title>CGNet Upload Audio</title>

 
    
 <script>
		function clipBoard(copytextId){
			
			var copytext = document.getElementById(copytextId);
			var range = document.createRange();
			range.selectNode(copytext);
			window.getSelection().addRange(range);
			try {  
    				
    				var successful = document.execCommand('copy');  
    				var msg = successful ? 'successful' : 'unsuccessful';  
    				
  			} 
			catch(err) {  
    				alert('Unable to copy, please copy manually');  
  			}  
			window.getSelection().removeAllRanges();

		}
	</script>

    
 </head>
<body id="body-index">
 <body id="body-inside">
    <div id="wrapper">

<br>

      <div id="content" class="clearfix">

        <div id="nav" class="clearfix">
        
          <h1><a href="http://cgnetswara.org/"><img id="logo" 
src="http://cgnetswara.org/loudblog/custom/templates/default/swaracover-small.png"
width=220
alt="CGNet Swara Logo" /></a></h1>
        
<h2 class="header">
<table><tr>
<td>
          <a href="http://cgnetswara.org/about.html">About</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<!--
</td><td>
          <a href="http://cgnetswara.org/news.html">News & Events</a>&nbsp;&nbsp;|&nbsp;&nbsp;
-->
</td><td>
          <a href="http://cgnetswara.org/impact2.php">Impact</a>&nbsp;&nbsp;|&nbsp;&nbsp;
</td><td>
          <a href="http://cgnetswara.org/recognition.html">Recognition</a>

</td></td>
</table>
</h2>

          <h2 class="slogan">CGNet Swara is a platform to discuss issues related to Central Gondwana region in India. To record a message, call us on <strong>+91-80500 68000</strong>.<lb:siteslogan /></h2>
          
        </div><!-- nav -->
               



        <div id="primary">

	  	<?php

	if (($_FILES['aud']['type']=="audio/mp3") || ($_FILES['aud']['type']=="audio/wav") || ($_FILES['aud']['type']=="audio/mpeg")) {
		
		$file_handler = fopen("counter.txt","r+");
		
		$count = fread($file_handler, filesize("counter.txt"));
		fseek($file_handler, 0);
		
		
		if (move_uploaded_file($_FILES["aud"]["tmp_name"], "/home/stanford/web/upload/" . $count.".mp3"))  {
			echo "<div class='upload_content'><h1><b>Audio file successfully uploaded</b></h1>";
			echo "<p>Copy link:-</p>";?>
			<p id="copytext"><?php echo "<b>".htmlspecialchars('<audio controls><source src="http://cgnetswara.org/stanford/upload/'.$count.'.mp3" type="audio/mpeg">')."</b>";?></p>
			<?php
			$number = (int)$count + 1;
			if (fwrite($file_handler, $number)==FALSE){ echo "error";}
			?>
			
			<button class='submit_button copy_button' onclick="clipBoard('copytext');">Copy to Clipboard</button>
			</div>
		<?php
		}
		else
			echo "<h3>File not uploaded, try again</h3>";
		fclose($file_handler);
	}
	else{
		echo "<h1>Invalid File</h1>";
	}
	
	if (isset($_FILES['img_file'])) {
		
		if (move_uploaded_file($_FILES["img_file"]["tmp_name"], "/home/stanford/web/uploadImages/" . $count.".jpeg"))  {
			echo "<div class='upload_content'><h1><b>Image file successfully uploaded</b></h1>";
			echo "<p>Copy link:-</p>";?>
			<p id="filetext"><?php echo "<b>".htmlspecialchars('<img src="http://cgnetswara.org/stanford/uploadImages/'.$count.'.jpeg" ></img>')."</b>";?></p>
			
			
			<button class="submit_button copy_button" onclick="clipBoard('filetext');">Copy to Clipboard</button>
			</div>

		<?php
		}
		else
			echo "<h3>File not uploaded, try again</h3>";
		}
	else{
		echo "<h1>Invalid File</h1>";
	}





?>	</div>
     
</body> 
 
</html>
