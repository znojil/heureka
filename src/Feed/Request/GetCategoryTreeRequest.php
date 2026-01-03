<?php
declare(strict_types=1);

namespace Znojil\Heureka\Feed\Request;

use Znojil\Heureka\Feed;

/**
 * @extends BaseRequest<Feed\DTO\CategoryTreeDTOCollection>
 */
final class GetCategoryTreeRequest extends BaseRequest{

	public function requiresAuth(): bool{
		return false;
	}

	public function getUrn(): string{
		return 'direct/xml-export/shops/heureka-sekce.xml';
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $response): Feed\DTO\CategoryTreeDTOCollection{
		return Feed\CategoryParser::parse($this->getXmlFromResponseBody($response->getBody()));
	}

}
