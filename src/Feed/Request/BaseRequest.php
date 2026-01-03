<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed\Request;

use Znojil\Heureka\Exception\XmlParseException;
use Znojil\Heureka\Http\Request;

/**
 * @template T
 * @implements Request<T>
 */
abstract class BaseRequest implements Request{

	public function onApi(): bool{
		return false;
	}

	public function getMethod(): string{
		return 'GET';
	}

	public function getHeaders(): array{
		return [];
	}

	public function getData(): array{
		return [];
	}

	public function getHttpClientOptions(): array{
		return [];
	}

	protected function getXmlFromResponseBody(\Psr\Http\Message\StreamInterface $stream): \SimpleXMLElement{
		$body = (string) $stream;

		$xml = @simplexml_load_string($body, \SimpleXMLElement::class, LIBXML_NOCDATA);
		if($xml === false){
			throw new XmlParseException("Failed to parse XML for URN '" . $this->getUrn() . "'. Body starts with:\n" . mb_substr($body, 0, 100));
		}

		return $xml;
	}

	protected function createDatetimeFromUnixTimestamp(string $timestamp): \DateTimeImmutable{
		$date = \DateTimeImmutable::createFromFormat('U', trim($timestamp));
		if($date === false){
			throw new XmlParseException("Invalid timestamp '$timestamp'.");
		}

		return $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
	}

}
