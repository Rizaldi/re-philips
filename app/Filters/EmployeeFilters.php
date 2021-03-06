<?php

namespace App\Filters;

use App\Employee;
use Illuminate\Http\Request;

class EmployeeFilters extends QueryFilters
{

    /**
     * Ordering data by name
     */
    public function name($value) {
        return (!$this->requestAllData($value)) ? $this->builder->where('name', 'like', '%'.$value.'%') : null;
    } 

    /* Ordering data by role */
    public function role($value){
    	return $this->builder->where('role', $value);
    }

}