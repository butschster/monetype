<?php

use Modules\Articles\Model\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::truncate();
        DB::table('article_tag')->truncate();

        factory(Tag::class, 'tag', 50)->create();
    }
}