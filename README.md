# Traits for Eloquent Models

[![Build Status](https://travis-ci.org/ponich/eloquent-traits.svg?branch=master)](https://travis-ci.org/ponich/eloquent-traits)
[![License](https://poser.pugx.org/ponich/eloquent-traits/license)](https://packagist.org/packages/ponich/eloquent-traits)
[![Latest Stable Version](https://poser.pugx.org/ponich/eloquent-traits/v/stable)](https://packagist.org/packages/ponich/eloquent-traits)
[![Total Downloads](https://poser.pugx.org/ponich/eloquent-traits/downloads)](https://packagist.org/packages/ponich/eloquent-traits)

This package adds the ability to use traits in you Laravel Eloquent Models

**[Traits list](#traits)**

 - [Virtual Attributes ](#virtual-attributes)
 - [Attachments](#attachments)


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


``php artisan migrate``

### Traits

#### Virtual Attributes

Adds the ability to create virtual attributes in your model.

Use trait: [``\Ponich\Eloquent\Traits\VirtualAttribute``](src/VirtualAttribute.php)

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
#### Attachments

Allows links files to models

Use trait: [``\Ponich\Eloquent\Traits\HasAttachment``](src/HasAttachment.php)

**Example:**

**Model:** 

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Ponich\Eloquent\Traits\HasAttachment;

    protected $table = 'posts';

    protected $guarded = ['id'];
}
```

**Add attachment :**

```php
$post = Post::findOrFail(1);

// by path
$post->attach('/path/to/file');

// by request
$post->attach(
    $request->file('photo')
);
```
