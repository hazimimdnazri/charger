<?php

use App\Models\Country;
use App\Models\Role;

if (! function_exists('getRoleIdBySlug')) {
    function getRoleIdBySlug($slug)
    {
        return Role::where('slug', $slug)->first()->id ?? null;
    }
}

if (! function_exists('getCountryIdByName')) {
    function getCountryIdByName($name)
    {
        return Country::whereRaw('LOWER(country) = ?', [strtolower($name)])
            ->first()
            ->id ?? null;
    }
}
