<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 Admin user (as specified in doc: admin@lostfound.test / password)
        $admin = User::firstOrCreate(
            ['email' => 'admin@lostfound.test'],
            [
                'name' => 'Campus Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 3 Regular users for testing interactions
        $user1 = User::firstOrCreate(
            ['email' => 'budi@lostfound.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'siti@lostfound.test'],
            [
                'name' => 'Siti Rahma',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'andi@lostfound.test'],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $users = [$user1, $user2, $user3];

        // 6 Categories from PRD
        $categoryNames = [
            'Electronics',
            'Wallets & Cards',
            'Keys',
            'Bags',
            'Books & Stationery',
            'Others',
        ];

        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[$name] = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        // Realistic seed items
        $itemsData = [
            [
                'user' => $user1,
                'category' => 'Electronics',
                'type' => 'found',
                'title' => 'AirPods Pro 2 with Matte Black Case',
                'description' => 'Found near the center table in the main library 2nd floor reading room. The case has a small white scratch near the lightning port.',
                'location' => 'Central Library 2nd Floor',
                'event_date' => now()->subDays(1)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user2,
                'category' => 'Wallets & Cards',
                'type' => 'lost',
                'title' => 'Brown Leather Fossil Wallet with Student KTM',
                'description' => 'Lost my brown bi-fold wallet containing ITS Student ID (KTM) under name Siti Rahma, BNI ATM card, and driving license (SIM C).',
                'location' => 'Campus Canteen Tower 1',
                'event_date' => now()->subDays(2)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user3,
                'category' => 'Keys',
                'type' => 'found',
                'title' => 'Honda Vario Key with Batman Carabiner',
                'description' => 'Found hanging on the fence of Motorcycle Parking Area B near Informatics Department. Has a black metallic Batman logo keychain.',
                'location' => 'Informatics Motorcycle Parking B',
                'event_date' => now()->subDays(3)->toDateString(),
                'status' => 'claimed',
            ],
            [
                'user' => $user1,
                'category' => 'Bags',
                'type' => 'lost',
                'title' => 'Navy Blue Herschel Classic Backpack',
                'description' => 'Left my backpack on bench outside Classroom 302 after the Operating Systems lecture. Contains notebook and pencil case.',
                'location' => 'Classroom 302 Corridor',
                'event_date' => now()->subDays(4)->toDateString(),
                'status' => 'returned',
            ],
            [
                'user' => $user2,
                'category' => 'Books & Stationery',
                'type' => 'found',
                'title' => 'Casio Scientific Calculator fx-991EX ClassWiz',
                'description' => 'Found under chair 18 in Auditorium Hall during the Calculus midterm exam session.',
                'location' => 'Main Campus Auditorium Hall',
                'event_date' => now()->subDays(2)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user3,
                'category' => 'Others',
                'type' => 'found',
                'title' => 'Hydro Flask 24oz Cobalt Blue Tumbler',
                'description' => 'Found on the bleachers after basketball practice. Has multiple stickers including GitHub octocat and React logo.',
                'location' => 'Sports Center Indoor Court',
                'event_date' => now()->subDays(1)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user1,
                'category' => 'Electronics',
                'type' => 'lost',
                'title' => 'Logitech MX Master 3S Mouse (Space Gray)',
                'description' => 'Accidentally left my wireless mouse plugged into Lab PC #12 in Computer Graphics Laboratory.',
                'location' => 'Computer Graphics Lab (Lab 305)',
                'event_date' => now()->subDays(3)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user2,
                'category' => 'Wallets & Cards',
                'type' => 'found',
                'title' => 'Flazz BCA Card with Spider-Man Custom Skin',
                'description' => 'Found next to the self-service printing kiosk in Student Center lobby.',
                'location' => 'Student Center 1st Floor',
                'event_date' => now()->subDays(1)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user3,
                'category' => 'Keys',
                'type' => 'lost',
                'title' => 'Boarding House (Kos) Key with Brass Tag #104',
                'description' => 'Lost brass key with green acrylic tag marked "KOS 104" somewhere between faculty gate and gazebo.',
                'location' => 'Faculty Main Gate to Gazebo Pathway',
                'event_date' => now()->subDays(4)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user1,
                'category' => 'Books & Stationery',
                'type' => 'lost',
                'title' => 'Hardcover Spiral Notebook - Algorithms and Data Structures',
                'description' => 'Black spiral notebook with handwritten lecture notes on Dynamic Programming and Graphs. Critical for upcoming finals!',
                'location' => 'Department Library Room A',
                'event_date' => now()->subDays(5)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user2,
                'category' => 'Electronics',
                'type' => 'found',
                'title' => 'Anker 65W GaN Fast Charger & USB-C Cable',
                'description' => 'Left plugged into wall outlet behind bench #4 at Central Gazebo.',
                'location' => 'Central Campus Gazebo',
                'event_date' => now()->subDays(2)->toDateString(),
                'status' => 'claimed',
            ],
            [
                'user' => $user3,
                'category' => 'Bags',
                'type' => 'found',
                'title' => 'Canvas Tote Bag - "Stay Curious" Print',
                'description' => 'Cream canvas tote containing an umbrella, wet wipes, and an empty Tupperware container.',
                'location' => 'Building C Ground Floor Benches',
                'event_date' => now()->subDays(3)->toDateString(),
                'status' => 'returned',
            ],
            [
                'user' => $user1,
                'category' => 'Others',
                'type' => 'lost',
                'title' => 'Ray-Ban Aviator Prescription Sunglasses',
                'description' => 'Black metal frame prescription sunglasses inside a black leather case. High prescription lenses.',
                'location' => 'Faculty Mosque Prayer Hall',
                'event_date' => now()->subDays(6)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user2,
                'category' => 'Electronics',
                'type' => 'lost',
                'title' => 'Apple Pencil (2nd Generation)',
                'description' => 'Lost in Drawing Studio 2 during UI/UX prototyping workshop. Engraved with initials "SR".',
                'location' => 'Multimedia Design Studio 2',
                'event_date' => now()->subDays(1)->toDateString(),
                'status' => 'open',
            ],
            [
                'user' => $user3,
                'category' => 'Wallets & Cards',
                'type' => 'found',
                'title' => 'Cardholder with Mandiri E-Money & TransJakarta Card',
                'description' => 'Black minimalist cardholder found on the campus shuttle bus seat (Bus Route 2).',
                'location' => 'Campus Shuttle Bus #2',
                'event_date' => now()->subDays(2)->toDateString(),
                'status' => 'open',
            ],
        ];

        foreach ($itemsData as $data) {
            $item = Item::create([
                'user_id' => $data['user']->id,
                'category_id' => $categories[$data['category']]->id,
                'type' => $data['type'],
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'event_date' => $data['event_date'],
                'status' => $data['status'],
                'photo_path' => null,
            ]);

            // If item is claimed or returned, seed approved claim
            if ($data['status'] === 'claimed') {
                // Claimed by a different user
                $claimant = $data['user']->id === $user1->id ? $user2 : $user1;
                Claim::create([
                    'item_id' => $item->id,
                    'user_id' => $claimant->id,
                    'message' => 'Hello, this is my item! I can prove ownership with the exact serial number / custom keychain markings.',
                    'contact' => '+6281234567890 (WhatsApp)',
                    'status' => 'approved',
                ]);
            } elseif ($data['status'] === 'returned') {
                $claimant = $data['user']->id === $user3->id ? $user2 : $user3;
                Claim::create([
                    'item_id' => $item->id,
                    'user_id' => $claimant->id,
                    'message' => 'I have verified all the contents and met the finder in person. Thank you very much for keeping it safe!',
                    'contact' => 'line_id: student_its',
                    'status' => 'approved',
                ]);
            }
        }
    }
}
