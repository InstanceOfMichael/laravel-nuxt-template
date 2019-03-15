<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Claim;
use App\Question;
use App\Claimrelation;
use App\Answer;
use App\Claimside;
use App\Side;
use App\Allowedquestionside;

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

        $this->faker = app(\Faker\Generator::class);
        $this->faker->seed(get_class($this));

        $this->call(SidesTableSeeder::class);

        $this->users = collect(
            $this->faker->randomElements(
                factory(User::class, 100)->create()->all(),
                50
            )
        );

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
        factory(Side::class, 3)->create([
            'op_id' => $this->randomUsers(1)->first()->id,
        ])->map(function (Side $side) {
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
                'question_id' => $this->questions[2]->id,
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
    }

    protected function randomUsers(int $num) {
        return $this->randomElements('users', $num);
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
