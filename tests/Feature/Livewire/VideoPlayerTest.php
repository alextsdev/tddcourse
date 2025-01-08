<?php


use App\Http\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('shows details for given video', function () {
  // Arrange
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

  // Act & Assert
    $video = $course->videos->first();
    Livewire::test(VideoPlayer::class, ['video' => $course->videos->first()])
        ->assertSeeText([
            $video->title,
            $video->description,
            "({$video->duration_in_min}min)"
        ]);

});

it('shows given video', function () {
    // Arrange
        $course = Course::factory()
            ->has(Video::factory())
            ->create();

    // Act & Assert
    $video = $course->videos->first();
        Livewire::test(VideoPlayer::class, ['video' => $video])
            ->assertSeeHtml('<iframe src="https://player.vimeo.com/video/'. $video->vimeo_id . '"');
});

it('shows list of all course videos', function () {
    // Arrange
        $course = Course::factory()
            ->has(Video::factory()
                ->count(3)
                ->state(new Sequence(
                    ['title' => 'First Video'],
                    ['title' => 'Second Video'],
                    ['title' => 'Third Video'],
                ))
            )
            ->create();

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertSeeTextInOrder([
            'First Video',
            'Second Video',
            'Third Video',
        ])->assertSeeHtml([
            route('pages.course-videos', Video::where('title', 'First Video')->first()),
            route('pages.course-videos', Video::where('title', 'Second Video')->first()),
            route('pages.course-videos', Video::where('title', 'Third Video')->first()),
        ]);
});

it('marks video as completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()
        ->has(Video::factory()->state(['title' => 'Course video']))
        ->create();

    $user->courses()->attach($course);

    // Asssert
   expect($user->videos)->toHaveCount(0);

    // Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, ['video' => $course->videos->first()])
        ->call('markVideoAsCompleted');

    // Assert
    $user->refresh();
    expect($user->videos)
        ->toHaveCount(1)
        ->first()->title->toEqual('Course video');
});

it('marks video as not completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()
        ->has(Video::factory()->state(['title' => 'Course video']))
        ->create();

    $user->courses()->attach($course);
    $user->videos()->attach($course->videos->first());

    // Asssert
    expect($user->videos)->toHaveCount(1);

    // Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, ['video' => $course->videos->first()])
        ->call('markVideoAsNotCompleted');

    // Assert
    $user->refresh();
    expect($user->videos)
        ->toHaveCount(0);
});
