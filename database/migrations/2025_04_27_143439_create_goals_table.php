<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('goals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to the user
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('location_latitude')->nullable(); // (for map later)
        $table->string('location_longitude')->nullable(); // (for map later)
        $table->date('deadline')->nullable(); // (optional)
        $table->integer('progress')->default(0); // progress in %
        $table->enum('status', ['active', 'upcoming', 'completed'])->default('active'); // (for map later)
        $table->enum('visibility', ['private', 'public', 'friends'])->default('private');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
