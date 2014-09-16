<?php
$checksum = crc32("The quick brown fox jumped over the lazy dog.");
printf("%u\n", $checksum);

$checksum = crc32("php");
printf("%u\n", $checksum);

$checksum = crc32("php");
printf("%u\n", $checksum);

$checksum = crc32("C");
printf("%u\n", $checksum);