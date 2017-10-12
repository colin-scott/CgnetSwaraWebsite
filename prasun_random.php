<?php

include 'loudblog/inc/loudblogtags.php';
$counter = random_id('./ppimages');
$counter = $counter-1;
$value = rand(0,$counter);

header('location: prasun_test.php?id='. $value);

?>