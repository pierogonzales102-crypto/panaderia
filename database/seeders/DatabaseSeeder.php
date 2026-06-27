<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['administrador', 'gerente', 'ventas', 'produccion', 'moderador_web', 'cliente'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $admin = User::create([
            'name' => 'Administrador Panis',
            'email' => 'admin@panisandco.com',
            'password' => Hash::make('password'),
            'phone' => '+51 999 111 222',
            'terms_accepted' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('administrador');

        $ventas = User::create([
            'name' => 'María Ventas',
            'email' => 'ventas@panisandco.com',
            'password' => Hash::make('password'),
            'phone' => '+51 999 333 444',
            'terms_accepted' => true,
            'email_verified_at' => now(),
        ]);
        $ventas->assignRole('ventas');

        $produccion = User::create([
            'name' => 'Carlos Panadero',
            'email' => 'produccion@panisandco.com',
            'password' => Hash::make('password'),
            'phone' => '+51 999 555 666',
            'terms_accepted' => true,
            'email_verified_at' => now(),
        ]);
        $produccion->assignRole('produccion');

        $cliente = User::create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@panisandco.com',
            'password' => Hash::make('password'),
            'phone' => '+51 999 777 888',
            'address' => 'Av. Principal 123, Lima',
            'terms_accepted' => true,
            'email_verified_at' => now(),
        ]);
        $cliente->assignRole('cliente');

        $categories = [
            ['name' => 'Panes', 'slug' => 'panes', 'description' => 'Panes artesanales horneados diariamente'],
            ['name' => 'Pasteles', 'slug' => 'pasteles', 'description' => 'Pasteles finos y deliciosos'],
            ['name' => 'Tortas', 'slug' => 'tortas', 'description' => 'Tortas personalizadas para ocasiones especiales'],
            ['name' => 'Galletas', 'slug' => 'galletas', 'description' => 'Galletas artesanales crujientes'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $products = [
            ['category_id' => 1, 'name' => 'Pan de Campo', 'slug' => 'pan-de-campo', 'description' => 'Pan rústico con corteza crujiente y miga suave, elaborado con harina integral.', 'ingredients_text' => 'Harina integral, agua, levadura, sal', 'price' => 8.50, 'stock' => 30, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Baguette Tradicional', 'slug' => 'baguette-tradicional', 'description' => 'Clásica baguette francesa con textura crujiente por fuera y suave por dentro.', 'ingredients_text' => 'Harina de trigo, agua, levadura, sal', 'price' => 6.00, 'stock' => 25, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Pan de Ajo', 'slug' => 'pan-de-ajo', 'description' => 'Pan artesanal con mantequilla de ajo y perejil.', 'ingredients_text' => 'Harina, mantequilla, ajo, perejil', 'price' => 10.00, 'stock' => 20],
            ['category_id' => 2, 'name' => 'Pastel de Chocolate', 'slug' => 'pastel-chocolate', 'description' => 'Pastel húmedo de chocolate belga con ganache.', 'ingredients_text' => 'Chocolate, harina, huevos, mantequilla', 'price' => 45.00, 'stock' => 10, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Cheesecake de Fresa', 'slug' => 'cheesecake-fresa', 'description' => 'Cremoso cheesecake con coulis de fresa natural.', 'ingredients_text' => 'Queso crema, fresas, galleta, azúcar', 'price' => 38.00, 'stock' => 8, 'is_featured' => true],
            ['category_id' => 3, 'name' => 'Torta de Bodas', 'slug' => 'torta-bodas', 'description' => 'Elegante torta de varios pisos para bodas.', 'ingredients_text' => 'Harina, azúcar, mantequilla, fondant', 'price' => 250.00, 'stock' => 5, 'is_customizable' => true, 'is_featured' => true],
            ['category_id' => 3, 'name' => 'Torta de Cumpleaños', 'slug' => 'torta-cumpleanos', 'description' => 'Torta personalizable para cumpleaños.', 'ingredients_text' => 'Harina, azúcar, mantequilla, crema', 'price' => 85.00, 'stock' => 10, 'is_customizable' => true],
            ['category_id' => 4, 'name' => 'Galletas de Avena', 'slug' => 'galletas-avena', 'description' => 'Galletas saludables con avena y pasas.', 'ingredients_text' => 'Avena, harina, pasas, mantequilla', 'price' => 12.00, 'stock' => 40],
            ['category_id' => 4, 'name' => 'Alfajores Artesanales', 'slug' => 'alfajores', 'description' => 'Alfajores rellenos de manjar blanco.', 'ingredients_text' => 'Harina, maicena, manjar, azúcar glass', 'price' => 15.00, 'stock' => 35, 'is_featured' => true],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $supplier = Supplier::create([
            'name' => 'Molinos del Sur S.A.C.',
            'contact_name' => 'Juan Pérez',
            'email' => 'ventas@molinossur.com',
            'phone' => '+51 01 456 7890',
            'address' => 'Av. Industrial 500, Lima',
        ]);

        Supplier::create([
            'name' => 'Lácteos San Martín',
            'contact_name' => 'Ana López',
            'email' => 'pedidos@lacteossm.com',
            'phone' => '+51 01 234 5678',
        ]);

        $ingredients = [
            ['name' => 'Harina de Trigo', 'unit' => 'kg', 'stock' => 100, 'min_stock' => 20, 'unit_cost' => 3.50, 'supplier_id' => 1],
            ['name' => 'Harina Integral', 'unit' => 'kg', 'stock' => 50, 'min_stock' => 10, 'unit_cost' => 4.20, 'supplier_id' => 1],
            ['name' => 'Mantequilla', 'unit' => 'kg', 'stock' => 30, 'min_stock' => 5, 'unit_cost' => 28.00, 'supplier_id' => 2],
            ['name' => 'Huevos', 'unit' => 'docena', 'stock' => 40, 'min_stock' => 10, 'unit_cost' => 8.50, 'supplier_id' => 2],
            ['name' => 'Azúcar', 'unit' => 'kg', 'stock' => 60, 'min_stock' => 15, 'unit_cost' => 3.80, 'supplier_id' => 1],
            ['name' => 'Chocolate', 'unit' => 'kg', 'stock' => 8, 'min_stock' => 5, 'unit_cost' => 45.00, 'supplier_id' => 1],
            ['name' => 'Levadura', 'unit' => 'kg', 'stock' => 5, 'min_stock' => 2, 'unit_cost' => 15.00, 'supplier_id' => 1],
        ];

        foreach ($ingredients as $ing) {
            Ingredient::create($ing);
        }

        $recipe = Recipe::create([
            'product_id' => 1,
            'instructions' => '1. Mezclar harina, agua, levadura y sal. 2. Amasar 15 minutos. 3. Fermentar 2 horas. 4. Hornear a 220°C por 35 minutos.',
            'prep_time_minutes' => 180,
        ]);

        RecipeIngredient::create(['recipe_id' => $recipe->id, 'ingredient_id' => 2, 'quantity' => 0.5, 'unit' => 'kg']);
        RecipeIngredient::create(['recipe_id' => $recipe->id, 'ingredient_id' => 7, 'quantity' => 0.02, 'unit' => 'kg']);
        RecipeIngredient::create(['recipe_id' => $recipe->id, 'ingredient_id' => 5, 'quantity' => 0.01, 'unit' => 'kg']);
    }
}
