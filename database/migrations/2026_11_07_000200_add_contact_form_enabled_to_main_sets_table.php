<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Переключатель «Показывать форму заявки на сайте» в «Настройках сайта». По умолчанию — включено. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main_sets', function (Blueprint $table) {
            $table->boolean('contact_form_enabled')->default(true)->after('footer');
        });
    }

    public function down(): void
    {
        Schema::table('main_sets', function (Blueprint $table) {
            $table->dropColumn('contact_form_enabled');
        });
    }
};
