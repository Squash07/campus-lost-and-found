<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Demo data that covers every feature and every status:
 *  - items that are open / claimed / returned, lost and found, with and without photos
 *  - claims that are pending / approved / rejected, so each user has something to review
 *  - an empty category the admin can delete, and a full one that is protected
 *
 * Run with: php artisan migrate:fresh --seed     (all passwords: "password")
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->copySeedPhotos();

        $admin = $this->user('Campus Admin', 'admin@lostfound.test', 'admin');
        $budi = $this->user('Budi Santoso', 'budi@lostfound.test');
        $siti = $this->user('Siti Rahma', 'siti@lostfound.test');
        $andi = $this->user('Andi Pratama', 'andi@lostfound.test');
        $dewi = $this->user('Dewi Lestari', 'dewi@lostfound.test');

        $cat = [];
        foreach (['Electronics', 'Wallets & Cards', 'Keys', 'Bags', 'Books & Stationery', 'Others', 'Umbrellas'] as $name) {
            // "Umbrellas" stays empty on purpose, so the admin can test deleting a category.
            $cat[$name] = Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        $items = [
            // ---- OPEN, with pending claims waiting for review --------------------------------
            [
                'user' => $budi, 'cat' => 'Electronics', 'type' => 'found', 'status' => 'open', 'days' => 1,
                'photo' => 'electronics-earbuds.jpg',
                'title' => 'AirPods Pro 2 in a matte black case',
                'description' => "Found on the centre table of the Central Library reading room, 2nd floor. Small white scratch near the charging port. I'm keeping it until the owner can describe what's engraved on the case.",
                'location' => 'Central Library, 2nd floor reading room',
                'claims' => [
                    [$siti, 'pending', 'They are mine! The case has "SR" engraved on the back and the left earbud tip is a slightly larger size than the right one.', 'WhatsApp 0812-1111-2222'],
                    [$andi, 'pending', 'I lost my AirPods in the library on Monday. Mine have a crack on the lid hinge and are paired to "Andi\'s iPhone".', 'LINE @andi.pratama'],
                ],
            ],
            [
                'user' => $siti, 'cat' => 'Wallets & Cards', 'type' => 'lost', 'status' => 'open', 'days' => 2,
                'photo' => 'wallet-brown.jpg',
                'title' => 'Brown leather Fossil wallet with my KTM',
                'description' => 'Bi-fold brown wallet with my student ID (KTM), a BNI ATM card and my SIM C. Probably dropped it around lunchtime.',
                'location' => 'Tower 1 canteen',
                'claims' => [
                    [$budi, 'pending', 'I think I found it! A brown wallet was on the chair next to the Padang food stall at around 12.30. I handed it to the canteen cashier.', 'WhatsApp 0813-3333-4444'],
                ],
            ],
            [
                'user' => $andi, 'cat' => 'Books & Stationery', 'type' => 'found', 'status' => 'open', 'days' => 2,
                'photo' => 'stationery-calculator.jpg',
                'title' => 'Casio fx-991EX ClassWiz calculator',
                'description' => 'Found under seat 18 in the Auditorium after the Calculus midterm. There is a name sticker on the back.',
                'location' => 'Main Auditorium, seat 18',
                'claims' => [
                    [$dewi, 'pending', 'This is mine. The sticker on the back says "Dewi L. - IF 2024" and the battery cover is held with clear tape.', 'WhatsApp 0857-5555-6666'],
                ],
            ],

            // ---- OPEN, no claims yet ------------------------------------------------------------
            [
                'user' => $andi, 'cat' => 'Others', 'type' => 'found', 'status' => 'open', 'days' => 1,
                'photo' => 'others-tumbler.jpg',
                'title' => 'Blue Hydro Flask tumbler with stickers',
                'description' => 'Found on the bleachers after basketball practice. Covered in stickers, including a GitHub Octocat.',
                'location' => 'Sports Center indoor court',
            ],
            [
                'user' => $budi, 'cat' => 'Electronics', 'type' => 'lost', 'status' => 'open', 'days' => 3,
                'photo' => 'electronics-mouse.jpg',
                'title' => 'Logitech MX Master 3S mouse',
                'description' => 'Left my wireless mouse plugged into PC #12 in the Computer Graphics Lab. The USB receiver was still attached.',
                'location' => 'Computer Graphics Lab (Lab 305)',
            ],
            [
                'user' => $siti, 'cat' => 'Wallets & Cards', 'type' => 'found', 'status' => 'open', 'days' => 1,
                'photo' => null,
                'title' => 'Flazz BCA card with a custom skin',
                'description' => 'Found next to the self-service printer in the Student Center lobby.',
                'location' => 'Student Center, 1st floor lobby',
            ],
            [
                'user' => $andi, 'cat' => 'Keys', 'type' => 'lost', 'status' => 'open', 'days' => 4,
                'photo' => 'keys.jpg',
                'title' => 'Boarding house key with green tag "KOS 104"',
                'description' => 'Brass key on a green acrylic tag. Lost somewhere between the faculty gate and the gazebo.',
                'location' => 'Faculty main gate to the gazebo path',
            ],
            [
                'user' => $budi, 'cat' => 'Books & Stationery', 'type' => 'lost', 'status' => 'open', 'days' => 5,
                'photo' => 'stationery-notebook.jpg',
                'title' => 'Black spiral notebook: Algorithms and Data Structures',
                'description' => 'Handwritten lecture notes on dynamic programming and graphs. I really need it for finals.',
                'location' => 'Department library, room A',
            ],
            [
                'user' => $dewi, 'cat' => 'Others', 'type' => 'lost', 'status' => 'open', 'days' => 6,
                'photo' => 'others-glasses.jpg',
                'title' => 'Ray-Ban prescription glasses in a black case',
                'description' => 'Black metal frame, high prescription lenses, inside a black leather case.',
                'location' => 'Faculty mosque, prayer hall',
            ],
            [
                'user' => $siti, 'cat' => 'Electronics', 'type' => 'lost', 'status' => 'open', 'days' => 1,
                'photo' => null,
                'title' => 'Apple Pencil (2nd generation)',
                'description' => 'Lost in Design Studio 2 during the UI/UX workshop. Engraved with "SR".',
                'location' => 'Multimedia Design Studio 2',
                'claims' => [
                    [$andi, 'rejected', 'I found a white stylus on a desk in Studio 2, but it might be a Logitech Crayon rather than an Apple Pencil.', 'LINE @andi.pratama'],
                ],
            ],

            // ---- CLAIMED: approved claim, waiting for "mark as returned" -----------------------
            [
                'user' => $andi, 'cat' => 'Keys', 'type' => 'found', 'status' => 'claimed', 'days' => 3,
                'photo' => null,
                'title' => 'Honda Vario key with a Batman keychain',
                'description' => 'Found hanging on the fence of motorcycle parking B near the Informatics building.',
                'location' => 'Informatics motorcycle parking B',
                'claims' => [
                    [$budi, 'approved', 'That is my motorbike key. The keychain is a black metal Batman logo and there is a small red remote fob too.', 'WhatsApp 0813-3333-4444'],
                    [$dewi, 'rejected', 'I lost a Honda key near the parking lot last week, could it be mine?', 'WhatsApp 0857-5555-6666'],
                ],
            ],
            [
                'user' => $siti, 'cat' => 'Electronics', 'type' => 'found', 'status' => 'claimed', 'days' => 2,
                'photo' => 'electronics-charger.jpg',
                'title' => 'Anker 65W charger with USB-C cable',
                'description' => 'Left plugged into the wall socket behind bench #4 at the central gazebo.',
                'location' => 'Central campus gazebo',
                'claims' => [
                    [$dewi, 'approved', 'Mine! The cable has a strip of yellow tape near the connector and the charger has a dent on one corner.', 'WhatsApp 0857-5555-6666'],
                ],
            ],

            // ---- RETURNED -----------------------------------------------------------------------
            [
                'user' => $budi, 'cat' => 'Bags', 'type' => 'lost', 'status' => 'returned', 'days' => 4,
                'photo' => 'bag-backpack.jpg',
                'title' => 'Navy Herschel backpack',
                'description' => 'Left on the bench outside classroom 302 after the Operating Systems lecture. Has a notebook and pencil case inside.',
                'location' => 'Classroom 302 corridor',
                'claims' => [
                    [$siti, 'approved', 'I found a navy Herschel backpack outside 302 and brought it to the security post on the ground floor.', 'WhatsApp 0812-1111-2222'],
                ],
            ],
            [
                'user' => $andi, 'cat' => 'Bags', 'type' => 'found', 'status' => 'returned', 'days' => 3,
                'photo' => 'bag-tote.jpg',
                'title' => 'Cream canvas tote bag, "Stay Curious" print',
                'description' => 'Found on the ground floor benches of Building C.',
                'location' => 'Building C, ground floor benches',
                'claims' => [
                    [$siti, 'approved', 'It is my tote. Inside there should be a folding umbrella, wet wipes and an empty Tupperware.', 'WhatsApp 0812-1111-2222'],
                ],
            ],
            [
                'user' => $dewi, 'cat' => 'Wallets & Cards', 'type' => 'found', 'status' => 'returned', 'days' => 2,
                'photo' => 'wallet-cardholder.jpg',
                'title' => 'Black cardholder with e-money and TransJakarta cards',
                'description' => 'Found on a seat of campus shuttle bus #2.',
                'location' => 'Campus shuttle bus #2',
                'claims' => [
                    [$budi, 'approved', 'That is mine, the Mandiri e-money card has my name printed on it: Budi Santoso.', 'WhatsApp 0813-3333-4444'],
                ],
            ],
        ];

        foreach ($items as $data) {
            $created = now()->subDays($data['days'])->setTime(rand(8, 17), rand(0, 59));

            $item = Item::create([
                'user_id' => $data['user']->id,
                'category_id' => $cat[$data['cat']]->id,
                'type' => $data['type'],
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'event_date' => $created->toDateString(),
                'photo_path' => $data['photo'] ? 'items/' . $data['photo'] : null,
                'status' => $data['status'],
            ]);
            $item->forceFill(['created_at' => $created, 'updated_at' => $created->copy()->addHours(20)])->saveQuietly();

            foreach ($data['claims'] ?? [] as $n => [$claimant, $status, $message, $contact]) {
                $claim = Claim::create([
                    'item_id' => $item->id,
                    'user_id' => $claimant->id,
                    'message' => $message,
                    'contact' => $contact,
                    'status' => $status,
                ]);
                $at = $created->copy()->addHours(2 + $n * 3);
                $claim->forceFill(['created_at' => $at, 'updated_at' => $at->copy()->addHours(4)])->saveQuietly();
            }
        }
    }

    private function user(string $name, string $email, string $role = 'user'): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make('password'), 'role' => $role, 'email_verified_at' => now()]
        );
    }

    /**
     * Item photos live in public/uploads (git-ignored), so copy the
     * bundled demo photos there whenever the database is seeded.
     */
    private function copySeedPhotos(): void
    {
        $target = public_path('uploads/items');
        File::ensureDirectoryExists($target);

        foreach (File::files(database_path('seeders/photos')) as $photo) {
            File::copy($photo->getPathname(), $target . '/' . $photo->getFilename());
        }
    }
}
