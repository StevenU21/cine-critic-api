<?php

namespace Tests\Feature;

use App\Models\Director;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use App\Notifications\CreatedMovieNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    public function test_movie_title_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        unset($movieData['title']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_title_must_be_at_least_6_characters()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['title'] = 'test';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_title_must_not_be_greater_than_60_characters()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['title'] = str_repeat('a', 61);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_title_is_unique()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory()->create(['title' => 'test']);

        $movieData = Movie::factory()->make(['title' => 'test'])->toArray();

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_description_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        unset($movieData['description']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_description_must_be_at_least_10_characters()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['description'] = 'test';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_description_must_not_be_greater_than_1000_characters()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['description'] = str_repeat('a', 1001);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_cover_image_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        unset($movieData['cover_image']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_cover_image_must_be_an_image_file()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = 'test';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_cover_image_must_be_a_file_of_type_jpeg_png_jpg_gif_svg()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->create('test.pdf');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_cover_image_must_not_be_greater_than_2048_kilobytes()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg')->size(3000);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_release_date_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        unset($movieData['release_date']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_release_date_must_be_a_date_before_today()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['release_date'] = now()->addDay()->format('d-m-Y');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_release_date_must_be_in_the_format_Y_m_d()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['release_date'] = now()->format('d-m-Y');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_trailer_url_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        unset($movieData['trailer_url']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_trailer_url_must_be_a_valid_url()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['trailer_url'] = 'test';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_duration_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        unset($movieData['duration']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_movie_duration_must_be_an_integer()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['duration'] = 'test';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(302);
    }

    public function test_admin_user_can_view_movie_list()
    {
        Movie::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies');

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_view_movie_list()
    {
        Movie::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies');

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_view_movie_list()
    {
        Movie::factory(10)->create();

        $user = User::factory()->create();

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies');

        $response->assertStatus(200);
    }

    public function test_admin_user_can_show_specify_movie()
    {
        $movie = Movie::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_moderator_user_can_show_specify_movie()
    {
        $movie = Movie::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_user_can_show_specify_movie()
    {
        $movie = Movie::factory()->create();

        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/movies/$movie->id");

        $response->assertStatus(200);
    }

    public function test_guest_user_can_not_view_movie_list()
    {
        $response = $this->get('/api/movies');

        $response->assertStatus(401);
    }

    public function test_guest_user_can_not_show_specify_movie()
    {
        $movie = Movie::factory()->create();

        $response = $this->get("/api/movies/$movie->id");

        $response->assertStatus(401);
    }

    public function test_admin_user_can_filter_movies_by_director()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $director = Director::factory()->create();

        Movie::factory(5)->create(['director_id' => $director->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?director=' . $director->id);

        $response->assertStatus(200);
    }

    public function test_admin_user_can_filter_movies_by_genre()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $genre = Genre::factory()->create();

        Movie::factory(5)->create(['genre_id' => $genre->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?genre=' . $genre->id);

        $response->assertStatus(200);
    }

    public function test_admin_user_can_filter_movies_by_years()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $years = [2021, 2020, 2019, 2018, 2017];

        foreach ($years as $year) {
            Movie::factory()->create(['release_date' => now()->setYear($year)]);
        }

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?year=' . $years[0]);

        $response->assertStatus(200);
    }

    public function test_admin_user_can_filter_movies_by_director_and_genre_and_years()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $director = Director::factory()->create();
        $genre = Genre::factory()->create();

        Movie::factory(5)->create(['director_id' => $director->id, 'genre_id' => $genre->id]);

        $years = [2021, 2020, 2019, 2018, 2017];

        foreach ($years as $year) {
            Movie::factory()->create(['release_date' => now()->year($year), 'director_id' => $director->id, 'genre_id' => $genre->id]);
        }

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?director=' . $director->id . '&genre=' . $genre->id . '&year=' . $years[0]);

        $response->assertStatus(200);
    }

    public function test_admin_user_can_sort_movies_by_title_asc_and_desc()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $movies = Movie::factory(5)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=title');

        $response->assertStatus(200);

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=-title');

        $response->assertStatus(200);
    }

    public function test_admin_user_can_sort_movies_by_release_date_asc_and_desc()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $movies = Movie::factory(5)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=release_date');

        $response->assertStatus(200);

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=-release_date');

        $response->assertStatus(200);
    }

    public function test_admin_user_can_sort_movies_by_duration_asc_and_desc()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $movies = Movie::factory(5)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=duration');

        $response->assertStatus(200);

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=-duration');

        $response->assertStatus(200);
    }

    public function test_admin_user_can_sort_movies_by_rating_average_asc_and_desc()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $movies = Movie::factory(5)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=rating_average');

        $response->assertStatus(200);

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=-rating_average');

        $response->assertStatus(200);
    }

    public function test_admin_user_can_sort_movies_by_reviews_count_asc_and_desc()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $movies = Movie::factory(5)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=reviews_count');

        $response->assertStatus(200);

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies?sort=-reviews_count');

        $response->assertStatus(200);
    }

    public function test_admin_user_can_search_movies_by_title()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $movies = Movie::factory(5)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies/search?search=' . $movies[0]->title);

        $response->assertStatus(200);
    }

    public function test_admin_user_can_autocomplete_movies_by_title()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        Movie::factory(5)->create();

        $movies = Movie::factory(5)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/movies/search/auto-complete?search=' . $movies[0]->title);

        $response->assertStatus(200);
    }

    public function test_admin_can_create_movie()
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(201);
    }

    public function test_it_notifies_all_users_when_a_movie_is_created()
    {
        Notification::fake();

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $token = $admin->createToken('auth_token')->plainTextToken;

        $users = User::factory()->count(3)->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(201);

        Notification::assertSentTo($users, CreatedMovieNotification::class);
    }

    public function test_moderator_cant_create_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(403);
    }

    public function test_reviewer_cant_create_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/movies', $movieData);

        $response->assertStatus(403);
    }

    public function test_guest_user_cant_create_movie()
    {
        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->post('/api/movies', $movieData);

        $response->assertStatus(401);
    }

    public function test_admin_can_update_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/movies/$movie->id", $movieData);

        $response->assertStatus(200);
    }

    public function test_moderator_can_update_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/movies/$movie->id", $movieData);

        $response->assertStatus(200);
    }

    public function test_reviewer_cant_update_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/movies/$movie->id", $movieData);

        $response->assertStatus(403);
    }

    public function test_guest_user_cant_update_movie()
    {
        $movie = Movie::factory()->create();

        $movieData = Movie::factory()->make()->toArray();

        $movieData['cover_image'] = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->put("/api/movies/$movie->id", $movieData);

        $response->assertStatus(401);
    }

    public function test_admin_can_delete_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/movies/$movie->id");

        $response->assertStatus(204);
    }

    public function test_moderator_cant_delete_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/movies/$movie->id");

        $response->assertStatus(403);
    }

    public function test_reviewer_cant_delete_movie()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $movie = Movie::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/movies/$movie->id");

        $response->assertStatus(403);
    }

    public function test_guest_user_cant_delete_movie()
    {
        $movie = Movie::factory()->create();

        $response = $this->delete("/api/movies/$movie->id");

        $response->assertStatus(401);
    }
}
