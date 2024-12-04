<?php

use App\Models\Course;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows courses overview', function() {
    // Arrange
    Course::factory()->create(['title' => 'Course A', 'description' => 'Description A',
        'released_at' => Carbon::now() ]);
    Course::factory()->create(['title' => 'Course B', 'description' => 'Description B',
        'released_at' => Carbon::now() ]);
    Course::factory()->create(['title' => 'Course C', 'description' => 'Description C',
        'released_at' => Carbon::now() ]);

    // Act  // Assert
    get(route('home'))
    ->assertSeeText([
        'Course A',
        'Description A',
        'Course B',
        'Description B',
        'Course C',
        'Description C',
    ]);
});

it('shows only released courses', function () {
    // Arrange
    Course::factory()->create(['title' => 'Course A', 'released_at' => Carbon::yesterday()]);
    Course::factory()->create(['title' => 'Course B']);

    // Act  // Assert
    get(route('home'))
    ->assertSeeText([
        'Course A',
    ])
    ->assertDontSeeText([
        'Course B',
    ]);
});

it('shows courses by release date', function () {
    // Arrange
    Course::factory()->create(['title' => 'Course A', 'released_at' => Carbon::yesterday()]);
    Course::factory()->create(['title' => 'Course B', 'released_at' => Carbon::now()]);

    // Act
    get(route('home'))
        ->assertSeeInOrder([
            'Course B',
            'Course A',
        ]);

    // Assert
});
