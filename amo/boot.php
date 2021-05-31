<?php

use AmoCRM\Client\AmoCRMApiClient;
use Symfony\Component\Dotenv\Dotenv;

include_once __DIR__ . '/vendor/autoload.php';

// $dotenv = new Dotenv();
// $dotenv->load(__DIR__ . '/.env.dist', __DIR__ . '/.env');

$clientId = "f16b90ce-1d19-41ac-88c1-7d01e8637409";
$clientSecret = "pMzL8wnGDlRXNrEPkvQzkNRj2NwjdvxMO0miLcuKyQgqBpRki7z7UBQL0ixgNK7T";
$redirectUri = "http://тм-мастер.рф/amo/get_token.php";

$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

include_once __DIR__ . '/token_actions.php';
include_once __DIR__ . '/error_printer.php';
