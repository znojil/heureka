<?php
declare(strict_types=1);

namespace Znojil\Heureka;

final class Client{

	private readonly Http\Client $httpClient;

	public function __construct(
		private readonly Enum\Region $region,
		private readonly ?string $key = null,
		?Http\Client $httpClient = null
	){
		$this->httpClient = $httpClient ?? new Http\ZnojilClient;
	}

	/**
	 * @template TResponse
	 * @param Http\Request<TResponse> $request
	 * @return TResponse
	 * @throws Exception\LogicException
	 * @throws Exception\ServerException
	 * @throws Exception\ClientException
	 * @throws Exception\ResponseException if request not successful
	 */
	public function send(Http\Request $request): mixed{
		$uri = (new \Znojil\Http\Message\Uri(
			rtrim($request->onApi() ? $this->region->apiUrl() : $this->region->baseUrl(), '/') .
			'/' . ltrim($request->getUrn(), '/')
		));

		$data = $request->getData();

		if($request->requiresAuth()){
			if($this->key === null){
				throw new Exception\LogicException("'" . $request::class . "' requires an API Key.");
			}

			if($request->onApi()){
				$data['apiKey'] = $this->key;
			}else{
				$query = $uri->getQuery();
				$separator = $query === '' ? '' : '&';
				$uri = $uri->withQuery($query . $separator . 'key=' . $this->key);
			}
		}

		$response = $this->httpClient->send($request->getMethod(), $uri, $request->getHeaders(), $data, $request->getHttpClientOptions());

		$statusCode = $response->getStatusCode();
		if($statusCode < 200 || $statusCode >= 300){
			$message = "Request failed. Result:\n" . (string) $response->getBody();

			throw match(true){
				$statusCode >= 500 => new Exception\ServerException($message, $statusCode),
				$statusCode >= 400 => new Exception\ClientException($message, $statusCode),
				default => new Exception\ResponseException($message, $statusCode)
			};
		}

		return $request->createResponse($response);
	}

}
