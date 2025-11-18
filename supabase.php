<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$SUPABASE_URL = $_ENV['NEXT_PUBLIC_SUPABASE_URL'];
$SUPABASE_KEY = $_ENV['NEXT_PUBLIC_SUPABASE_ANON_KEY'];
$SUPABASE_SERVICE_KEY = $_ENV['SUPABASE_SERVICE_ROLE_KEY'];

$supabase = new \GuzzleHttp\Client([
    'base_uri' => $SUPABASE_URL . '/rest/v1/',
    'headers' => [
        'apikey' => $SUPABASE_SERVICE_KEY,
        'Authorization' => 'Bearer ' . $SUPABASE_SERVICE_KEY,
        'Content-Type' => 'application/json'
    ]
]);
