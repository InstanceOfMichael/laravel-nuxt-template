<?php

namespace Tests\Browser\Util;

use Facebook\WebDriver\Remote\RemoteWebElement;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert;

class ClassList {

    /**
     * var $element RemoteWebElement
     */
    protected $element;
    /**
     * var $collection Collection
     */
    protected $collection;

    public function __construct (RemoteWebElement $element) {
        $this->collection = new Collection(
            array_filter(explode(' ', ($element->getAttribute('class') ?: '')))
        );
    }

    public static function from (RemoteWebElement $element): ClassList {
        return new static($element);
    }

    public function assertElement (): ClassList {
        Assert::assertTrue($this->element instanceOf RemoteWebElement, "Expecting Element to have been found");
        return $this;
    }

    public function assertHas (string $cname): ClassList {
        Assert::assertTrue($this->collection->contains($cname), "Expecting Element to has class [{$cname}]");
        return $this;
    }

    public function collection (): Collection {
        return $this->collection;
    }
}
