<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Cases\Internal;

use Tester\Assert;
use Znojil\Heureka\Internal\XmlExtractor;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class XmlExtractorTest extends \Tester\TestCase{

	private function getXml(): \SimpleXMLElement{
		return new \SimpleXMLElement('
			<root>
				<id>123</id>
				<name>Test Product</name>
				<price>99.9</price>
				<is_active>1</is_active>
				<empty_tag></empty_tag>
				<nested>
					<val>inner</val>
				</nested>
			</root>
		');
	}

	public function testOptionalMethods(): void{
		$xml = $this->getXml();

		// existing
		Assert::true(XmlExtractor::getOptionalBool($xml, 'is_active'));
		Assert::same(99.9, XmlExtractor::getOptionalFloat($xml, 'price'));
		Assert::same(123, XmlExtractor::getOptionalInt($xml, 'id'));
		Assert::same('Test Product', XmlExtractor::getOptionalString($xml, 'name'));
		// empty
		Assert::null(XmlExtractor::getOptionalString($xml, 'empty_tag'));
		// nested
		Assert::same('inner', XmlExtractor::getRequiredString($xml->nested, 'val'));

		// non-existent
		Assert::null(XmlExtractor::getOptionalBool($xml, 'missing'));
		Assert::null(XmlExtractor::getOptionalFloat($xml, 'missing'));
		Assert::null(XmlExtractor::getOptionalInt($xml, 'missing'));
		Assert::null(XmlExtractor::getOptionalString($xml, 'missing'));
	}

	public function testRequiredMethods(): void{
		$xml = $this->getXml();

		Assert::true(XmlExtractor::getRequiredBool($xml, 'is_active'));
		Assert::same(99.9, XmlExtractor::getRequiredFloat($xml, 'price'));
		Assert::same(123, XmlExtractor::getRequiredInt($xml, 'id'));
		Assert::same('Test Product', XmlExtractor::getRequiredString($xml, 'name'));

		Assert::exception(function() use ($xml) {
			XmlExtractor::getRequiredString($xml, 'non_existent');
		}, \Znojil\Heureka\Exception\XmlParseException::class, "Missing mandatory element '<non_existent>' in XML.");
	}

}

(new XmlExtractorTest)->run();
