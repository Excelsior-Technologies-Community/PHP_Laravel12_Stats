<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatisticScan extends Model
{
    protected $fillable = [
        'project_name',

        'number_of_classes',
        'number_of_methods',
        'methods_per_class',

        'loc',
        'lloc',
        'lloc_per_method',

        'code_lloc',
        'test_lloc',
        'code_to_test_ratio',
        'number_of_routes',

        'statistics',
        'scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'methods_per_class' => 'float',
            'lloc_per_method' => 'float',
            'code_to_test_ratio' => 'float',

            'statistics' => 'array',

            'scanned_at' => 'datetime',
        ];
    }
}