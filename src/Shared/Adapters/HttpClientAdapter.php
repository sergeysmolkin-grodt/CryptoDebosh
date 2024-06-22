<?php

declare(strict_types=1);

namespace App\Shared\Adapters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class HttpClientAdapter
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * HttpClientAdapter constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Perform a GET request.
     *
     * @param string $url
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function get(string $url, array $headers = []): ResponseInterface
    {
        return $this->client->request('GET', $url, [
            'headers' => $headers,
        ]);
    }

    /**
     * Perform a POST request.
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function post(string $url, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->client->request('POST', $url, [
            'headers' => $headers,
            'json' => $data,
        ]);
    }

    /**
     * Perform a PUT request.
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function put(string $url, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->client->request('PUT', $url, [
            'headers' => $headers,
            'json' => $data,
        ]);
    }

    /**
     * Perform a DELETE request.
     *
     * @param string $url
     * @param array $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function delete(string $url, array $headers = []): ResponseInterface
    {
        return $this->client->request('DELETE', $url, [
            'headers' => $headers,
        ]);
    }
}