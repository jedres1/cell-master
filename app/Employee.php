<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
   protected $fillable = [
      'employee_name',
      'department_id',
      'company_id',
      'job_title',
      'email'
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
                $query->where('employee_name','like','%'.$search.'%')
                    ->orWhere('job_title','like','%'.$search.'%')
                    ->orWhere('email','like','%'.$search.'%')
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
