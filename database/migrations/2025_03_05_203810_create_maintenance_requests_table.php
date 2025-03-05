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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('request_type');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->dateTime('submitted_at')->useCurrent();
            $table->integer('technician_visits')->default(0);
            $table->text('problem_description');
            $table->text('technician_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('technician_name')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
