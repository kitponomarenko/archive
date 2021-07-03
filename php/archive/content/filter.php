<?php

namespace archive\content;

include __DIR__ . '/../loader.php';

class filter {

    function __construct() {}

    function get_filter_common(
            string $ref_name,
            string $ref_val,
            string $operation = '='
    ) {
        $filter = '';
        if(!empty($ref_val)){
            if ($operation == 'LIKE') {
                $filter = "$ref_name LIKE '%$ref_val%'";
            } else {
                $filter = "$ref_name $operation '$ref_val'";
            }
        }

        return $filter;
    }

    function get_filter_mult(
            array $ref_name,
            array $ref_arr,
            string $operation = '='
    ) {
        $filter = '';

        if (!empty($ref_name)) {
            $first_el = 1;
            foreach ($ref_name as $name) {
                foreach ($ref_arr as $ref_val) {
                    $filter_str = $this->get_filter_common($name, $ref_val, $operation);
                    if ($first_el == 1) {
                        $first_el = 0;
                        $filter = "$filter_str";
                    } else {
                        $filter .= " OR $filter_str";
                    }
                }
            }
        }

        if (!empty($filter)) {
            $filter = "($filter)";
        }

        return $filter;
    }

    function get_filter_complex(
            array $filters
    ) {
        $filter = '';

        $first_el = 1;
        foreach ($filters as $filter_arr) {
            if (is_array($filter_arr[1])) {
                $filter_str = $this->get_filter_mult(...$filter_arr);
            } else {
                $filter_str = $this->get_filter_common(...$filter_arr);
            }
            
            if(!empty($filter_str)){
                if ($first_el == 1) {
                    $first_el = 0;
                    $filter = "$filter_str";
                } else {
                    $filter .= " AND $filter_str";
                }
            }
        }

        return $filter;
    }

    function get_filter_sort(
            array $sort =['id' => 'DESC']
    ) {
        $filter = " ORDER BY ";
        $i = 0;
        foreach($sort as $key => $val){
            if($i > 0){
                $filter .= ", ";
            }
            $filter .= "$key $val";            
            ++$i;
        }
        return $filter;
    }

    function get_filter_limit(
            int $request = 0,
            int $limit = null
    ) {        
        $filter = "";
        if ($limit != null) {
            $offset = $request * $limit;
            $filter = " LIMIT $offset, $limit";
        }

        return $filter;
    }

    function get_filter_active(
            int $active = null
    ) {
        $filter = "";
        if ($active != null) {
            $filter = "active='$active'";
        }

        return $filter;
    }

    function complete_filter(
            array $filters = [],
            int $request = 0,
            array $sort =['id' => 'DESC'],
            int $limit = null,
            int $active = null
    ) {
        $filter = $this->get_filter_active($active);

        if (!empty($filters) && !empty($filters[0])) {
            $filter_main = $this->get_filter_complex($filters);
            if ((!empty($filter)) && (!empty($filter_main))) {
                $filter .= " AND ";
            }
            $filter .= $filter_main;            
        }
        
        if(!empty($filter)){
            $filter = "WHERE " . $filter;
        }
        
        $filter .= $this->get_filter_sort($sort);
        $filter .= $this->get_filter_limit($request, $limit);

        return $filter;
    }

}
