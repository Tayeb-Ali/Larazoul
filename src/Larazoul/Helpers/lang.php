<?php

/*
 * get current language key
 */

function l(){
    return \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
}

/*
 * get filed with current lang
 * pass the filed and we will return this filed with
 * the currnet lang
 */

function fwcl($filed){
    return $filed.'_'.l();
}

/*
 * return url with current language key
 */

function urll($url){

    /*
     * remove slash if exists
     */

    $url = ltrim($url , '/');

    /*
     * return url with the current lang
     */

    return url(l().'/'.$url);
}

/*
 * get the current language direction
 */

function getLayoutDirection(){
    return \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocaleDirection();
}

/*
 * get the normal direction for current language
 */

function ROL(){
    return getLayoutDirection() == 'rtl' ? 'right' : 'left';
}

/*
 * reverse the normal direction for current language
 */

function rROL(){
    return getLayoutDirection() == 'rtl' ? 'left' : 'right';
}

