<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('gives back successful response for home page', function () {
    get(route('home'))
        ->assertOk();
});

it('gives back succesfull response for course details page', function(){
   // Arrange
    $course = Course::factory()->create();

    // Act
    get(route('course-details', $course))
        ->assertOk();
});
