<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cellphone extends Model
{
   protected $fillable = [
    'model',
    'brand',
    'imei',
    'status',
    'accessories',
    'department_id',
    'company_id'
   ];

   public function department()
   {
      return $this->belongsTo(Department::class);
   }
   public function company()
   {
      return $this->belongsTo(Company::class);
   }
   public function scopeFilter($query, $filters)
   {
       $query->when($filters['search'] ?? false , function($query, $search){
           $query->where(function($query) use($search) {
               $query->where('model','like','%'.$search.'%')
                   ->orWhere('brand','like','%'.$search.'%')
                   ->orWhere('imei','like','%'.$search.'%')
                   ->orWhereHas('company', function ($query) use ($search) {
                       $query->where('company_name', 'like', '%'.$search.'%');
                   })
                   ->orWhereHas('department', function ($query) use ($search) {
                       $query->where('department_name', 'like', '%'.$search.'%');
                   });
           });
       });
   }
}
