<?php

use Faker\Generator as Faker;

use Ponich\Eloquent\Traits\Tests\Models\Post;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $title = $faker->unique()->word,
        'slug' => str_slug($title),
        'content' => '<p>' . implode("</p>\n<p>", $faker->paragraphs(rand(3, 5))) . '</p>',
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
