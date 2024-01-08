<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class Media extends Eloquent
{
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 'properties' => 'array',
    ];
    protected $table = 'medias';
    protected $dates = ['created_at', 'deleted_at','updated_at'];
    protected $guarded = [];
    // public $appends = ['mainMediaUrl'];

    public function getMainMediaUrlAttribute()
    {
        if ($this->media) {
            $media = $this->media()->where('collection_name', 'main')->first();
            return Config::get('main.media_url').$media->name.$media->file_name;
        } else {
            return null;
        }
    }
    public function mediable()
    {
        return $this->morphTo('media_type');
    }


    public function renderActionButton($id)
    {
        $html = '';
        $html .= '<button type="button" class="btn btn-success btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>';
        $html .= '<div class="dropdown-menu" role="menu" style="">
        <a class="dropdown-item edit_button" data-id='.$id.' href="#" data-toggle="modal" data-target="#create_model">修改</a>
                      <a class="dropdown-item delete_button" id='.$id.' href="#">删除</a>
                    </div>';
        return $html;
    }
}
