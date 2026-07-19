<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the feedback page renders.
     */
    public function test_feedback_page_can_be_rendered(): void
    {
        $response = $this->get(route('feedback.index'));

        $response->assertStatus(200);
        $response->assertSee('আপনার প্রতিক্রিয়া জানান');
    }

    /**
     * Test successful feedback submission.
     */
    public function test_feedback_can_be_submitted_successfully(): void
    {
        $feedbackData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'designation' => 'Developer',
            'rating' => 5,
            'content' => 'এটি একটি চমৎকার ওয়েবসাইট এবং সমাজ সংস্কারমূলক কাজ।',
        ];

        $response = $this->post(route('feedback.submit'), $feedbackData);

        // Assert it redirects back
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Assert database has the unapproved testimonial
        $this->assertDatabaseHas('testimonials', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'designation' => 'Developer',
            'rating' => 5,
            'is_active' => false,
        ]);
    }

    /**
     * Test feedback submission validation.
     */
    public function test_feedback_submission_fails_with_invalid_data(): void
    {
        $invalidData = [
            'name' => '', // Required
            'email' => 'not-an-email', // Invalid email
            'designation' => '', // Required
            'rating' => 10, // Must be between 1 and 5
            'content' => 'short', // Should be valid
        ];

        $response = $this->post(route('feedback.submit'), $invalidData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'designation', 'rating']);
    }
}
