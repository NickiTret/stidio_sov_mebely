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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_src');
            $table->timestamps();
            //связь один ко многим с таблицей группы
            $table->unsignedBigInteger('group_id')->nullable();
            $table->index('group_id', 'slide_group_idx');
            $table->foreign('group_id', 'slide_group_fk')->on('groups')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siders');
    }
};
