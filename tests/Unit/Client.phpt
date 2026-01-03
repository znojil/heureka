<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Unit;

use Psr\Http\Message\UriInterface;
use Tester\Assert;
use Znojil\Heureka\Enum\Region;
use Znojil\Heureka;
use Znojil\Http\Message\Response;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class ClientTest extends \Tester\TestCase{

	protected function tearDown(): void{
		parent::tearDown();
		\Mockery::close();
	}

	public function testFeedSend(): void{
		$httpClient = \Mockery::mock(Heureka\Http\Client::class);
		$responseBody = '<?xml version="1.0" encoding="UTF-8"?><reviews></reviews>';

		$httpClient->shouldReceive('send')
			->once()
			->with(
				'GET',
				\Mockery::on(function (UriInterface $uri): bool{
					$uriString = (string) $uri;

					return str_starts_with($uriString, Region::Cz->baseUrl())
						&& str_contains($uriString, 'export-review.php')
						&& str_contains($uriString, 'key=custom-api-key');
				}),
				\Mockery::any(),
				\Mockery::any(),
				\Mockery::any()
			)
			->andReturn(new Response(200, body: $responseBody));

		Assert::same(
			[],
			(new Heureka\Client(Region::Cz, 'custom-api-key', $httpClient))
				->send(new Heureka\Feed\Request\GetShopReviewsRequest)
		);
	}

	public function testShopCertificationSend(): void{
		$httpClient = \Mockery::mock(Heureka\Http\Client::class);
		$responseBody = '{"code": 200, "message": "OK"}';

		$httpClient->shouldReceive('send')
			->once()
			->with(
				'POST',
				\Mockery::on(function (UriInterface $uri): bool{
					$uriString = (string) $uri;

					return str_starts_with($uriString, Region::Sk->apiUrl())
						&& str_contains($uriString, '/order/log');
				}),
				\Mockery::any(),
				\Mockery::on(function (array $data): bool{
					return array_key_exists('apiKey', $data) && $data['apiKey'] == 'my-api-key';
				}),
				\Mockery::any()
			)
			->andReturn(new Response(200, body: $responseBody));

		$expectedResponse = (new Heureka\ShopCertification\OrderLogResponse($responseBody));
		$response = (new Heureka\Client(Region::Sk, 'my-api-key', $httpClient))
			->send(new Heureka\ShopCertification\OrderLogRequest(new Heureka\ShopCertification\LogOrderDTO('mail@example.com')));

		Assert::same($expectedResponse->code, $response->code);
		Assert::same($expectedResponse->message, $response->message);
	}

	public function testSendThrowsExceptionOnUnauthorized(): void{
		$httpClient = \Mockery::mock(Heureka\Http\Client::class);

		$httpClient->shouldReceive('send')
			->once()
			->andReturn(new Response(401, body: 'Invalid API Key'));

		Assert::exception(
			function() use ($httpClient): void{
				(new Heureka\Client(Region::Cz, 'bad-key', $httpClient))
					->send(new Heureka\Feed\Request\GetShopReviewsRequest);
			},
			Heureka\Exception\ClientException::class,
			"Request failed. Result:\nInvalid API Key"
		);
	}

}

(new ClientTest)->run();
