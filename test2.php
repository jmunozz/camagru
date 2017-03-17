<?php

$str = 'bonjour';
$str = base64_encode($str);
echo $str;
$str = base64_decode($str);
echo $str;
?>
