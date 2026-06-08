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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('Teacher_Name');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('mobile_no');
            $table->string('gender');
            $table->date('dob');
            $table->integer('age');
            $table->string('qualification');
            $table->string('subject');
            $table->double('salary');
            $table->date('join_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
