<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->unsignedBigInteger('university_id')->unique()->nullable(false);
            $table->string('level')->nullable();
            $table->string('department')->nullable();
            $table->string('batch')->nullable();
            $table->boolean('is_active')->nullable(false)->default(false);
            $table->unsignedBigInteger('fees_id');
            $table->unsignedBigInteger('installments_id');
            $table->unsignedBigInteger('reductions_id');
            
            $table->index('fees_id');
            $table->index('installments_id');
            $table->index('reductions_id');
            $table->timestamps();

            $table->foreign('fees_id')->references('id')->on('fees')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('installments_id')->references('id')->on('installments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('reductions_id')->references('id')->on('reductions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
