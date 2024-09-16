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
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->integer('contribution_hours')->default(0);
            $table->dateTime('last_activity')->nullable();
            $table->enum('p_rule',['admin','manager','developer','tester']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');               
            $table->foreignId('project_id')->constrained()->onDelete('cascade')->onUpdate('cascade');                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};
