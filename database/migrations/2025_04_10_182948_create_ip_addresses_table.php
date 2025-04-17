<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('access');
            $table->string('family');
            $table->string('floating');
            $table->string('ptr_record');
            $table->string('mac');
            $table->string('release_policy');
            $table->string('is_delegated');
            $table->string('zone_id');
            $table->foreignId('server_id')
                ->nullable()
                ->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_addresses');
    }
};
