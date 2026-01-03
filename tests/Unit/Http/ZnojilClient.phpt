<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Unit\Http;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class ZnojilClientTest extends \Tester\TestCase{

	protected function tearDown(): void{
		parent::tearDown();
		\Mockery::close();
	}

	public function testSend(): void{
		$znojilClientClient = \Mockery::mock(\Znojil\Http\Client::class);
		$response = \Mockery::mock(\Psr\Http\Message\ResponseInterface::class);

		$method = 'POST';
		$uri = new \Znojil\Http\Message\Uri('https://api.heureka.cz/test');
		$headers = ['Custom-headeR' => 'Foo bar'];
		$data = ['foo' => 'bar'];
		$options = [CURLOPT_TIMEOUT => 6];

		$znojilClientClient->shouldReceive('sendRequest')
			->once()
			->with(
				\Mockery::on(function (\Psr\Http\Message\RequestInterface $request) use ($method, $uri, $headers, $data): bool{
					return $request->getMethod() === $method
						&& (string) $request->getUri() === (string) $uri
						&& str_contains($request->getHeaderLine('custom-header'), $headers['Custom-headeR'])
						&& (string) $request->getBody() === http_build_query($data);
				}),
				$options
			)
			->andReturn($response);

		Assert::same(
			$response,
			(new \Znojil\Heureka\Http\ZnojilClient($znojilClientClient))
				->send($method, $uri, $headers, $data, $options)
		);
	}

}

(new ZnojilClientTest)->run();
