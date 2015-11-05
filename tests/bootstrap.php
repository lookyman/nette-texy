<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('TEMP_DIR', __DIR__ . '/tmp');
if (!@mkdir(TEMP_DIR) && !is_dir(TEMP_DIR)) {
	throw new \RuntimeException('Cannot create temp directory');
}

foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(TEMP_DIR, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $entry) {
	$entry->isDir() ? rmdir($entry) : unlink($entry);
}
