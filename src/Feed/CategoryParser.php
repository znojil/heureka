<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed;

final class CategoryParser{

	public static function parse(\SimpleXMLElement $xml): DTO\CategoryTreeDTOCollection{
		$categoryTreePaths = [];
		$categories = [];

		$fn = function(\SimpleXMLElement $category, ?int $parentId = null, ?string $parentPath = null) use (&$fn, &$categoryTreePaths, &$categories): void{
			$categoryId = (int) $category->CATEGORY_ID;
			$categoryName = (string) $category->CATEGORY_NAME;
			$categoryFullName = (string) $category->CATEGORY_FULLNAME;

			$categoryPath = $parentPath !== null ? $parentPath . ' | ' . $categoryName : $categoryName;

			if($categoryFullName === ''){ // tree
				$categoryTreePaths[$categoryId] = $categoryPath;
			}elseif($parentId !== null){ // category
				$categories[$parentId][$categoryId] = new DTO\CategoryDTO(
					$categoryId,
					$categoryName,
					$categoryFullName
				);
			}

			foreach($category->CATEGORY ?? [] as $subCategory){
				$fn($subCategory, $categoryId, $categoryPath);
			}
		};

		foreach($xml->CATEGORY ?? [] as $v){
			$fn($v);
		}

		$categoryTrees = [];
		foreach($categoryTreePaths as $k => $v){
			if(isset($categories[$k])){
				$categoryTrees[$k] = new DTO\CategoryTreeDTO($k, $v, $categories[$k]);
			}
		}

		return new DTO\CategoryTreeDTOCollection($categoryTrees);
	}

}
