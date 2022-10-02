<?php

namespace Tests\Feature;

use App\Models\Coach;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExerciseTest extends TestCase
{
    use RefreshDatabase;

    public function testGetExercise()
    {
        $coach = factory(Coach::class)->create();
        $exercise = factory(Exercise::class)->create(['coach_id' => $coach->id]);
        $response = $this->actingAs($coach->user, 'api')->getJson('api/exercises/1');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
        ]);
    }
}
