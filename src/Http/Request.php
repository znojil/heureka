<?php
declare(strict_types=1);

namespace Znojil\Heureka\Http;

/**
 * @template-covariant T
 */
interface Request{

	function onApi(): bool;

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
	 * @return array<int, mixed>
	 */
	function getHttpClientOptions(): array;

	/**
	 * @return T
	 */
	function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): mixed;

}
