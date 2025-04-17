<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('server_ssh_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id');
            $table->foreignId('ssh_key_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_ssh_keys');
    }
};
