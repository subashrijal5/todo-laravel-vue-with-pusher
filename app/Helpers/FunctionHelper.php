<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FunctionHelper
{
    public static function getEntity($entity = null, $parameter = null)
    {
        if (empty($entity)) {
            if ((request()->is('admin/*') || request()->is('sub-admin/*') || request()->is('customer/*')) && is_string(request()->segment(2))) {
                $resources = request()->segment(2);
            } else {
                $resources = request()->segment(1);
            }
        } else {
            $resources = $entity;
        }

        $model = !empty($resources) ? Str::singular($resources) : '';
        $view = !empty($resources) ? str_replace('-', '_', Str::snake($resources)) : '';
        $plural = str_replace('_', ' ', Str::title($view));
        $singular = Str::singular($plural);
        $urlPath = array_values(array_filter(explode('/', request()->getPathInfo()), 'strlen'));
        $prefix = array_shift($urlPath);
        $data = [
            'prefix' => $prefix ? $prefix : '',
            'url' => $resources,
            'targetModel' => $model,
            'view' => $view,
            'plural' => $plural,
            'singular' => $singular,
        ];
        return  $data[$parameter] ?? $data;
    }
}
