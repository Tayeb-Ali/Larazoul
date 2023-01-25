<?php

function orderType($name){
    if(request()->has('orderBy')){
        if(request()->get('orderBy') == $name){
            if(request()->has('orderByType') && request()->get('orderByType') == 'decs'){
                return '&orderByType=asc';
            }
        }
    }
    return '&orderByType=decs';
}