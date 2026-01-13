<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Cases\Feed\Request;

use Tester\Assert;
use Znojil\Heureka\Feed\Request\GetCategoryTreeRequest;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class GetCategoryTreeRequestTest extends \Tester\TestCase{

	use \Znojil\Heureka\Tests\Fixtures\CategoryTreeAssertions;

	public function testConfiguration(): void{
		$request = new GetCategoryTreeRequest;

		Assert::false($request->onApi());
		Assert::false($request->requiresAuth());
		Assert::same('GET', $request->getMethod());
		Assert::same('direct/xml-export/shops/heureka-sekce.xml', $request->getUrn());
		Assert::same([], $request->getHeaders());
		Assert::same([], $request->getData());
		Assert::same([], $request->getHttpClientOptions());
	}

	public function testCreateResponse(): void{
		$this->assertCategoryTreeCollection((new GetCategoryTreeRequest)->createResponse(
			new \Znojil\Http\Message\Response(200, body: $this->getCategoryTreeRawXml())
		));
	}

}

(new GetCategoryTreeRequestTest)->run();
