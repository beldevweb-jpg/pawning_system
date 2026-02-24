<?php
$image = imagecreatetruecolor(300, 300);

if ($image === false) {
    echo "GD FAIL";
} else {
    echo "GD OK";
}
