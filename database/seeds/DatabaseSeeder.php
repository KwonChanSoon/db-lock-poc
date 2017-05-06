<?php

use App\Product;
use App\Review;
use App\Support\KoreanLoremProvider;
use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('ko_KR');
        $koreanProvider = new KoreanLoremProvider($faker);
        $faker->addProvider($koreanProvider);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Product::truncate();
        Review::truncate();

        $admin = User::forceCreate([
            'name' => 'Admin',
            'email' => 'admin@foo.com',
            'password' => bcrypt('password'),
        ]);

        $user = User::forceCreate([
            'name' => 'User',
            'email' => 'user@foo.com',
            'password' => bcrypt('password'),
        ]);

        foreach (range(0, 10) as $index) {
            Product::forceCreate([
                'title' => $faker->korSentence(3),
                'stock' => rand(1, 5),
                'price' => rand(1, 5) * 1000,
                'description' => $faker->korParagraph(2),
            ]);
        }

        Product::all()->each(function (Product $product) use ($admin, $user, $faker) {
            $user = [$admin, $user][rand(0, 1)];

            Review::forceCreate([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'title' => $faker->korSentence(3),
                'content' => $faker->korParagraph(2),
            ]);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
