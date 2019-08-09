<?php

namespace App;

use Slim\Factory\AppFactory;
use Tightenco\Collect\Support\Collection;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Generator.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$companies = Generator::generate(100);

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response, $args) {
    return $response->write('open something like (you can change id): /companies/5');
});

$app->get('/companies/{id}', function ($request, $response, array $args) use ($companies) {
    $companiesCollection = Collection::make($companies);
    $company = $companiesCollection->firstWhere('id', $args['id']);

    return $company
        ? $response->write(json_encode($company))
        : $response->write('Page not found')
            ->withStatus(404);
});

$app->run();