<?php

use Modules\Users\Model\User;
use Modules\Articles\Model\Tag;
use Modules\Articles\Model\Article;
use Modules\Comments\Model\Comment;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->defineAs(User::class, 'user', function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'gender' => $faker->randomElement(['male', 'female', 'other'])
    ];
});

$factory->defineAs(Tag::class, 'tag', function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->defineAs(Article::class, 'article', function (Faker\Generator $faker) {
    $users = User::where('id', '>', 3)->get()->lists('id', 'id')->all();

    $text = str_replace('e.', "e.\n\n<br />\n#### $faker->sentence\n\n", $faker->sentences(100, true));
    return [
        'title' => $faker->sentence(6),
        'cost' => $faker->numberBetween(0, 5),
        'text_source' => $faker->sentences(2, true) . '---read-more---' . $text,
        'disable_comments' => $faker->boolean(),
        'disable_stat_views' => $faker->boolean(),
        'disable_stat_pays' => $faker->boolean(),
        'author_id' => $faker->randomElement($users),
        'status' => $faker->randomElement(['new', 'published', 'approved'])
    ];
});

$factory->defineAs(Comment::class, 'comment', function (Faker\Generator $faker) {
    $users = User::where('id', '>', 3)->get()->lists('id', 'id')->all();

    return [
        'title' => $faker->sentence,
        'text' => $faker->sentences(rand(1, 3), true),
        'author_id' => $faker->randomElement($users)
    ];
});