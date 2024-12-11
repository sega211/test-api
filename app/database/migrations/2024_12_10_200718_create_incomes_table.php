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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id(); 
            $table->bigInteger('income_id')->unique(); 
            $table->string('number')->nullable(); 
            $table->date('date');
            $table->date('last_change_date')->nullable(); 
            $table->string('supplier_article'); 
            $table->string('tech_size')->nullable(); 
            $table->bigInteger('barcode')->nullable(); 
            $table->integer('quantity'); 
            $table->decimal('total_price', 10, 2)->default(0); 
            $table->date('date_close')->nullable(); 
            $table->string('warehouse_name')->nullable(); 
            $table->bigInteger('nm_id')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
