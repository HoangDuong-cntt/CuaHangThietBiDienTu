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
        Schema::create('specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('specification_groups')->cascadeOnDelete();
            $table->string('name');
            $table->string('unit', 50)->nullable();
            $table->enum('compare_rule', ['none', 'higher_is_better', 'lower_is_better'])->default('none');
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specifications');
    }
};
