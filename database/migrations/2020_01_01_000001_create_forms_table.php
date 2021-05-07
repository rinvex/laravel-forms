<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.forms.tables.forms'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->nullableMorphs('entity');
            $table->string('slug');
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('content');
            $table->json('actions')->nullable();
            $table->json('submission')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('rinvex.forms.tables.forms'));
    }
}
