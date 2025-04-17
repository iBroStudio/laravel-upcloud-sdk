<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->uuid(); // $uuid
            $table->string('state'); // $state
            $table->string('title'); // $title
            $table->string('hostname'); // $hostname
            $table->bigInteger('host'); // $host
            $table->integer('core_number'); // $core_number
            $table->integer('memory_amount'); // $memory_amount
            $table->string('plan_id'); // $plan
            $table->uuid('server_group')->nullable(); // $server_group
            $table->string('zone_id'); // $zone
            $table->string('timezone'); // $timezone
            $table->string('firewall'); // $firewall
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
