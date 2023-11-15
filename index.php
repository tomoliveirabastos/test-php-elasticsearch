<?php

require "vendor/autoload.php";

use Pecee\SimpleRouter\SimpleRouter;
use App\ElasticService;
use App\WordService;
use Smalot\PdfParser\Parser;

SimpleRouter::post('/findByText', function () {
    $json = json_decode(file_get_contents("php://input"), true);
    $elasticService = new ElasticService();
    
    $j = $elasticService->getSearch($json['texto']);

    header("Content-Type: application/json");
    header("Accept: application/json");

    return $j;
});


SimpleRouter::get('/findId/{textId}', function($testId) {
    $elasticService = new ElasticService();

    $res = $elasticService->getById($testId);
    header("Content-Type: application/json");
    header("Accept: application/json");
    return $res;
});

SimpleRouter::post('/upload', function () {
    if (array_key_exists('type', $_POST) && $_POST['type'] === 'newIndex') {
        $pathinfo = pathinfo($_FILES['file']['name']);

        $content = "";

        if ($pathinfo['extension'] === 'docx') {
            $content = WordService::read_docx($_FILES['file']['tmp_name']);
        } else if ($pathinfo['extension'] === 'doc') {

            $content = WordService::read_doc($_FILES['file']['tmp_name']);
        } else if ($pathinfo['extension'] === 'pdf') {

            $parser = new Parser();
            $pdf = $parser->parseFile($_FILES['file']['tmp_name']);
            $content = $pdf->getText();
        }

        $elasticService = new ElasticService();

        $app = $elasticService->newIndex($content);

        return json_encode($app->asArray());
    }
});

SimpleRouter::get('/', function () {
    include("app.html");
});

SimpleRouter::start();
