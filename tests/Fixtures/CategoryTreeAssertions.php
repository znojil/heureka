<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Fixtures;

use Tester\Assert;

trait CategoryTreeAssertions{

	private function getCategoryTreeRawXml(): string{
		$filename = __DIR__ . '/data/sekce.xml';

		$xmlString = @file_get_contents($filename);
		if($xmlString === false){
			throw new \LogicException("Unable to read fixture file '$filename'.");
		}

		return $xmlString;
	}

	private function getCategoryTreeXmlElement(): \SimpleXMLElement{
		$xml = @simplexml_load_string($this->getCategoryTreeRawXml(), \SimpleXMLElement::class, LIBXML_NOCDATA);
		if($xml === false){
			throw new \LogicException("Failed to parse XML for class '" . static::class . "'.");
		}

		return $xml;
	}

	private function assertCategoryTreeCollection(\Znojil\Heureka\Feed\DTO\CategoryTreeDTOCollection $categoryTrees): void{
		Assert::count(3, $categoryTrees);

		$items = $categoryTrees->getItems();

		$categoryTree = $items[971];
		Assert::same('Auto-moto', $categoryTree->name);
		Assert::count(1, $categoryTree->children);

		$categoryTree2 = $items[2974];
		Assert::same('Auto-moto | Náplně a kapaliny', $categoryTree2->name);
		Assert::count(2, $categoryTree2->children);

		$categoryTree3 = $items[995];
		Assert::same('Bydlení a doplňky', $categoryTree3->name);
		Assert::count(1, $categoryTree3->children);

		$categoryTree3Sub = $categoryTree3->children[1562];
		Assert::same('Koberce a koberečky', $categoryTree3Sub->name);
		Assert::same('Heureka.cz | Bydlení a doplňky | Koberce a koberečky', $categoryTree3Sub->fullName);
	}

}
