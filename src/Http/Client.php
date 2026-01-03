<?php
declare(strict_types=1);

namespace Znojil\Heureka\Http;

use Psr\Http\Message;

interface Client{

	/**
	 * @param array<string, string|string[]> $headers
	 * @param array<int, mixed> $options options for cURL wrapper (CURLOPT_*)
	 */
	function send(string $method, Message\UriInterface $uri, array $headers = [], mixed $data = null, array $options = []): Message\ResponseInterface;

}
