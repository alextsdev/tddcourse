<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows course details', function () {
    // Arrange
    $course = Course::factory()->create([
        'tagline' => 'Course tagline',
        'image' => 'image.png',
        'learnings' => [
            'Learn Laravel routes',
            'Learn Laravel views',
            'Learn Laravel commands',
        ]
    ]);

    // Act
    get(route('course-details', $course))
        ->assertOk()
        ->assertSee([
            $course->title,
            $course->description,
            'Course tagline',
            'Learn Laravel routes',
            'Learn Laravel views',
            'Learn Laravel commands',
        ])
        ->assertSee('image.png');

    // Assert

});

it('shows course video count', function() {
    //$this->withoutExceptionHandling();
    // Arrange
    $course = Course::factory()->create();
    Video::factory()->count(3)->create(['course_id' => $course->id]);

    // Act  // Assert
    get(route('course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');

});
