<?php
$migrations_path = 'database/migrations/';

function rewrite_migration($file, $table_name, $schema_body) {
    $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$table_name}', function (Blueprint \$table) {
{$schema_body}
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table_name}');
    }
};
PHP;
    file_put_contents($file, $content);
}

// Admins
$file = glob($migrations_path . '*create_admins_table.php')[0];
rewrite_migration($file, 'admins', "            \$table->id();\n            \$table->string('name');\n            \$table->string('email')->unique();\n            \$table->string('password');\n            \$table->rememberToken();\n            \$table->timestamps();");

// Livreurs
$file = glob($migrations_path . '*create_livreurs_table.php')[0];
rewrite_migration($file, 'livreurs', "            \$table->id();\n            \$table->string('name');\n            \$table->string('email')->unique();\n            \$table->string('phone')->nullable();\n            \$table->string('password');\n            \$table->boolean('is_active')->default(true);\n            \$table->rememberToken();\n            \$table->timestamps();");

// Categories
$file = glob($migrations_path . '*create_categories_table.php')[0];
rewrite_migration($file, 'categories', "            \$table->id();\n            \$table->string('name');\n            \$table->string('slug')->unique();\n            \$table->text('description')->nullable();\n            \$table->timestamps();");

// Produits
$file = glob($migrations_path . '*create_produits_table.php')[0];
rewrite_migration($file, 'produits', "            \$table->id();\n            \$table->foreignId('category_id')->constrained('categories')->onDelete('cascade');\n            \$table->string('name');\n            \$table->string('slug')->unique();\n            \$table->text('description')->nullable();\n            \$table->decimal('price', 10, 2);\n            \$table->integer('stock')->default(0);\n            \$table->string('image')->nullable();\n            \$table->boolean('is_active')->default(true);\n            \$table->timestamps();");

// Promos
$file = glob($migrations_path . '*create_promos_table.php')[0];
rewrite_migration($file, 'promos', "            \$table->id();\n            \$table->string('code')->unique();\n            \$table->decimal('discount', 5, 2);\n            \$table->date('valid_until');\n            \$table->boolean('is_active')->default(true);\n            \$table->timestamps();");

// Commandes
$file = glob($migrations_path . '*create_commandes_table.php')[0];
rewrite_migration($file, 'commandes', "            \$table->id();\n            \$table->foreignId('user_id')->constrained()->onDelete('cascade');\n            \$table->foreignId('livreur_id')->nullable()->constrained('livreurs')->onDelete('set null');\n            \$table->decimal('total_amount', 10, 2);\n            \$table->string('status')->default('pending');\n            \$table->text('shipping_address');\n            \$table->timestamps();");

echo "Migrations fully rewritten\n";
