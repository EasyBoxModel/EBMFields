<?php

namespace EBMQ\Section;

use EBMQ\Questionnaire\Questionnaire;

class Section
{
    protected $fields = [];
    protected $isVisible = false;

    public function __construct(Array $fields){
        foreach ($fields as $field) {
            array_push($this->fields, $field);
        }
    }

    public function save(Array $data = [])
    {
        $q = new Questionnaire;

        return $q->save($data);
    }

    public function validate(Array $data = [])
    {
        $q = new Questionnaire;

        return $q->validate($data);
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function isComplete(): bool
    {
        $fields = $this->getFields();
        foreach ($fields as $field) {
            return $field->isComplete();
        }

        return true;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }
}
