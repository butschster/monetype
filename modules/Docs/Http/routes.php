<?php

//use Parsedown;
/**
 * Convert some text to Markdown...
 */
function markdown($text) {
    return (new Parsedown)->text($text);
}

Route::group(['as' => 'front.'], function () {
    Route::get('docs/{page?}', ['as' => 'docs.index', 'uses' => 'DocsController@show']);
});