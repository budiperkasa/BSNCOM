<?php

function genRandomString() {
    $length = 10;
    $characters = 'abcdefghijklmnopqrstuvwxyz_';
    $string = '';    

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}
?>