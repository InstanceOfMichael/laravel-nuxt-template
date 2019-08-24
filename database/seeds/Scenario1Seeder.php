<?php

use Illuminate\Database\Seeder;

use App\Comment;
use App\User;

class Scenario1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE users, password_resets, oauth_providers, comments CASCADE;');

        User::firstOrCreate([
            'handle' => 'app',
            'name' => 'app',
            'email' => 'app@example.com',
        ]);
        User::firstOrCreate([
            'handle' => 'iom',
            'name' => 'iom',
            'email' => 'iom@codebro.org',
            'password' => bcrypt('Buffalo4ever!'),
        ]);

        $this->faker = app(\Faker\Generator::class);
        $this->faker->seed(get_class($this));

        $this->users = collect(
            $this->faker->randomElements(
                factory(User::class, 100)->create()->all(),
                50
            )
        );

        $comments = $this->randomUsers(50)->map(function (User $user) {
            return factory(Comment::class)->create([
                'op_id' => $user->id,
            ]);
        });

        $this->randomCommentTree($comments[0], 16);
        $this->randomCommentTree($comments[1], 12);
        $this->randomCommentTree($comments[2], 8);
        $this->randomCommentTree($comments[3], 6);
        $this->randomCommentTree($comments[4], 4);
        foreach(Comment::all() as $x) {
            $this->randomCommentTree($x, $this->faker->numberBetween(1, 5));
        }
    }

    protected function randomUsers(int $num) {
        return $this->randomElements('users', $num);
    }

    protected function randomCommentTree(object $parent, int $num) {
        // dump(get_class($parent).'_'.$parent->id.' '.$num);
        if ($parent instanceof Comment) {
            $this->randomUsers($num)->each(function (User $user) use ($parent, $num) {
                $comment = $parent->context->comments()->create(factory(Comment::class)->raw([
                    'op_id' => $user->id,
                    'pc_id' => $parent->id,
                ]));

                if (!$this->faker->numberBetween(0, 2)) {
                    $this->randomCommentTree($comment, $this->faker->numberBetween(0, $num - 1));
                }
            });
        } else {
            $this->randomUsers($num)->each(function (User $user) use ($parent, $num) {
                $comment = $parent->comments()->create(factory(Comment::class)->raw([
                    'op_id' => $user->id,
                ]));
                $this->randomCommentTree($comment, $this->faker->numberBetween(0, $num));
            });
        }
    }

    protected function randomElements($collection, int $num) {
        if (is_string($collection)) {
            $collection = $this->{$collection};
        }
        return collect(
            $this->faker->randomElements($collection->all(), $num)
        );
    }
}
