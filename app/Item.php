<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'title', 'state', 'user_provider_id'
    ];

    /**
     * Validate data
     *
     * @return bool
     */
    public function validate()
    {
        return isset($this->title) && $this->title;
    }
}
