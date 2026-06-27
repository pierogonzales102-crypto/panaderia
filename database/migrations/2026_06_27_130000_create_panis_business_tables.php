<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->boolean('terms_accepted')->default(false)->after('address');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('ingredients_text')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_customizable')->default(false);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->default('kg');
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('min_stock', 10, 2)->default(5);
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['entrada', 'salida']);
            $table->decimal('quantity', 10, 2);
            $table->string('reason')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->text('instructions')->nullable();
            $table->integer('prep_time_minutes')->nullable();
            $table->integer('yield_quantity')->default(1);
            $table->timestamps();
        });

        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('kg');
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['estandar', 'personalizado'])->default('estandar');
            $table->enum('status', [
                'pendiente', 'confirmado', 'en_produccion', 'listo',
                'en_camino', 'entregado', 'cancelado',
            ])->default('pendiente');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('discount_code')->nullable();
            $table->enum('payment_method', ['tarjeta', 'efectivo', 'transferencia'])->nullable();
            $table->enum('payment_status', ['pendiente', 'pagado', 'fallido'])->default('pendiente');
            $table->text('delivery_address')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->text('custom_instructions')->nullable();
            $table->string('reference_image')->nullable();
            $table->text('customization_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->text('customization')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['tarjeta', 'efectivo', 'transferencia']);
            $table->enum('status', ['pendiente', 'completado', 'fallido', 'reembolsado'])->default('pendiente');
            $table->string('transaction_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->enum('type', ['boleta', 'factura'])->default('boleta');
            $table->string('pdf_path')->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity');
            $table->enum('status', ['pendiente', 'en_proceso', 'completado', 'cancelado'])->default('pendiente');
            $table->date('production_date');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['borrador', 'enviada', 'recibida', 'cancelada'])->default('borrador');
            $table->decimal('total', 10, 2)->default(0);
            $table->date('expected_date')->nullable();
            $table->date('received_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('production_orders');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'terms_accepted']);
        });
    }
};
