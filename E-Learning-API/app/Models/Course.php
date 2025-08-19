<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    protected $fillable = ['title', 'description', 'price', 'created_by'];

    public function instructor() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }
}
