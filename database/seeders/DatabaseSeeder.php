<?php

namespace Database\Seeders;

use App\Models\Director;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $AdminUser = User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'image' => 'https://randomuser.me/api/portraits/men/68.jpg',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $AdminUser->assignRole('admin');

        $ModeratorUser = User::factory()->create([
            'name' => 'Moderator',
            'username' => 'moderator',
            'image' => 'https://randomuser.me/api/portraits/men/69.jpg',
            'email' => 'moderator@example.com',
            'password' => bcrypt('password'),
        ]);
        $ModeratorUser->assignRole('moderator');

        $ReviewerUser = User::factory()->create([
            'name' => 'Reviewer',
            'username' => 'reviewer',
            'image' => 'https://randomuser.me/api/portraits/men/70.jpg',
            'email' => 'reviewer@example.com',
            'password' => bcrypt('password'),
        ]);
        $ReviewerUser->assignRole('reviewer');

        User::factory(50)->create();

        foreach (User::all() as $user) {
            $role = ['reviewer', 'moderator'][array_rand(['reviewer', 'moderator'])];
            $user->assignRole($role);
        }

        Director::factory(20)->create();
        Genre::factory(10)->create();
        Movie::factory(100)->create();

        $reviewers = User::role('reviewer')->get();

        for ($i = 0; $i < 200; $i++) {
            $reviewer = $reviewers->random();
            Review::factory()->create(['user_id' => $reviewer->id]);
        }
    }
}
