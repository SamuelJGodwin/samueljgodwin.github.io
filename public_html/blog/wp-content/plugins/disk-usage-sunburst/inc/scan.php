<?php

function rbdusb_humanreadablesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
}

function rbdusb_scan($START_PATH, $path, $level = 0) {

    $ret = array();
    $ret["name"] = basename($path);
    $ret["size"] = 0;

    if (!is_dir($path)) {

        $ret["size"] = filesize($path);

    } else {

        $children = array();

        $files = scandir($path);
        foreach ($files as $file) {
            if ($file == '.') continue;
            if ($file == '..') continue;
            $absfile = $path.'/'.$file;

            $child = rbdusb_scan($START_PATH, $absfile, $level+1);
            $children[] = $child;
        }

        if (count($children) > 0) {
            $ret["children"] = $children;
            $ret["size"] = array_reduce($children, function($carry, $item) { return $carry + $item["size"]; }, 0);
        }
    }
    $ret["name"] .= " ".rbdusb_humanreadablesize($ret["size"]);
    return $ret;
}
