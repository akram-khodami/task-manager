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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'done', 'cancelled'])->default('todo');
            $table->dateTime('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->foreignId('folder_id')->nullable()->constrained('folders')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // ایندکس مرکب اصلی
            $table->index(['folder_id', 'created_by', 'status', 'priority'], 'tasks_main_filter_index');
            // ایندکس برای جستجوی عنوان و توضیحات
            $table->index(['title'], 'tasks_title_index');
            // ایندکس برای تاریخ سررسید
            $table->index(['due_date'], 'tasks_due_date_index');
            // ایندکس متن کامل برای جستجوی پیشرفته
            $table->fullText(['title', 'description'], 'tasks_fulltext_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
