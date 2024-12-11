<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('gives back successful response for home page', function () {
    get(route('pages.home'))
        ->assertOk();
});

it('gives back succesfull response for course details page', function(){
   // Arrange
    $course = Course::factory()->released()->create();

    // Act
    get(route('pages.course-details', $course))
        ->assertOk();
});

it('gives back successful for dashboard page', function() {
    // Arrange
    $user = User::factory()->create();

    // Act  // Assert
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});
