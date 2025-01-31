<?php

use App\Models\Course;
use App\Models\Video;
use function Pest\Laravel\get;

it('does not find unreleased course', function() {
    // Arrange
    $course = Course::factory()->create();

    // Act  // Assert
    get(route('pages.course-details', $course))
        ->assertNotFound();
});

it('shows course details', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act   // Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSee([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/{$course->image_name}"));
});

it('shows course video count', function() {
    //$this->withoutExceptionHandling();
    // Arrange
    $course = Course::factory()
        ->released()
        ->has(Video::factory()->count(3))
        ->create();

    // Act  // Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');

});
