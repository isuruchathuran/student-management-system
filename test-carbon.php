<?php
require 'vendor/autoload.php';
try {
    echo \Carbon\Carbon::parse('2002-05.20')->format('Y-m-d');
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage();
}
