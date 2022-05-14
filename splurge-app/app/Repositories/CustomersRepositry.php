<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomersRepository {
    public function findAll(Request $request) {
        $query = (new Customer())->newQuery();

        $search = $request->input("q");

        if (!empty($search)) {
            $term = sprintf("%%%s%%", $search);
            
            foreach (['first_name', 'last_name', 'email'] as $index => $attr) {
                if ($index > 0) {
                    $query = $query->orWhere($attr, "like", $term);
                } else {
                    $query = $query->where($attr, "like", $term);
                }
            }
        }
        $date_filter_prefixes = ["date" => "created_at", "created" => "created_at"];
        $date_opts = ["min_" => ">=", "max_" => "<="];
        foreach ($date_opts as $prex => $operator) {
            foreach ($date_filter_prefixes as $query_part => $model_attribute) {
                $query_name = sprintf("%s%s", $prex, $query_part);
                if (!is_null($request->input($query_name))) {
                    $query = $query->where($model_attribute, $operator, $request->input($query_name));
                }
            }
        }
        

        return $query;
    }
}