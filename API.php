
<?php
$json_file = file_get_contents("https://frosch.cosy.sbg.ac.at/datasets/json/movies.git");
$json_data = json_decode($json_file);
print_r($json_data);
?>