<?php

include __DIR__ . "/../vendor/autoload.php";

function delTree($dir)
{
    if (!file_exists($dir)) {
        return;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

delTree('data/cache');