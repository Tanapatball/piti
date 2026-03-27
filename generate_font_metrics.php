<?php
require 'vendor/autoload.php';
use FontLib\Font;

$fontDir = str_replace('\\', '/', realpath('storage/fonts'));

// Generate .ufm for Regular
$font = Font::load($fontDir . '/NotoSansThai-Regular.ttf');
$font->parse();
$font->saveAdobeFontMetrics($fontDir . '/NotoSansThai-Regular.ufm');
$font->close();
echo 'Regular .ufm created: ' . (file_exists($fontDir . '/NotoSansThai-Regular.ufm') ? 'YES' : 'NO') . PHP_EOL;

// Generate .ufm for Bold
$font = Font::load($fontDir . '/NotoSansThai-Bold.ttf');
$font->parse();
$font->saveAdobeFontMetrics($fontDir . '/NotoSansThai-Bold.ufm');
$font->close();
echo 'Bold .ufm created: ' . (file_exists($fontDir . '/NotoSansThai-Bold.ufm') ? 'YES' : 'NO') . PHP_EOL;
