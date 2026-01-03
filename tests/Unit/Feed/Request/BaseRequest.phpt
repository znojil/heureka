<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Unit\Feed\Request;

use Tester\Assert;
use Znojil\Heureka\Tests\Fixtures\TestableBaseRequest;
use Znojil\Http\Message\Response;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class BaseRequestTest extends \Tester\TestCase{

	public function testGetXmlFromResponse(): void{
		$response = new Response(200, body: '<root><item>1</item></root>');
		$xml = (new TestableBaseRequest)->getXmlFromResponseBody($response->getBody());
		Assert::same('1', (string) $xml->item);

		$request = new TestableBaseRequest;
		$response = new Response(200, body: 'INVALID XML');
		Assert::exception(function() use ($request, $response): void{
			$request->getXmlFromResponseBody($response->getBody());
		}, \Znojil\Heureka\Exception\XmlParseException::class, "Failed to parse XML for URN '" . $request->getUrn() . "'. Body starts with:\nINVALID XML");
	}

	public function testCreateDatetimeFromUnixTimestamp(): void{
		$datetime = (new TestableBaseRequest)->createDatetimeFromUnixTimestamp('1766597400');

		Assert::type(\DateTimeImmutable::class, $datetime);
		Assert::same('2025-12-24T18:30:00+01:00', $datetime->format('c'));
	}

}

(new BaseRequestTest)->run();
