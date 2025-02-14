<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins_telegram', function (Blueprint $table) {
            $table->id();
            $table->string('ADMIN_USERNAME');
            $table->string('ADMIN_PASSWORD');
            $table->string('ADMIN_ROLE');
            $table->string('ADMIN_IDTELEGRAM');
            $table->timestamps();
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins_telegram');
    }
};
