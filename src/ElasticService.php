<?php

namespace App;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticService
{
    private string $host = 'https://localhost:9200';
    private string $username = 'elastic';
    private string $password = '123Mudar';
    private string $currentIndex = "current_index";

    private function getClient(): Client
    {
        return ClientBuilder::create()
            ->setHosts([$this->host])
            ->setSSLVerification(false)
            ->setBasicAuthentication($this->username, $this->password)
            ->build();
    }

    public function getById(string $id)
    {
        $params = [
            'index' => $this->currentIndex,
            'id'    => $id,
        ];

        return $this->getClient()->get($params);
    }

    public function newIndex(string $newContent)
    {
        $id = uniqid();
        $text = trim($newContent);

        $params = [
            'index' => $this->currentIndex,
            'id'    => $id,
            'body'  => ['testField' => $text]
        ];

        return $this->getClient()->index($params);
    }

    public function getSearch(string $contentText)
    {
        $text = trim($contentText);

        $params = [
            'index' => $this->currentIndex,
            'body'  => [
                'query' => [
                    'match_phrase' => [
                        'testField' => "{$text}"
                    ]
                ]
            ]
        ];

        return $this->getClient()->search($params);
    }
}
