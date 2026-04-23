<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cellphone;
use App\Employee;

class AssignmentCellphoneEmployee extends Model
{
    protected $fillable = [
        'cellphone_id',
        'employee_id',
        'status',
        'note'
    ];

   public function cellphone()
   {
      return $this->belongsTo(Cellphone::class);
   }
   public function employee()
   {
      return $this->belongsTo(Employee::class);
   }
   public function scopeFilter($query, $filters)
   {
       $query->when($filters['search'] ?? false , function($query, $search){
           $query->where(function($query) use($search) {
               $query->where('note','like','%'.$search.'%')
                   ->orWhereHas('employee', function ($query) use ($search) {
                       $query->where('employee_name', 'like', '%'.$search.'%')
                           ->orWhere('job_title', 'like', '%'.$search.'%');
                   })
                   ->orWhereHas('cellphone', function ($query) use ($search) {
                       $query->where('model', 'like', '%'.$search.'%')
                           ->orWhere('brand', 'like', '%'.$search.'%')
                           ->orWhere('imei', 'like', '%'.$search.'%')
                           ->orWhereHas('number', function ($query) use ($search) {
                               $query->where('number', 'like', '%'.$search.'%');
                           })
                           ->orWhereHas('company', function ($query) use ($search) {
                               $query->where('company_name', 'like', '%'.$search.'%');
                           })
                           ->orWhereHas('department', function ($query) use ($search) {
                               $query->where('department_name', 'like', '%'.$search.'%');
                           });
                   });
           });
       });
   }
}
