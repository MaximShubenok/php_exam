<?php
$database = mysqli_connect("localhost", "root", "root", "bdquiz");
function super_hash($hesh) {
    for ($i = 0; $i < 10000; $i++) {
        if ($i == 634) {
            $hesh = $hesh . 'j\vn%ew/$3f/43y*/.42gsd';
        }
        if ($i == 2569) {
            $hesh = 'vsd&sp34/8*/@ccp$,kcsa.//' . $hesh;
        }
        if ($i % 2 == 0) {
            $hesh = hash('sha512', $hesh);
        } else if ($i % 3 == 0) {
            $hesh = hash('sha256', $hesh);
        } else {
            $hesh = hash('md5', $hesh);
        }
    }
    return $hesh;
}