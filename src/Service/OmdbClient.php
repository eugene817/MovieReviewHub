<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbClient
{
    public function __construct(
        private HttpClientInterface $client,
        private string $omdbApiKey
    ) {}

    public function search(string $query, int $page = 1): array
    {
        $resp = $this->client->request("GET", "http://www.omdbapi.com/", [
            "query" => [
                "apikey" => $this->omdbApiKey,
                "s" => $query,
                "page" => $page,
            ],
        ]);
        $data = $resp->toArray();
        if (!isset($data["Search"])) {
            return [];
        }
        return $data["Search"];
    }

    public function fetchById(string $imdbId): array
    {
        $resp = $this->client->request("GET", "http://www.omdbapi.com/", [
            "query" => [
                "apikey" => $this->omdbApiKey,
                "i" => $imdbId,
                "plot" => "full",
            ],
        ]);
        $data = $resp->toArray();
        if (!empty($data["Error"])) {
            throw new \RuntimeException("OMDb error: " . $data["Error"]);
        }
        return $data;
    }

    public function fetchByTitle(string $title): array
    {
        $resp = $this->client->request("GET", "http://www.omdbapi.com/", [
            "query" => [
                "apikey" => $this->omdbApiKey,
                "t" => $title,
                "plot" => "full",
            ],
        ]);
        $data = $resp->toArray();
        if (!empty($data["Error"])) {
            throw new \RuntimeException("OMDb error: " . $data["Error"]);
        }
        return $data;
    }
}
