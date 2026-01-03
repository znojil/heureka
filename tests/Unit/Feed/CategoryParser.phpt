<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Unit\Feed;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CategoryParserTest extends \Tester\TestCase{

	use \Znojil\Heureka\Tests\Fixtures\CategoryTreeAssertions;

	public function testParse(): void{
		$this->assertCategoryTreeCollection(
			\Znojil\Heureka\Feed\CategoryParser::parse($this->getCategoryTreeXmlElement())
		);
	}

}

(new CategoryParserTest)->run();
