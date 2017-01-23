<?php

namespace EBMApp\Tests\Section;

use EBMApp\Base\Section;

class SectionOne extends Section
{
    protected $name = 'Section One';
    public $isComplete = false;

    public function isComplete(): bool
    {
        if (!$this->isComplete) {
            return false;
        }

        return parent::isComplete();
    }
}
