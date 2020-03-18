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
            $table->bigIncrements('id');
            $table->nullableMorphs('entity');
            $table->string('slug');
            $table->{$this->jsonable()}('name');
            $table->{$this->jsonable()}('description')->nullable();
            $table->{$this->jsonable()}('content');
            $table->{$this->jsonable()}('actions')->nullable();
            $table->{$this->jsonable()}('submission')->nullable();
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

    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    protected function jsonable(): string
    {
        $driverName = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        $dbVersion = DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);
        $isOldVersion = version_compare($dbVersion, '5.7.8', 'lt');

        return $driverName === 'mysql' && $isOldVersion ? 'text' : 'json';
    }
}
