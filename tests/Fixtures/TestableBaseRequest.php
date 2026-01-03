<?php
declare(strict_types=1);

namespace Znojil\Heureka\Tests\Fixtures;

use Psr\Http\Message;
use Znojil\Heureka\Feed\Request\BaseRequest;

/**
 * @extends BaseRequest<null>
 */
final class TestableBaseRequest extends BaseRequest{

	public function requiresAuth(): bool{
		return false;
	}

	public function getUrn(): string{
		return 'test';
	}

	public function createResponse(Message\ResponseInterface $response): null{
		return null;
	}

	public function getXmlFromResponseBody(Message\StreamInterface $stream): \SimpleXMLElement{
		return parent::getXmlFromResponseBody($stream);
	}

	public function createDatetimeFromUnixTimestamp(string $timestamp): \DateTimeImmutable{
		return parent::createDatetimeFromUnixTimestamp($timestamp);
	}
}
