<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;
use function Pest\Laravel\get;

it('cannot be accessed by guest', function () {
    // Act & Assert
    get(route('pages.dashboard'))
        ->assertRedirect(route('login'));
});

it('lists purchases courses', function () {
    // Arrange
    $user = User::factory()
        ->has(Course::factory()->count(2)->state (
            new Sequence(
                ['title' => 'Course A'],
                ['title' => 'Course B'],
        )))
        ->create();

    // Act & Assert
    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText([
            'Course A',
            'Course B',
        ]);
});

it('does not list other courses', function () {
    // Arrange
    $course = Course::factory()->create();

    // Act & Assert
    loginAsUser();
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertDontSeeText($course->title);
});

it('shows latest  purchased courses first', function () {
    // Arrange
    $user = User::factory()->create();
    $firstPurchaseCourse = Course::factory()->create();
    $secondPurchaseCourse = Course::factory()->create();

    $user->courses()->attach($firstPurchaseCourse, ['created_at' => Carbon::yesterday()]);
    $user->courses()->attach($secondPurchaseCourse, ['created_at' => Carbon::now()]);

    // Act & Assert
    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeTextInOrder([
            $secondPurchaseCourse->title,
            $firstPurchaseCourse->title,
        ]);
});

it('includes link to product videos', function () {
    // Arrange
    $user = User::factory()
        ->has(Course::factory())
        ->create();

    // Act & Assert
    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Watch videos')
        ->assertSee(route('pages.course-videos', Course::first()));
});
