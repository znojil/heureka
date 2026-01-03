# Znojil Heureka

[![Latest Stable Version](https://poser.pugx.org/znojil/heureka/v/stable)](https://github.com/znojil/heureka/releases)
[![PHP Version Require](https://poser.pugx.org/znojil/heureka/require/php)](https://packagist.org/packages/znojil/heureka)
[![License](https://poser.pugx.org/znojil/heureka/license)](LICENSE)
[![Tests](https://github.com/znojil/heureka/actions/workflows/tests.yml/badge.svg)](https://github.com/znojil/heureka/actions)

A simple and modern PHP library for communicating with the Heureka API.

The library covers services for fetching reviews, the category tree, and the `Verified by Customers` (Ověřeno zákazníky) service.

## 🌐 Supported Regions

This library supports the following regions:
- Heureka.cz
- Heureka.sk

## 🔑 Service Activation

To use the `Verified by Customers` (Ověřeno zákazníky) service, you must first [activate it here](https://sluzby.heureka.cz/napoveda/overeno-jak-aktivovat/).

## 🚀 Installation

Install the library using Composer:

```bash
composer require znojil/heureka
```

## 📖 Usage

### 1. Client Initialization

First, you need to create a client instance. The client requires a region (`.cz` or `.sk`) and an [API key](https://sluzby.heureka.cz/sluzby/certifikat-spokojenosti/) for services that need authentication.

```php
use Znojil\Heureka\Client;
use Znojil\Heureka\Enum\Region;

// For Heureka.cz
$clientCz = new Client(Region::Cz, 'YOUR_API_KEY_FOR_CZ');

// For Heureka.sk
$clientSk = new Client(Region::Sk, 'YOUR_API_KEY_FOR_SK');
```

### 2. Using a Custom HTTP Client

You can inject your own HTTP client implementation by passing it as the third argument to the `Client` constructor. This is useful for testing or for integrating with your application's existing HTTP layer (e.g., Guzzle, Symfony HTTP Client).

Your client must implement the `Znojil\Heureka\Http\Client` interface.

```php
use Znojil\Heureka\Client;
use Znojil\Heureka\Enum\Region;
use Znojil\Heureka\Http\Client as HeurekaHttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

// A custom HTTP client implementation example
class MyCustomHttpClient implements HeurekaHttpClient{
	public function send(string $method, UriInterface|string $uri, array $headers = [], mixed $data = null, array $options = []): ResponseInterface{
		// Your logic to send the request...
		// For example, using Guzzle:
		// $guzzleClient = new \GuzzleHttp\Client;
		// return $guzzleClient->request($method, $uri, [...]);
	}
}

$customHttpClient = new MyCustomHttpClient;
$client = new Client(Region::Cz, 'YOUR_API_KEY', $customHttpClient);
```

### 3. Fetching the Category Tree

This service does not require an API key.

```php
use Znojil\Heureka\Feed\Request\GetCategoryTreeRequest;

$request = new GetCategoryTreeRequest;
$categoryTreeCollection = $clientCz->send($request);

foreach($categoryTreeCollection as $tree){
	$tree; // CategoryTreeDTO
}
```

### 4. Fetching Shop Reviews

This service requires an API key. The response is an array of `ShopReviewDTO` objects.

```php
use Znojil\Heureka\Feed\Request\GetShopReviewsRequest;

$request = new GetShopReviewsRequest;
$reviews = $clientCz->send($request);

foreach($reviews as $review){
	$review; // ShopReviewDTO
}
```

### 5. Fetching Product Reviews

This service requires an API key. You can optionally set a date from which to fetch the reviews. The response is an array of `ProductReviewDTO` objects.

```php
use Znojil\Heureka\Feed\Request\GetProductReviewsRequest;

// Without a date filter
$requestAll = new GetProductReviewsRequest;
$allReviews = $clientCz->send($requestAll);

// Only reviews since yesterday
$yesterday = new \DateTimeImmutable('yesterday');
$requestSince = new GetProductReviewsRequest($yesterday);
$recentReviews = $clientCz->send($requestSince);
```

### 6. Sending an Order (Verified by Customers)

This service sends order information to the `Verified by Customers` (Ověřeno zákazníky) system. It requires an API key and uses the new v2 API.

```php
use Znojil\Heureka\ShopCertification\LogOrderDTO;
use Znojil\Heureka\ShopCertification\OrderLogRequest;

// 1. Create a DTO with the order data
$orderDto = new LogOrderDTO(
	email: 'customer@email.com',
	orderId: 'ORD2024001',
	productItemIds: ['AB-123', 'CD-456']
);

// 2. Create the request and send it
$request = new OrderLogRequest($orderDto);
$response = $clientCz->send($request);

if($response->isSuccessful()){
	echo "Order was successfully logged.";
}
```

> For more details, see the official [Heureka 'Verified by Customers' documentation](https://github.com/heureka/overeno-zakazniky/blob/30eb6f7ab47ee3068f71efbdbde4a0d4b019a0a2/docs/api-documentation-en.md).

## ⚠️ Error Handling

The client throws exceptions on failed HTTP requests to help you identify the issue:

- `Znojil\Heureka\Exception\ClientException`: For client-side errors (HTTP 4xx).
- `Znojil\Heureka\Exception\ServerException`: For server-side errors (HTTP 5xx).
- `Znojil\Heureka\Exception\ResponseException`: For other HTTP error codes.
- `Znojil\Heureka\Exception\LogicException`: If you try to call a service that requires an API key without one being set.

## 📄 License
This library is open-source software licensed under the [MIT license](https://choosealicense.com/licenses/mit/).
