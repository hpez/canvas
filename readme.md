<p align="center">
    <br>
    <img src="https://raw.githubusercontent.com/cnvs/art/master/github-header.png" width="400">
</p>

<p align="center">
	<a href="https://travis-ci.org/cnvs/canvas"><img src="https://travis-ci.org/cnvs/canvas.svg?branch=master"></a>
	<a href="https://packagist.org/packages/cnvs/canvas"><img src="https://poser.pugx.org/cnvs/canvas/downloads"></a>
	<a href="https://packagist.org/packages/cnvs/canvas"><img src="https://poser.pugx.org/cnvs/canvas/v/stable"></a>
	<a href="https://packagist.org/packages/cnvs/canvas"><img src="https://poser.pugx.org/cnvs/canvas/license"></a>
    <br><br>
</p>

## Introduction

A [Laravel](https://laravel.com) publishing platform. Canvas is a fully open source package to extend your 
application and get you up-and-running with a blog in just a few minutes. In addition to a distraction-free 
writing experience, you can view monthly trends on your content, get insights into reader traffic and more!

## Installation

> **Note:** Canvas requires you to have user authentication in place prior to installation. For Laravel 5.* based projects you may run the `make:auth` Artisan command to satisfy this requirement. For Laravel 6.* based projects please see the [official guide](https://laravel.com/docs/6.x/authentication#introduction) to get started.   

You may use composer to install Canvas into your Laravel project:

```bash
composer require cnvs/canvas
```

Publish the assets and primary configuration file using the `canvas:install` Artisan command:

```bash
php artisan canvas:install
```

Create a symbolic link to ensure file uploads are publicly accessible from the web using the `storage:link` Artisan command:

```bash
php artisan storage:link
```

## Usage

### Configuration

After publishing Canvas's assets, a primary configuration file will be located at `config/canvas.php`. This file allows you to customize various aspects of how your application uses the package.

Canvas exposes a simple UI at `/canvas` by default. This can be changed by updating the `path` option:

```php
/*
|--------------------------------------------------------------------------
| Canvas Path
|--------------------------------------------------------------------------
|
| This is the URI path where Canvas will be accessible from. You are free
| to change this path to anything you like. Note that the URI will not
| affect the paths of its internal API that aren't exposed to users.
|
*/

'path' => env('CANVAS_PATH_NAME', 'canvas'),
```

If you'd like to restrict access to Canvas in a production environment, add any custom middleware to the following array:

```php
/*
|--------------------------------------------------------------------------
| Route Middleware
|--------------------------------------------------------------------------
|
| These middleware will be attached to every route in Canvas, giving you
| the chance to add your own middleware to this list or change any of
| the existing middleware. Or, you can simply stick with the list.
|
*/

'middleware' => [
    'web',
    'auth',
],
```

### Publishing

Canvas takes care of all the backend publishing while giving you the freedom to display it on the frontend however you choose. A very simple setup would include a controller, some views, and a few routes. Take a look at the following example:

Define a few routes inside of `routes/web.php`:

```php
// Get all published posts
Route::get('blog', 'BlogController@getPosts');

// Get posts for a given tag
Route::get('tag/{slug}', 'BlogController@getPostsByTag');

// Get posts for a given topic
Route::get('topic/{slug}', 'BlogController@getPostsByTopic');

// Find a single post
Route::middleware('Canvas\Http\Middleware\ViewThrottle')->get('{slug}', 'BlogController@findPostBySlug');
```

Add the corresponding methods inside of a new `BlogController`:

```php
public function getPosts()
{
    $data = [
        'posts' => \Canvas\Post::published()->orderByDesc('published_at')->simplePaginate(10),
    ];

    return view('blog.index', compact('data'));
}
```

```php
public function getPostsByTag(string $slug)
{
    if (\Canvas\Tag::where('slug', $slug)->first()) {
        $data = [
            'posts' => \Canvas\Post::whereHas('tags', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->published()->orderByDesc('published_at')->simplePaginate(10),
        ];

        return view('blog.index', compact('data'));
    } else {
        abort(404);
    }
}
```

```php
public function getPostsByTopic(string $slug)
{
    if (\Canvas\Topic::where('slug', $slug)->first()) {
        $data = [
            'posts' => \Canvas\Post::whereHas('topic', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->published()->orderByDesc('published_at')->simplePaginate(10),
        ];

        return view('blog.index', compact('data'));
    } else {
        abort(404);
    }
}
```

```php
public function findPostBySlug(string $slug)
{
    $posts = \Canvas\Post::with('tags', 'topic')->published()->get();
    $post = $posts->firstWhere('slug', $slug);

    if (optional($post)->published) {
        $data = [
            'author' => $post->author,
            'post'   => $post,
            'meta'   => $post->meta,
        ];

        // IMPORTANT: Including this event allows Canvas to save the view data
        event(new \Canvas\Events\PostViewed($post));

        return view('blog.show', compact('data'));
    } else {
        abort(404);
    }
}
```

If you'd rather have this completed for you automatically, just follow through the optional guide below to generate a fully-featured template. Aside from general post listings, you'll get localized content, reading suggestions and more!

## Options

> **Note:** The following steps are optional configurations, you are not required to complete them.

**Want to get started fast?** Just run `php artisan canvas:setup` after installing Canvas. A `--data` option may also be included in the command to generate demo data. Then, navigate your browser to `http://your-app.test/blog` or any other URL that is assigned to your application. This command scaffolds a default frontend for your entire blog!

If you want to include [Unsplash](https://unsplash.com) images in your post content, set up a new application at [https://unsplash.com/oauth/applications](https://unsplash.com/oauth/applications). Grab your access key and update `config/canvas.php`:

```php
/*
|--------------------------------------------------------------------------
| Unsplash Integration
|--------------------------------------------------------------------------
|
| Visit https://unsplash.com/oauth/applications to create a new Unsplash
| app. Use the confidential Access Key given to you to integrate with
| the API. Note that demo apps are limited to 50 requests per hour.
|
*/

'unsplash' => [
    'access_key' => env('CANVAS_UNSPLASH_ACCESS_KEY'),
],
```

**Want a weekly summary?** Canvas provides support for a weekly e-mail that gives you quick stats of the content you've authored, delivered straight to your inbox. Once your application is [configured for sending mail](https://laravel.com/docs/5.8/mail), update `config/canvas.php`:

```php
/*
|--------------------------------------------------------------------------
| Notifications
|--------------------------------------------------------------------------
|
| This option enables Canvas to send e-mail notifications via the default
| mail driver. If enabled, a weekly summary will be sent to each user
| that has authored content providing them with unique analytics.
|
*/

'mail' => [
    'enabled' => env('CANVAS_MAIL_ENABLED', false),
],
```

Since the weekly digest runs on [Laravel's Scheduler](https://laravel.com/docs/5.8/scheduling#introduction), you'll need to add the following cron entry to your server:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Updates

You may update your Canvas installation using composer:

```bash
composer update
```

Run any new migrations using the `migrate` Artisan command:

```bash
php artisan migrate
```

Re-publish the assets using the `canvas:publish` Artisan command:

```bash
php artisan canvas:publish
```

## Testing

Run the tests with:

```bash
composer test
```

## License

Canvas is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

- [The team](https://github.com/orgs/cnvs/people) that continues to support and develop this project
- Thanks to [Mohamed Said](https://themsaid.com/) and his project [Wink](https://github.com/writingink/wink) for design and component inspiration
- Anyone who has [contributed a patch](https://github.com/cnvs/canvas/pulls) or [made a helpful suggestion](https://github.com/cnvs/canvas/issues)
