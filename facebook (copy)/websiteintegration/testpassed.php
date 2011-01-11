<?php 
print_r($_SESSION);print_r($_COOKIE);
print_r($cookie['access_token']);

$user = json_decode(file_get_contents(
    'https://graph.facebook.com/me?access_token=' .
    $cookie['access_token']))->me;

print_r($user);
?>
