<?php
declare(strict_types=1);

namespace Znojil\Heureka\Internal;

use Znojil\Heureka\Exception\XmlParseException;

final class XmlExtractor{

	public static function getOptionalBool(\SimpleXMLElement $element, string $property): ?bool{
		return ($value = self::getOptionalProperty($element, $property)) !== null ? filter_var($value, FILTER_VALIDATE_BOOLEAN) : null;
	}

	public static function getOptionalFloat(\SimpleXMLElement $element, string $property): ?float{
		return ($value = self::getOptionalProperty($element, $property)) !== null ? (float) $value : null;
	}

	public static function getOptionalInt(\SimpleXMLElement $element, string $property): ?int{
		return ($value = self::getOptionalProperty($element, $property)) !== null ? (int) $value : null;
	}

	public static function getOptionalString(\SimpleXMLElement $element, string $property): ?string{
		return ($value = self::getOptionalProperty($element, $property)) !== null ? $value : null;
	}

	public static function getRequiredBool(\SimpleXMLElement $element, string $property): bool{
		return filter_var(self::getRequiredProperty($element, $property), FILTER_VALIDATE_BOOLEAN);
	}

	public static function getRequiredFloat(\SimpleXMLElement $element, string $property): float{
		return (float) self::getRequiredProperty($element, $property);
	}

	public static function getRequiredInt(\SimpleXMLElement $element, string $property): int{
		return (int) self::getRequiredProperty($element, $property);
	}

	public static function getRequiredString(\SimpleXMLElement $element, string $property): string{
		return (string) self::getRequiredProperty($element, $property);
	}

	private static function getOptionalProperty(\SimpleXMLElement $element, string $property): ?string{
		if(!isset($element->{$property})){
			return null;
		}

		/** @var \SimpleXMLElement $node */
		$node = $element->{$property};
		$value = (string) $node;

		return $value === '' ? null : $value;
	}

	private static function getRequiredProperty(\SimpleXMLElement $element, string $property): string{
		if(!isset($element->{$property})){
			throw new XmlParseException("Missing mandatory element '<$property>' in XML.");
		}

		/** @var \SimpleXMLElement $node */
		$node = $element->{$property};

		return (string) $node;
	}

}
