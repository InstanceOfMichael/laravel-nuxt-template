<?php

namespace App\Rules;

use DB;
use Illuminate\Contracts\Validation\Rule;

class UniqueLowerCase implements Rule
{
    protected $table;
    protected $column;
    protected $model;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, string $column, $model = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->model = $model;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $query = DB::table($this->table);

        if ($this->model) {
            $query->where('id', '<>', $this->model->id);
        }

        $query->whereRaw('lower('.$this->column.') = lower(?)', [ $value ]);

        return $query->take(1)->get()->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The value must be unique (case insensitive).';
    }
}
