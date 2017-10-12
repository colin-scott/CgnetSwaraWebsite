
<?php
		$edit_id = 9999;
		
		$command_output = array();
		$result = NULL;
		exec("/usr/local/bin/lame audio/$edit_id.mp3 --decode sounds/$edit_id.wav", $command_output, $result);



		if ($result !== 0) {
			echo 'Command failed!<br>';
			print_r($command_output);
			die();
		}

		echo 'success!';
		print_r($command_output); 

?>
