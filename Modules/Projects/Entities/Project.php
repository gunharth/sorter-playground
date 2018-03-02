<?php

namespace Modules\Projects\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Translatable;

    protected $table = 'projects__projects';
    public $translatedAttributes = ['title', 'slug', 'content'];
    protected $fillable = ['title', 'slug', 'content'];
}
