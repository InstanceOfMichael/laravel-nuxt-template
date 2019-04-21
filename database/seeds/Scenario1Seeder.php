<?php

use Illuminate\Database\Seeder;

use App\Allowedquestionside;
use App\Answer;
use App\Claim;
use App\Claimrelation;
use App\Claimside;
use App\Comment;
use App\Link;
use App\Question;
use App\Side;
use App\Topic;
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
        DB::statement('TRUNCATE users, password_resets, oauth_providers, questions, comments, answers, claims, claimrelations, links, linkdomains CASCADE;');

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

        $this->call(SidesTableSeeder::class);

        $this->users = collect(
            $this->faker->randomElements(
                factory(User::class, 100)->create()->all(),
                50
            )
        );

        $this->randomUsers(50)->map(function (User $user) {
            return factory(Question::class)->create([
                'op_id' => $user->id,
            ]);
        });

        $this->questions = $this->randomUsers(5)->map(function (User $user) {
            return factory(Question::class)->create([
                'op_id' => $user->id,
            ]);
        });

        $this->randomUsers(1)->map(function (User $user) {
            $claim = factory(Claim::class)->create([
                'op_id' => $user->id,
            ]);
            $answer = factory(Answer::class)->create([
                'op_id' => $user->id,
                'claim_id' => $claim->id,
                'question_id' => $this->questions[0]->id,
            ]);
        });
        $this->randomUsers(2)->map(function (User $user) {
            $claim = factory(Claim::class)->create([
                'op_id' => $user->id,
            ]);
            $answer = factory(Answer::class)->create([
                'op_id' => $user->id,
                'claim_id' => $claim->id,
                'question_id' => $this->questions[1]->id,
            ]);
        });
        $this->randomUsers(20)->map(function (User $user) {
            $claim = factory(Claim::class)->create([
                'op_id' => $user->id,
            ]);
            $answer = factory(Answer::class)->create([
                'op_id' => $user->id,
                'claim_id' => $claim->id,
                'question_id' => $this->questions[2]->id,
            ]);
        });

        $this->questions[3]->update([
            'sides_type' => Side::TYPE_ALLOW,
        ]);
        factory(Side::class, 3)->create()->map(function (Side $side) {
            Allowedquestionside::create([
                'question_id' => $this->questions[3]->id,
                'side_id' => $side->id,
                'op_id' => $this->randomUsers(1)->first()->id,
            ]);
        });

        $this->randomUsers(5)->map(function (User $user) {
            $claim = factory(Claim::class)->create([
                'op_id' => $user->id,
            ]);
            $answer = factory(Answer::class)->create([
                'op_id' => $user->id,
                'claim_id' => $claim->id,
                'question_id' => $this->questions[2]->id,
            ]);
        });

        $this->questions[4]->update([
            'sides_type' => Side::TYPE_ANY,
        ]);
        factory(Side::class, 14)->create([])->map(function (Side $side) {
            Allowedquestionside::create([
                'question_id' => $this->questions[4]->id,
                'side_id' => $side->id,
                'op_id' => $this->randomUsers(1)->first()->id,
            ]);
        });
        $this->randomUsers(10)->map(function (User $user) {
            $claim = factory(Claim::class)->create([
                'op_id' => $user->id,
            ]);
            $answer = factory(Answer::class)->create([
                'op_id' => $user->id,
                'claim_id' => $claim->id,
                'question_id' => $this->questions[4]->id,
            ]);
        });

        $this->randomUsers(3)->map(function (User $user) {
            $pclaim = factory(Claim::class)->create([
                'op_id' => $user->id,
            ]);
            $this->randomUsers(3)->map(function (User $user) use ($pclaim) {
                $rclaim = factory(Claim::class)->create([
                    'op_id' => $user->id,
                ]);
                $cr = factory(Claimrelation::class)->create([
                    'op_id' => $user->id,
                    'pc_id' => $pclaim->id,
                    'rc_id' => $rclaim->id,
                ]);
            });
        });

        $this->randomUsers(15)->map(function (User $user) {
            $link = factory(Link::class)->create([
                'op_id' => $user->id,
            ]);
        });

        $this->randomCommentTree($this->questions[0], 16);
        $this->randomCommentTree($this->questions[1], 12);
        $this->randomCommentTree($this->questions[2], 8);
        $this->randomCommentTree($this->questions[3], 6);
        $this->randomCommentTree($this->questions[4], 4);
        foreach(Answer::all() as $x) {
            $this->randomCommentTree($x, $this->faker->numberBetween(1, 5));
        }
        foreach(Claim::all() as $x) {
            $this->randomCommentTree($x, $this->faker->numberBetween(1, 5));
        }
        foreach(Side::all() as $x) {
            $this->randomCommentTree($x, $this->faker->numberBetween(1, 5));
        }
        foreach(Claimrelation::all() as $x) {
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
