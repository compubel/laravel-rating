<?php

namespace Compubel\Rating\Test;

use BadMethodCallException;
use Compubel\Rating\Exceptions\ModelNotRateable;
use Compubel\Rating\Exceptions\RatingAlreadyExists;
use Compubel\Rating\Test\Models\User;

class RatingTest extends TestCase
{
    /**
     * Information about the tests
     * - Users can rate posts, but not other users
     * - Posts can be rated
     * - Members can rate other members.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_rate_a_post()
    {
        $user = $this->createUser();
        $post = $this->createPost();

        $this->assertTrue($user->rate($post, 10.00));
        $this->assertTrue($user->hasRated($post));
    }

    public function test_users_can_rate_a_post()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $post = $this->createPost();

        $this->assertTrue($user1->rate($post, 10.00));
        $this->assertTrue($user1->hasRated($post));

        $this->assertTrue($user2->rate($post, 10.00));
        $this->assertTrue($user2->hasRated($post));
    }

    public function test_users_can_rerate_a_post()
    {
        $user = $this->createUser();
        $post = $this->createPost();

        $this->assertTrue($user->rate($post, 10.00));
        $this->assertTrue($user->hasRated($post));
        $this->assertEquals(10, $post->averageRating(User::class));

        $this->assertTrue($user->rate($post, 9.00));
        $this->assertTrue($user->hasRated($post));
        $this->assertEquals(9, $post->averageRating(User::class));
    }

    public function test_users_can_not_rerate_a_post()
    {
        $user = $this->createUser();
        $post = $this->createPost();

        $this->assertTrue($user->rate($post, 10.00, false));
        $this->assertTrue($user->hasRated($post));
        $this->assertEquals(10, $post->averageRating(User::class));

        $this->expectException(RatingAlreadyExists::class);
        $user->rate($post, 9.00, false);
        $this->assertTrue($user->hasRated($post));
        $this->assertEquals(10, $post->averageRating(User::class));
    }

    public function test_it_can_count_ratings()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $user3 = $this->createUser();
        $post = $this->createPost();

        $user1->rate($post, 10.00);
        $this->assertEquals(1, $post->countRatings(User::class));

        $user2->rate($post, 10.00);
        $this->assertEquals(2, $post->countRatings(User::class));

        $user3->rate($post, 10.00);
        $this->assertEquals(3, $post->countRatings(User::class));
    }

    public function test_no_rating_returns_count_of_zero()
    {
        $post = $this->createPost();

        $this->assertEquals(0, $post->countRatings(User::class));
    }

    public function test_it_can_calculate_the_average_rating()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $user3 = $this->createUser();
        $post = $this->createPost();

        $user1->rate($post, 8.00);
        $this->assertEquals(8.00, $post->averageRating(User::class));

        $user2->rate($post, 10.00);
        $this->assertEquals(9.00, $post->averageRating(User::class));

        $user3->rate($post, 7.50);
        $this->assertEquals(8.5, $post->averageRating(User::class));
    }

    public function test_no_rating_returns_average_of_zero()
    {
        $post = $this->createPost();

        $this->assertEquals(0.00, $post->averageRating(User::class));
    }

    public function test_user_can_unrate_a_post()
    {
        $user = $this->createUser();
        $post = $this->createPost();

        $user->rate($post, 10.00);
        $this->assertTrue($user->unrate($post));
    }

    public function test_user_can_not_rate_a_user()
    {
        $user = $this->createUser();

        $this->expectException(ModelNotRateable::class);
        $user->rate($user, 10.00);
    }

    public function test_post_can_not_rate_a_user()
    {
        $post = $this->createPost();
        $user = $this->createUser();

        $this->expectException(BadMethodCallException::class);
        $post->rate($user, 10.00);
    }

    public function test_member_can_rate_a_member()
    {
        $member = $this->createMember();

        $this->assertTrue($member->rate($member, 10.00));
    }
}
