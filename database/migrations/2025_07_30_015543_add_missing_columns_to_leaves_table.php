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
        Schema::table('leaves', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('leaves', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (!Schema::hasColumn('leaves', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (!Schema::hasColumn('leaves', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('leaves', 'total_days')) {
                $table->integer('total_days');
            }
            if (!Schema::hasColumn('leaves', 'reason')) {
                $table->text('reason');
            }
            if (!Schema::hasColumn('leaves', 'leave_type')) {
                $table->enum('leave_type', ['annual', 'sick', 'personal', 'maternity', 'paternity', 'bereavement'])->default('annual');
            }
            if (!Schema::hasColumn('leaves', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'rejection_reason', 'approved_by', 'total_days', 'reason', 'leave_type', 'status']);
        });
    }
};
