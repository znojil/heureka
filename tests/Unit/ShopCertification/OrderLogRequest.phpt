<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Unit\ShopCertification;

use Tester\Assert;
use Znojil\Heureka\ShopCertification;
use Znojil\Http\Message\Response;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class OrderLogRequestTest extends \Tester\TestCase{

	private function getLogOrderDTO(): ShopCertification\LogOrderDTO{
		return new ShopCertification\LogOrderDTO('mail@example.com');
	}

	public function testConfiguration(): void{
		$logOrderDTO = $this->getLogOrderDTO();
		$request = new ShopCertification\OrderLogRequest($logOrderDTO);

		Assert::true($request->onApi());
		Assert::true($request->requiresAuth());
		Assert::same('POST', $request->getMethod());
		Assert::same('shop-certification/v2/order/log', $request->getUrn());
		Assert::same(['Content-Type' => 'application/json; charset=utf-8'], $request->getHeaders());
		Assert::same($logOrderDTO->toArray(), $request->getData());
		Assert::same([CURLOPT_TIMEOUT => 5], $request->getHttpClientOptions());
	}

	/**
	 * @return array<mixed>
	 */
	public function getCreateResponseArgs(): array{
		return [
			[
				'{"code": 200,"message": "ok"}',
				true,
				200,
				'ok',
				null
			],
			[
				'{"code": 400, "message": "bad-request", "description": "There is a problem with your request. Please see the documentation for details."}',
				false,
				400,
				'bad-request',
				'There is a problem with your request. Please see the documentation for details.'
			]
		];
	}

	/**
	 * @dataProvider getCreateResponseArgs
	 */
	public function testCreateResponse(string $body, bool $isSuccessful, int $code, string $message, ?string $description): void{
		$response = new Response(body: $body);
		$request = new ShopCertification\OrderLogRequest($this->getLogOrderDTO());
		$result = $request->createResponse($response);

		Assert::same($isSuccessful, $result->isSuccessful());
		Assert::same($code, $result->code);
		Assert::same($message, $result->message);
		Assert::same($description, $result->description);
	}

	public function testCreateResponseWithInvalidJson(): void{
		$body = '{"code": 200...';

		Assert::exception(function() use ($body): void{
			(new ShopCertification\OrderLogRequest($this->getLogOrderDTO()))
				->createResponse(new Response(body: $body));
		}, \Znojil\Heureka\Exception\JsonException::class, "Unexpected response '$body' returned. JSON error: Syntax error");
	}

}

(new OrderLogRequestTest)->run();
