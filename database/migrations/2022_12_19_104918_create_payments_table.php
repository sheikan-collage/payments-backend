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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('name')->nullable(false);
            $table->unsignedBigInteger('university_id')->nullable(false);
            $table->string('level')->nullable();
            $table->string('department')->nullable();
            $table->string('batch')->nullable();
            $table->json('fees_data')->nullable(false);
            $table->json('installments_data')->nullable(false);
            $table->json('reductions_data')->nullable(false);
            $table->unsignedDouble('payed_amount')->nullable(false);
            $table->enum('currency', ['USD', 'SDG'])->nullable(false);
            $table->text('reference_id');
            $table->text('follow_up_number');
            $table->timestamps();

            $table->index('student_id');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
