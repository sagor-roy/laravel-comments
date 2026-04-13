# SagorRoy Comments

A simple, lightweight comment system for Laravel 13 with AJAX support.

## Requirements

- PHP 8.3+
- Laravel 13.x

## Installation

### 1. Install via Composer

```bash
composer require sagor-roy/comments
```

### 2. Publish Assets

```bash
# Publish all assets
php artisan vendor:publish --provider="SagorRoy\Comments\CommentServiceProvider"

# Or individually:
php artisan vendor:publish --tag=comments-migrations
php artisan vendor:publish --tag=comments-config
php artisan vendor:publish --tag=comments-views
php artisan vendor:publish --tag=comments-assets
```

### 3. Run Migrations

```bash
php artisan migrate
```

## Usage

### 1. Make Model Commentable

Add the `Commentable` trait to any model you want to make commentable:

```php
// app/Models/Article.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SagorRoy\Comments\Traits\Commentable;

class Article extends Model
{
    use Commentable;
}
```

### 2. Display Comments in View

In your Blade template, use the `@comments` directive:

```blade
{{-- resources/views/articles/show.blade.php --}}
<article>
    <h1>{{ $article->title }}</h1>
    <p>{{ $article->content }}</p>
    
    @comments($article)
</article>
```

### 3. Include CSS

Add the CSS to your layout:

```blade
<link rel="stylesheet" href="{{ asset('vendor/comments/comments.css') }}">
```

### 4. Include JavaScript

Add the JavaScript to your layout:

```blade
<script src="{{ asset('vendor/comments/comments.js') }}"></script>
```

## Configuration

Publish the config file and customize:

```bash
php artisan vendor:publish --tag=comments-config
```

Then edit `config/comments.php`:

```php
return [
    'max_length' => env('COMMENTS_MAX_LENGTH', 1000),
    'allow_guest_replies' => env('COMMENTS_ALLOW_GUEST_REPLIES', true),
    'per_page' => env('COMMENTS_PER_PAGE', 15),
    'guest_name_required' => env('COMMENTS_GUEST_NAME_REQUIRED', true),
    'guest_name_max_length' => env('COMMENTS_GUEST_NAME_MAX', 100),
    'user_model' => env('COMMENTS_USER_MODEL', \App\Models\User::class),
];
```

### Environment Variables

Add to your `.env` file:

```env
COMMENTS_MAX_LENGTH=1000
COMMENTS_ALLOW_GUEST_REPLIES=true
COMMENTS_PER_PAGE=15
COMMENTS_GUEST_NAME_REQUIRED=true
COMMENTS_GUEST_NAME_MAX=100
COMMENTS_USER_MODEL=App\Models\User
```

## Features

### Commentable Models
Any Eloquent model can have comments by adding the `Commentable` trait.

### Nested Comments
Supports 1 level of nested replies. Replies to replies are not allowed.

### Guest Comments
Guests can leave comments with just their name. Authenticated users have their name auto-filled.

### AJAX Submission
Forms submit via AJAX for better user experience.

### Customizable Views
Publish and customize the Blade views to match your design.

## Customizing Views

```bash
php artisan vendor:publish --tag=comments-views
```

Views will be copied to `resources/views/vendor/comments/`.

## Security

- CSRF protection enabled
- XSS sanitization via Laravel's default escaping
- User can only delete their own comments
- Configurable max comment length

## License

MIT License
