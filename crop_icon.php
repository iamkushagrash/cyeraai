<?php
$srcPath = __DIR__ . '/public/icon.png';
$dstPath = __DIR__ . '/public/images/cai-token-coin.png';

if (!file_exists($srcPath)) {
    echo "Source not found\n";
    exit(1);
}

$src = imagecreatefrompng($srcPath);
$width = imagesx($src);
$height = imagesy($src);

// The golden circle in icon.png is located at top center.
// Let's crop the circular medallion only (x: 230 to 770, y: 70 to 610 in a 1000x1000 image).
$cropX = (int)($width * 0.242);
$cropY = (int)($height * 0.076);
$cropW = (int)($width * 0.516);
$cropH = $cropW; // square

$cropped = imagecrop($src, ['x' => $cropX, 'y' => $cropY, 'width' => $cropW, 'height' => $cropH]);

if ($cropped !== false) {
    imagealphablending($cropped, false);
    imagesavealpha($cropped, true);
    imagepng($cropped, $dstPath);
    imagedestroy($cropped);
    echo "Cropped successfully to: " . $dstPath . "\n";
} else {
    echo "Crop failed\n";
}
imagedestroy($src);
