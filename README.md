# Traits for Eloquent Models

[![Packagist License](https://poser.pugx.org/ponich/eloquent-traits/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/ponich/eloquent-traits/version.png)](https://packagist.org/packages/ponich/eloquent-traits)
[![Total Downloads](https://poser.pugx.org/ponich/eloquent-traits/d/total.png)](https://packagist.org/packages/ponich/eloquent-traits)

This package adds the ability to use traits in you Laravel Eloquent Models

> [The traits list below](#traits)

### Installation

This package can be used in Laravel 5.5 or higher.

``
composer require ponich/eloquent-traits
``

You can publish the migration with:


```
php artisan vendor:publish --provider="Ponich\Eloquent\Traits\ServiceProvider" --tag="migrations"
```

After the migration has been published you can create tables by running the migrations:


``php artisan migration``

### Traits

#### Virtual Attributes

Adds the ability to create virtual attributes in your model.

For use, use trait: ``\Ponich\Eloquent\Traits\VirtualAttribute``

**Example:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Ponich\Eloquent\Traits\VirtualAttribute;

    protected $table = 'posts';

    protected $guarded = ['id'];

    public $virtalAttributes = ['tags', 'og_tags'];
}
```

In the property of the class
 ``$virtalAttributes`` list all valid virtual attributes.

```php
$post = Post::firstOrFail(1);

$post->tags = ['tag1', 'tag2', 'tag3'];
$post->save();

$post->refresh();

var_dump($post->tags); 
/**
    array(3) {
      [0]=>
      string(4) "tag1"
      [1]=>
      string(4) "tag2"
      [2]=>
      string(4) "tag3"
    }
*/
```

