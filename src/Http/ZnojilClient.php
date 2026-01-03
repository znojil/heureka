<?php
declare(strict_types=1);

namespace Znojil\Heureka\Http;

use Psr\Http\Message;
use Znojil\Http;

final class ZnojilClient implements Client{

	private readonly Http\Client $client;

	public function __construct(
		?Http\Client $client = null
	){
		$this->client = $client ?? new Http\Client;
	}

	public function send(string $method, Message\UriInterface $uri, array $headers = [], mixed $data = null, array $options = []): Message\ResponseInterface{
		return $this->client->sendRequest(
			(new Http\RequestFactory)->createRequest($method, $uri, $headers, $data),
			$options
		);
	}

}
