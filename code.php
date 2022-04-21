<?php 


echo "<p>".password_hash("epfc",PASSWORD_DEFAULT)."</p>";
$hash = password_hash("epfc",PASSWORD_DEFAULT);
echo "<p>".$hash."</p>";

echo password_verify("epfc",$hash);



?>