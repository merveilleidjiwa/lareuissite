<?php
$migrations_path = 'database/migrations/';

function update_migration($file, $schema) {
    $content = file_get_contents($file);
    $content = preg_replace('/Schema::create\(.*?\)\s*\{.*?\};/s', $schema, $content);
    file_put_contents($file, $content);
}

// Admins
$file = glob($migrations_path . '*create_admins_table.php')[0];
update_migration($file, "Schema::create('admins', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->string('email')->unique();
            \$table->string('password');
            \$table->rememberToken();
            \$table->timestamps();
        });");

// Livreurs
$file = glob($migrations_path . '*create_livreurs_table.php')[0];
update_migration($file, "Schema::create('livreurs', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->string('email')->unique();
            \$table->string('phone')->nullable();
            \$table->string('password');
            \$table->boolean('is_active')->default(true);
            \$table->rememberToken();
            \$table->timestamps();
        });");

// Categories
$file = glob($migrations_path . '*create_categories_table.php')[0];
update_migration($file, "Schema::create('categories', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->text('description')->nullable();
            \$table->timestamps();
        });");

// Produits
$file = glob($migrations_path . '*create_produits_table.php')[0];
update_migration($file, "Schema::create('produits', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->text('description')->nullable();
            \$table->decimal('price', 10, 2);
            \$table->integer('stock')->default(0);
            \$table->string('image')->nullable();
            \$table->boolean('is_active')->default(true);
            \$table->timestamps();
        });");

// Promos
$file = glob($migrations_path . '*create_promos_table.php')[0];
update_migration($file, "Schema::create('promos', function (Blueprint \$table) {
            \$table->id();
            \$table->string('code')->unique();
            \$table->decimal('discount', 5, 2); // percentage
            \$table->date('valid_until');
            \$table->boolean('is_active')->default(true);
            \$table->timestamps();
        });");

// Commandes
$file = glob($migrations_path . '*create_commandes_table.php')[0];
update_migration($file, "Schema::create('commandes', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('livreur_id')->nullable()->constrained('livreurs')->onDelete('set null');
            \$table->decimal('total_amount', 10, 2);
            \$table->string('status')->default('pending');
            \$table->text('shipping_address');
            \$table->timestamps();
        });");

echo "Migrations updated\n";
