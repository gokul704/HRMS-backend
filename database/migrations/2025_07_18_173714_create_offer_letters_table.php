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
        Schema::create('offer_letters', function (Blueprint $table) {
            $table->id();
            $table->string('offer_id')->unique(); // OFF001
            $table->string('candidate_name');
            $table->string('candidate_email');
            $table->string('candidate_phone')->nullable();
            $table->string('position');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->decimal('offered_salary', 10, 2);
            $table->string('salary_currency')->default('INR');
            $table->date('offer_date');
            $table->date('joining_date');
            $table->text('job_description')->nullable();
            $table->text('benefits')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'expired'])->default('draft');
            $table->date('response_date')->nullable();
            $table->text('response_notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('approved_at')->nullable();
            $table->string('offer_letter_file')->nullable(); // PDF file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_letters');
    }
};
