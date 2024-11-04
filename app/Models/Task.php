<?php

namespace App\Models;

use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['title', 'description', 'due_date', 'status_id','owner_id'];

    protected $hidden = ['created_at','updated_at'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    protected $appends = ['status_name','owner_name'];

    public function getStatusNameAttribute() {
        return $this->status ? $this->status->status : null;
    }
    public function getOwnerNameAttribute() {
        return $this->owner ? $this->owner->name : null;
    }
}
