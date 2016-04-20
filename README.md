# Common

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Some boilerplate for most of the Multimedia Street projects.

## Table of Contents

- [Included Packages](#included-packages)
- [Install](#install)
 - [Via Composer](#via-composer)
 - [Add to Service Provider](#add-to-service-provider)
 - [Disabling CSRF protection for your API](#disabling-csrf-protection-for-your-api)
- [Post Install](#post-install)
 - [Extend Exception Handler](#extend-exception-handler)
 - [Response Trait](#response-trait)
- [Change Log](#change-log)
- [Testing](#testing)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)


## Included Packages


- [Image](https://github.com/Intervention/image) - PHP Image Manipulation
- [Image (Cache)](https://github.com/Intervention/imagecache) - Caching extension for the Intervention Image Class
- [iSeed](https://github.com/orangehill/iseed) - Inverse seed generator
- [Whoops](https://github.com/filp/whoops) - PHP errors for cool kids
- [Clockwork](https://github.com/itsgoingd/clockwork) - Chrome extension for PHP development
- [DOMPDF](https://github.com/barryvdh/laravel-dompdf) - DOMPDF Wrapper for Laravel 5
- [Excel](https://github.com/Maatwebsite/Laravel-Excel) - Laravel Excel v2.1.* for Laravel 5
- [CORS](https://github.com/barryvdh/laravel-cors) - CORS in Laravel 5


## Install

#### Via Composer
Require the `multimedia-street/common` package in your composer.json and update your dependencies.

``` bash
$ composer require multimedia-street/common
```

#### Add Service Provider
Include the Service Provider to your `config/app.php` in providers array

``` php
Mmstreet\Common\ServiceProvider::class,
```

#### Add Package Facades
Include Facades to your `config/app.php` in aliases array

``` php
'Excel' => Maatwebsite\Excel\Facades\Excel::class,
'PDF' => Barryvdh\DomPDF\Facade::class,
'Image' => Intervention\Image\Facades\Image::class,
```


#### Disabling CSRF protection for your API
To use the CORS properly, [as stated in the documentation](https://github.com/barryvdh/laravel-cors#disabling-csrf-protection-for-your-api), in `App\Http\Middleware\VerifyCsrfToken`, add your routes to the exceptions:

``` php
protected $except = [
  'api/*'
];
```


## Post Install

#### Extend Exception Handler
You can use the Exception handler specially for developing. This includes the [Whoops](https://github.com/filp/whoops). You can extend your `app/Exceptions/Handler.php` with `Mmstreet\Common\Exceptions\Handler`. Also add your uris using the `$corsUris` property to be used in [CORS](https://github.com/barryvdh/laravel-cors). See example below.

``` php
namespace App\Exceptions;

use Mmstreet\Common\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $corsUris = [
        'api/*', // default
        'auth/*',
        'logout'
    ];
}
```


#### Response Trait
You can use the `Mmstreet\Common\Traits\ResponseTrait` to your `App\Http\Controllers\Controller` for easy returning response either Json or in View. See example below.

``` php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mmstreet\Common\Traits\ResponseTrait;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;
}
```

Example usage:

``` php
namespace App\Http\Controllers;

use App\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        if ($posts->isEmpty()) {
            // {The message}, {The data}, {status code}, {view name}, {response headers}, {Json callback}
            return $this->errorResponse('No Posts as of the moment', $posts, 404, 404, [], 'callback');
        }

        return $this->successResponse('Successfully get all posts', $posts);
    }

    public function all()
    {
        $posts = Post::all();

        if ($post->isEmpty()) {
            // You can also use Closure.
            return $this->errorResponse(function() {
                return response('No POSTS');
            }
        }

        return $this->successResponse('Successfully get all posts', $posts);
    }
}
```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [Jay Are Galinada][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/multimedia-street/common.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/multimedia-street/common/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/multimedia-street/common.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/multimedia-street/common.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/multimedia-street/common.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/multimedia-street/common
[link-travis]: https://travis-ci.org/multimedia-street/common
[link-scrutinizer]: https://scrutinizer-ci.com/g/multimedia-street/common/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/multimedia-street/common
[link-downloads]: https://packagist.org/packages/multimedia-street/common
[link-author]: https://github.com/jayaregalinada
[link-contributors]: ../../contributors
