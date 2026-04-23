<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'status',
        'note',
    ];

    public function items()
    {
        return $this->hasMany(AssignmentItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('note', 'like', '%'.$search.'%')
                    ->orWhereIn('id', function ($subQuery) use ($search) {
                        $subQuery->select('assignment_id')
                            ->from('assignment_items')
                            ->where('assignable_type', Employee::class)
                            ->whereIn('assignable_id', function ($employeeQuery) use ($search) {
                                $employeeQuery->select('id')
                                    ->from('employees')
                                    ->where('employee_name', 'like', '%'.$search.'%')
                                    ->orWhere('job_title', 'like', '%'.$search.'%')
                                    ->orWhere('email', 'like', '%'.$search.'%');
                            });
                    })
                    ->orWhereIn('id', function ($subQuery) use ($search) {
                        $subQuery->select('assignment_id')
                            ->from('assignment_items')
                            ->where('assignable_type', Cellphone::class)
                            ->whereIn('assignable_id', function ($cellphoneQuery) use ($search) {
                                $cellphoneQuery->select('id')
                                    ->from('cellphones')
                                    ->where('model', 'like', '%'.$search.'%')
                                    ->orWhere('brand', 'like', '%'.$search.'%')
                                    ->orWhere('imei', 'like', '%'.$search.'%');
                            });
                    })
                    ->orWhereIn('id', function ($subQuery) use ($search) {
                        $subQuery->select('assignment_id')
                            ->from('assignment_items')
                            ->where('assignable_type', Number::class)
                            ->whereIn('assignable_id', function ($numberQuery) use ($search) {
                                $numberQuery->select('id')
                                    ->from('numbers')
                                    ->where('number', 'like', '%'.$search.'%')
                                    ->orWhere('data_plan', 'like', '%'.$search.'%');
                            });
                    });
            });
        });
    }

    public function employeeItem()
    {
        return $this->items->first(function ($item) {
            return $item->assignable_type === Employee::class;
        });
    }

    public function cellphoneItem()
    {
        return $this->items->first(function ($item) {
            return $item->assignable_type === Cellphone::class;
        });
    }

    public function numberItem()
    {
        return $this->items->first(function ($item) {
            return $item->assignable_type === Number::class;
        });
    }

    public function employeeEntity()
    {
        return optional($this->employeeItem())->assignable;
    }

    public function cellphoneEntity()
    {
        return optional($this->cellphoneItem())->assignable;
    }

    public function numberEntity()
    {
        return optional($this->numberItem())->assignable;
    }

    public function itemSummary()
    {
        return $this->items->map(function ($item) {
            return $item->shortLabel();
        })->implode(' + ');
    }

    public function statusLabel()
    {
        $labels = [
            1 => 'Entrega Pendiente',
            2 => 'Activo',
            3 => 'Inactivo',
        ];

        return $labels[$this->status] ?? 'Sin estado';
    }
}
