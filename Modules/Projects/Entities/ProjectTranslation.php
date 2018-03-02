<?php

namespace Modules\Projects\Entities;

use Illuminate\Database\Eloquent\Model;

class ProjectTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'projects__project_translations';
}
