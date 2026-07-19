<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create sales_channels table
        Schema::create('sales_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Create product_sales_channels pivot table
        Schema::create('product_sales_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_channel_id')->constrained()->onDelete('cascade');
            $table->string('purchase_url')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->integer('priority')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('external_product_id')->nullable();
            $table->timestamps();
        });

        // 3. Create license_providers table
        Schema::create('license_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Create licenses table
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('license_provider_id')->constrained()->onDelete('cascade');
            $table->string('license_key')->unique();
            $table->timestamp('purchased_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('support_expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Create license_activations table
        Schema::create('license_activations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->string('domain');
            $table->string('ip_address')->nullable();
            $table->timestamp('activated_at')->useCurrent();
            $table->timestamps();
        });

        // 6. Alter license_verifications to add nullable license_id link
        Schema::table('license_verifications', function (Blueprint $table) {
            $table->foreignId('license_id')->nullable()->constrained()->onDelete('set null');
        });

        // 7. Alter support_tickets to add nullable license_id link
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->foreignId('license_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn('license_id');
        });

        Schema::table('license_verifications', function (Blueprint $table) {
            $table->dropColumn('license_id');
        });

        Schema::dropIfExists('license_activations');
        Schema::dropIfExists('licenses');
        Schema::dropIfExists('license_providers');
        Schema::dropIfExists('product_sales_channels');
        Schema::dropIfExists('sales_channels');
    }
};
