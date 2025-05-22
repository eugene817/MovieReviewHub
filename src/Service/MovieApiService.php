<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use RuntimeException;

class MovieApiService
{
  public function __construct(
    private HttpClientInterface $client,
    private string $omdbApiKey
  ) {}

  public function fetchById(string $apiId): array
  {
    $response = $this->client->request('GET', 'https://www.omdbapi.com/', [
      'query' => ['apikey' => $this->omdbApiKey, 'i' => $apiId],
    ]);

    $data = $response->toArray();

    if (($data['Response'] ?? 'False') === 'False') {
      throw new RuntimeException($data['Error'] ?? 'Film not found');
    }

    return $data;
  }
}
