<?php
declare(strict_types=1);

namespace Znojil\Heureka\Http;

/**
 * @template-covariant T the type of response object returned by createResponse()
 */
interface Request{

	/**
	 * Should this request use API subdomain (api.heureka.*) instead of base URL?
	 * @return bool true for api.heureka.*, false for www.heureka.*
	 */
	function onApi(): bool;

	/**
	 * @return bool true if Client must have API key configured
	 */
	function requiresAuth(): bool;

	function getMethod(): string;

	function getUrn(): string;

	/**
	 * @return array<string, string|string[]>
	 */
	function getHeaders(): array;

	/**
	 * @return array<mixed>
	 */
	function getData(): array;

	/**
	 * cURL options for this request.
	 * @return array<int, mixed>
	 */
	function getHttpClientOptions(): array;

	/**
	 * @return T
	 */
	function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): mixed;

}
