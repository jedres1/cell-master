<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentItem extends Model
{
    protected $fillable = [
        'assignment_id',
        'assignable_type',
        'assignable_id',
        'slot',
        'sort_order',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function assignable()
    {
        return $this->morphTo();
    }

    public function shortLabel()
    {
        if (!$this->assignable) {
            return 'Elemento no disponible';
        }

        if ($this->assignable_type === Employee::class) {
            return 'Empleado: '.$this->assignable->employee_name;
        }

        if ($this->assignable_type === Cellphone::class) {
            return 'Celular: '.$this->assignable->brand.' '.$this->assignable->model;
        }

        return 'Numero: '.$this->assignable->number;
    }
}
