<?php

namespace App\Mixins;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QueryBuilder {

    public function whereRelationSearch () {
        return function (Relation $relation, $value) {
            // dump(get_class($relation));
            if ($relation instanceof HasMany) {
                // $topic->getQualifiedParentKeyName() => topics.id
                $this->whereIn($relation->getQualifiedParentKeyName(), function ($query) use ($relation, $value) {
                    $make = $relation->make();
                    // $relation->make()->getTable() => claimtopics
                    $query->from($make->getTable())
                        // $relation->getQualifiedForeignKeyName() => claimtopics.topic_id
                        ->select($relation->getQualifiedForeignKeyName())
                        // $relation->make()->getQualifiedKeyName() => claimtopics.id
                        ->whereInCsv($make->getQualifiedKeyName(), $value);
                });
            } elseif (method_exists($relation, 'getQualifiedForeignKey')) {
                $this->whereInCsv($relation->getQualifiedForeignKey(), $value);
            } else {
                throw new Exception('unexpected relation type: '. get_class($relation));
            }
            return $this;
        };
    }

    public function whereInCsv () {
        return function ($key, $value) {
            if (is_string($value) && strpos($value, ',') !== false) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $this->whereIn($key, $value);
            } elseif (is_scalar($value)) {
                $this->where($key, $value);
            }
            return $this;
        };
    }

}
