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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang ngelakuin
        $table->string('action');      // Ngapain (misal: 'login', 'create_planner', 'upload_doc')
        $table->string('ip_address')->nullable(); // Dari IP mana (buat keamanan)
        $table->timestamps();          // Kapan dilakuinnya
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
