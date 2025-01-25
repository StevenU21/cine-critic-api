<?php

namespace Tests\Feature;

use App\Models\Director;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;

class DirectorControllerTest extends TestCase
{
    public function test_director_name_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        unset($directorData['name']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_name_must_be_at_least_3_characters_long()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['name'] = 'ab';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_name_must_be_at_most_30_characters_long()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['name'] = 'aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_biography_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        unset($directorData['biography']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_biography_must_be_at_least_3_characters_long()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['biography'] = 'ab';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_biography_must_be_at_most_2000_characters_long()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['biography'] = 'aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz
        aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_birth_date_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        unset($directorData['birth_date']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_birth_date_must_be_a_date_before_today_date()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['birth_date'] = '01-01-2022';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_birth_date_must_be_a_date_after_01_01_1890_date()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['birth_date'] = '01-01-1889';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_birth_date_must_be_in_d_m_Y_format()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['birth_date'] = '2022-01-01';

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_image_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        unset($directorData['image']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_image_must_be_an_image_file_of_type_jpeg_png_jpg_gif_svg_webp_and_max_4096_kb_and_dimensions_1000x1500_pixels()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['image'] = UploadedFile::fake()->create('document.pdf');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_director_nationality_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        unset($directorData['nationality']);

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }
    public function test_admin_can_view_directors()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/directors');

        $response->assertStatus(200);
    }

    public function test_moderator_can_view_directors()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/directors');

        $response->assertStatus(200);
    }

    public function test_reviewer_can_view_directors()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->get('/api/directors');

        $response->assertStatus(200);
    }

    public function test_admin_can_show_director()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/directors/$director->id");

        $response->assertStatus(200);
    }

    public function test_moderator_can_show_director()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/directors/$director->id");

        $response->assertStatus(200);
    }

    public function test_reviewer_can_show_director()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->get("/api/directors/$director->id");

        $response->assertStatus(200);
    }

    public function test_admin_can_create_director()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['image'] = UploadedFile::fake()->image('product.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(201);
    }

    public function test_moderator_cant_create_director()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['image'] = UploadedFile::fake()->image('product.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(403);
    }

    public function test_reviewer_cant_create_director()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $directorData['image'] = UploadedFile::fake()->image('product.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(403);
    }

    public function test_directors_image_is_required()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $directorData = Director::factory()->make()->toArray();

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/directors', $directorData);

        $response->assertStatus(302);
    }

    public function test_admin_can_update_director()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $directorData = Director::factory()->make()->toArray();

        $directorData['image'] = UploadedFile::fake()->image('product.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/directors/$director->id", $directorData);

        $response->assertStatus(200);
    }

    public function test_moderator_can_update_director()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $directorData = Director::factory()->make()->toArray();

        $directorData['image'] = UploadedFile::fake()->image('product.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/directors/$director->id", $directorData);

        $response->assertStatus(200);
    }

    public function test_reviewer_cant_update_director()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $directorData = Director::factory()->make()->toArray();

        $directorData['image'] = UploadedFile::fake()->image('product.jpg');

        $response = $this->withHeader('Authorization', "Bearer $token")->put("/api/directors/$director->id", $directorData);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_director()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/directors/$director->id");

        $response->assertStatus(200);
    }

    public function test_moderator_cant_delete_director()
    {
        $user = User::factory()->create();
        $user->assignRole('moderator');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/directors/$director->id");

        $response->assertStatus(403);
    }

    public function test_reviewer_cant_delete_director()
    {
        $user = User::factory()->create();
        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        $director = Director::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")->delete("/api/directors/$director->id");

        $response->assertStatus(403);
    }
}
