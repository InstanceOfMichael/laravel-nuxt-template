<?php

namespace Tests;

use Illuminate\Support\Collection;
use Exception;
use App\User;

class TestResponseMacros
{
    public function assertDontExposeUserEmails() {
        return function ($input) {
            $arr = [];
            if (is_string($input)) {
                $arr[] = $input;
            }
            if ($input instanceof Collection) {
                $input->reduce(function ($carry, $item) {
                    if (is_string($item)) {
                        $carry[] = $item;
                    } elseif ($item instanceof User) {
                        $carry[] = $item->email;
                    } elseif ($item !== null) {
                        throw new Exception(
                            'Could not get email address from unexpected '
                            .(is_object($item) ? get_class($item) : gettype($item)));
                    }
                    return $carry;
                }, $arr);
            }
            $this->assertJsonMissing(['email']);
            $this->assertDontSeeAny($arr);
            return $this;
        };
    }

    public function assertDontSeeAny() {
        return function (array $arr) {
            foreach ($arr as $item) {
                $this->assertDontSee($item);
            }
            return $this;
        };
    }
}
