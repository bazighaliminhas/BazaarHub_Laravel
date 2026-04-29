<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Only create if table doesn't exist
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->string('device_user_id');
                $table->string('employee_name')->nullable();
                $table->dateTime('punch_time');
                $table->tinyInteger('punch_type')->default(0); // 0=IN, 1=OUT
                $table->string('device_ip')->default('mock');
                $table->timestamps();

                $table->unique(['device_user_id', 'punch_time']); // prevent duplicates
            });
        } else {
            // ✅ Table exists — just add missing columns if needed
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'device_user_id')) {
                    $table->string('device_user_id')->after('id');
                }
                if (!Schema::hasColumn('attendances', 'employee_name')) {
                    $table->string('employee_name')->nullable()->after('device_user_id');
                }
                if (!Schema::hasColumn('attendances', 'punch_time')) {
                    $table->dateTime('punch_time')->after('employee_name');
                }
                if (!Schema::hasColumn('attendances', 'punch_type')) {
                    $table->tinyInteger('punch_type')->default(0)->after('punch_time');
                }
                if (!Schema::hasColumn('attendances', 'device_ip')) {
                    $table->string('device_ip')->default('mock')->after('punch_type');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};