<?php

require(dirname(__FILE__) . '/../vendor/autoload.php');

$config_file = Phar::running() . '/etc/programmatic.ini';
$config = parse_ini_string(file_get_contents($config_file));

if (false == $config) {
    exit(1);
}

$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://api.parsely.com/v2/',
]);

$response = $client->request('GET', 'realtime/posts', [
    'query' => [
        'apikey' => $config['apikey'],
        'secret' => $config['secret'],
        'time' => '24h',
        'tag' => 'Ikke paywall',
    ],
]);

$code = $response->getStatusCode();

if (200 != $code) {
    exit(1);
}

$body = $response->getBody();

$results = json_decode($body);

$top_three = [];
if (!empty($results->data)) {
    $count = 0;
    foreach ($results->data as $article) {
        if ($count > 2) {
            break;
        }
        if (strlen(utf8_decode($article->title)) > 20) {
            $top_three[] = [
                'title' => $article->title,
                'url' => $article->url,
            ];
        }
        $count++;
    }
}

print json_encode($top_three);
