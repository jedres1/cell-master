<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    protected $fillable = [
        'number',
        'company_id',
        'status',
        'data_plan'
    ];

    public function company()
    {
       return $this->belongsTo(Company::class);
    }

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? false , function($query, $search){
            $query->where(function($query) use($search) {
                $query->where('number','like','%'.$search.'%')
                    ->orWhere('data_plan','like','%'.$search.'%')
                    ->orWhereHas('company', function ($query) use ($search) {
                        $query->where('company_name', 'like', '%'.$search.'%');
                    });
            });
        });
    }
}
