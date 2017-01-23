<?php

namespace EBMQ\Tests\Section;

use EBMQ\Base\Section;

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
