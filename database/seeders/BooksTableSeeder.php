<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BooksTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('books')->delete();
        $books = [];

        for ($i = 1; $i <= 10; $i++) {
            $books[] = [
                'title' => 'Book ' . $i,
                'author' => 'Author ' . $i,
                'genre' => 'Fiction',
                'price' => rand(10, 50) + 0.99,
                'quantity_available' => rand(20, 100),
                'image' => '0BeS5U5pkZGhS0kSX4a7OQ49yJJR1yjvqsDUqysx.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($books as $book) {
            $imagePath = $this->storeImage($book['image']);
            DB::table('books')->insert([
                'title' => $book['title'],
                'author' => $book['author'],
                'genre' => $book['genre'],
                'price' => $book['price'],
                'quantity_available' => $book['quantity_available'],
                'image' => $imagePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function storeImage($imageName)
    {
        $imagePath = 'images/' . $imageName;

        Storage::put($imagePath, file_get_contents(storage_path('app/public/' . $imageName)));

        return $imagePath;
    }
}

