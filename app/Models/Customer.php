<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory,SoftDeletes;

    public $incremented = false;

    protected $fillable =['id','nif', 'payment_type', 'payment_ref'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'customer_id', 'id');
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'id','id')->withTrashed();
    }
}
