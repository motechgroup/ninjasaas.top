<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('envato_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('item_id')->unique();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        Schema::create('envato_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('envato_item_id')->constrained('envato_items')->onDelete('cascade');
            $table->string('purchase_code')->unique();
            $table->string('envato_username');
            $table->timestamp('purchase_date');
            $table->timestamp('support_expiry')->nullable();
            $table->string('license_type')->nullable(); // Regular, Extended
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('license_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envato_purchase_id')->nullable()->constrained('envato_purchases')->onDelete('set null');
            $table->string('purchase_code')->nullable();
            $table->string('product_id')->nullable();
            $table->string('domain')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_valid')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_verifications');
        Schema::dropIfExists('envato_purchases');
        Schema::dropIfExists('envato_items');
    }
};
