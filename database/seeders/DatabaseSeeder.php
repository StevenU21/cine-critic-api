<?php

namespace Database\Seeders;

use App\Models\Director;
use App\Models\Movie;
use App\Models\Rating;
use App\Models\User;
use App\Models\Genre;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $AdminUser->assignRole('admin');

        $ModeratorUser = User::factory()->create([
            'name' => 'Moderator',
            'email' => 'moderator@example.com',
            'password' => bcrypt('password'),
        ]);
        $ModeratorUser->assignRole('moderator');

        $ReviewerUser = User::factory()->create([
            'name' => 'Reviewer',
            'email' => 'reviewer@example.com',
            'password' => bcrypt('password'),
        ]);
        $ReviewerUser->assignRole('reviewer');

        User::factory(200)->create();

        foreach (User::all() as $user) {
            $role = ['reviewer', 'moderator'][array_rand(['reviewer', 'moderator'])];
            $user->assignRole($role);
        }

        Genre::factory(20)->create();
        Director::factory(40)->create();
        Movie::factory(500)->create();

        $reviewers = User::role('reviewer')->get();

        for ($i = 0; $i < 1000; $i++) {
            $reviewer = $reviewers->random();
            Rating::factory()->create(['user_id' => $reviewer->id]);
        }
    }
}
