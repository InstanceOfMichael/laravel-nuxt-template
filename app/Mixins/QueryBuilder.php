<?php

namespace App\Mixins;

class QueryBuilder {

    public function whereRelationSearch () {
        return function ($relation, $value) {
            if (method_exists($relation, 'getQualifiedForeignKey')) {
                if (is_string($value) && strpos($value, ',') !== false) {
                    $value = explode(',', $value);
                }
                if (is_array($value)) {
                    $this->whereIn($relation->getQualifiedForeignKey(), $value);
                } elseif (is_scalar($value)) {
                    $this->where($relation->getQualifiedForeignKey(), $value);
                }
            }
            return $this;
        };
    }

}
