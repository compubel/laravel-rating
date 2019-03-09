<?php

namespace Compubel\Rating\Test;

use BadMethodCallException;
use Compubel\Rating\Test\Models\Post;
use Compubel\Rating\Test\Models\User;
use Compubel\Rating\Test\Models\Member;
use Compubel\Rating\Exceptions\ModelNotRateable;
use Compubel\Rating\Exceptions\RatingAlreadyExists;

class RatingTest extends TestCase
{
    /**
     * Information about the tests
     * - Users can rate posts, but not other users
     * - Posts can be rated
     * - Members can rate other members.
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function user_can_rate_a_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->assertTrue($user->rate($post, 10.00));
        $this->assertTrue($user->hasRated($post));
    }

    /**
     * @test
     */
    public function users_can_rate_a_post()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->assertTrue($user1->rate($post, 10.00));
        $this->assertTrue($user1->hasRated($post));

        $this->assertTrue($user2->rate($post, 10.00));
        $this->assertTrue($user2->hasRated($post));
    }

    /**
     * @test
     */
    public function users_can_rerate_a_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->assertTrue($user->rate($post, 10.00));
        $this->assertTrue($user->hasRated($post));
        $this->assertEquals(10, $post->averageRating(User::class));

        $this->assertTrue($user->rate($post, 9.00));
        $this->assertTrue($user->hasRated($post));
        $this->assertEquals(9, $post->averageRating(User::class));
    }

    /**
     * @test
     */
    public function users_can_not_rerate_a_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->assertTrue($user->rate($post, 10.00, false));
        $this->assertTrue($user->hasRated($post));
        $this->assertEquals(10, $post->averageRating(User::class));

        $this->expectException(RatingAlreadyExists::class);
        $user->rate($post, 9.00, false);
        $this->assertTrue($user->hasRated($post));
        $this->assertEquals(10, $post->averageRating(User::class));
    }

    /**
     * @test
     */
    public function it_can_count_ratings()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $user1->rate($post, 10.00);
        $this->assertEquals(1, $post->countRatings(User::class));

        $user2->rate($post, 10.00);
        $this->assertEquals(2, $post->countRatings(User::class));

        $user3->rate($post, 10.00);
        $this->assertEquals(3, $post->countRatings(User::class));
    }

    /**
     * @test
     */
    public function no_rating_returns_count_of_zero()
    {
        $post = factory(Post::class)->create();

        $this->assertEquals(0, $post->countRatings(User::class));
    }

    /**
     * @test
     */
    public function it_can_calculate_the_average_rating()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $user1->rate($post, 8.00);
        $this->assertEquals(8.00, $post->averageRating(User::class));

        $user2->rate($post, 10.00);
        $this->assertEquals(9.00, $post->averageRating(User::class));

        $user3->rate($post, 7.50);
        $this->assertEquals(8.5, $post->averageRating(User::class));
    }

    /**
     * @test
     */
    public function no_rating_returns_average_of_zero()
    {
        $post = factory(Post::class)->create();

        $this->assertEquals(0.00, $post->averageRating(User::class));
    }

    /**
     * @test
     */
    public function user_can_unrate_a_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $user->rate($post, 10.00);
        $this->assertTrue($user->unrate($post));
    }

    /**
     * @test
     */
    public function user_can_not_rate_a_user()
    {
        $user = factory(User::class)->create();

        $this->expectException(ModelNotRateable::class);
        $user->rate($user, 10.00);
    }

    /**
     * @test
     */
    public function post_can_not_rate_a_user()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->expectException(BadMethodCallException::class);
        $post->rate($user, 10.00);
    }

    /**
     * @test
     */
    public function member_can_rate_a_member()
    {
        $member = factory(Member::class)->create();

        $this->assertTrue($member->rate($member, 10.00));
    }
}
