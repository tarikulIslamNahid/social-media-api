<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    public static function uniqueSlug($title){
        $slug = Str::slug($title, '-');
        $count = Page::where('slug', 'LIKE', "{$slug}%")->count();
        $newCount = $count > 0 ? ++$count : '';
        return $newCount > 0 ? "$slug-$newCount" : $slug;
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
