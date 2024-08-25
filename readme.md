# Payment API Demo

[![PHP Version][php-image]][php-url]
[![Composer Version][composer-image]][composer-url]

This project is a demo API that closely resembles the original production API. It is designed to create a safe testing environment, allowing developers to test and experiment without exposing or modifying real data from the database.

## Installation

### Step 1: Clone the Repository

Clone this repository to your local machine and cd to the directory:

```bash
git clone https://github.com/Partha11/payment_api_demo
cd payment_api_demo
```

### Step 2: Install Dependencies

This project uses composer packages. For installing the dependencies, run the following command:

```bash
composer install
```

This will download and set up all necessary packages as defined in composer.json.

### Step 3: Start Server

Run the project using the following command:

```bash
php -S localhost:8080
```

This project does not use database, so no need to setup any database engine.


## Usage example

To get started with using this demo API, please refer to the [Usage Examples](wiki) section in our project's wiki.

### What You'll Find in the Wiki:

- **Detailed Route Examples:** Learn how to interact with each route, including how to make POST and PUT requests.
- **Sample API Requests:** Examples of how to format your API requests using tools like `cURL` or `Postman`.
- **Response Handling:** Guidance on interpreting the API responses, including success and error messages.

_For more examples and usage, please refer to the [Wiki][wiki]._

## Release History

* 1.2.1
    * REMOVE: Removed `/update` endpoint for payments
    * ADD: Added response body for `/invoice` endpoint for `POST` request
* 1.2
    * ADD: Added monolog for logging
    * ADD: Added flysystem for file management
* 1.1
    * Added controller class
    * CHANGE: Updated namespace `TwainArc` to `App`
* 1.0
    * ADD: Added composer for dependency management

## Contributing

1. Fork it (<[Payment API Demo][project-url]>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

<!-- Markdown link & img dfn's -->
[php-image]: https://img.shields.io/badge/v8.2.0-777BB4?style=flat-square&logo=php&logoColor=ffffff&label=php
[php-url]: https://www.php.net/downloads.php
[composer-image]: https://img.shields.io/badge/v2.7.8-885630?style=flat-square&logo=composer&logoColor=ffffff&label=composer
[composer-url]: https://getcomposer.org/download/
[github-forks]: https://img.shields.io/github/forks/Partha11/payment_api_demo?style=flat-square&logo=github
[travis-image]: https://img.shields.io/travis/dbader/node-datadog-metrics/master.svg?style=flat-square
[travis-url]: https://travis-ci.org/dbader/node-datadog-metrics
[wiki]: https://github.com/Partha11/payment_api_demo/wiki
[project-url]: https://github.com/Partha11/payment_api_demo
