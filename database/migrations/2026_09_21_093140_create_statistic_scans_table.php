<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('statistic_scans', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Project Information
            |--------------------------------------------------------------------------
            */

            $table->string('project_name');


            /*
            |--------------------------------------------------------------------------
            | Total Project Statistics
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('number_of_classes')
                ->default(0);

            $table->unsignedInteger('number_of_methods')
                ->default(0);

            $table->decimal('methods_per_class', 10, 2)
                ->default(0);

            $table->unsignedInteger('loc')
                ->default(0);

            $table->unsignedInteger('lloc')
                ->default(0);

            $table->decimal('lloc_per_method', 10, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Project Meta Statistics
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('code_lloc')
                ->default(0);

            $table->unsignedInteger('test_lloc')
                ->default(0);

            $table->decimal('code_to_test_ratio', 10, 2)
                ->default(0);

            $table->unsignedInteger('number_of_routes')
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Complete Laravel Stats JSON
            |--------------------------------------------------------------------------
            */

            $table->json('statistics');


            /*
            |--------------------------------------------------------------------------
            | Scan Information
            |--------------------------------------------------------------------------
            */

            $table->timestamp('scanned_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_scans');
    }
};