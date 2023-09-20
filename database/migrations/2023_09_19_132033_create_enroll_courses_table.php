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
        Schema::create('enroll_courses', function (Blueprint $table) {
            $table->increments('enroll_course_id');
            $table->integer('course_id')->nullable()->unsigned()->comment('ref table: courses');
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->float('amount')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('transaction_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enroll_courses');
    }
};
