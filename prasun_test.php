<?php 

include 'loudblog/inc/loudblogtags.php';
//$image= array();
//$image[0]= randomimage('../../harish/web/loudblog/custom/templates/default/compressed');
//$image[0]= randomimage('./ppimages');

$counter = random_id('./ppimages');
$imageval = $_GET['id'];
echo $imageval;
//if ($imageval >= $counter)
    //$imageval = 1;
$imageval = ($imageval % $counter);
$imag=ret_image('./ppimages', $imageval);

//$text = testdisplay("Hello");
//echo $text;
echo $counter;
echo $imageval;
$files = glob('./ppimages/*.*');

for ($i=0;$i<count($files);$i++){
	echo $files[$i];}
?>

<img src="<?php echo $imag; ?>" alt="" / >
