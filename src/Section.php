<?php

namespace EBMQ\Base;

use EBMQ\Base\Application;

class Section
{
    protected $fields = [];
    protected $isVisible = true;
    protected $name = 'Default template';

    /** 
    * @description A reference to the base application
    */
    protected $app;

    public function __construct(Array $fields, Application $app){
        foreach ($fields as $field) {
            $alias = $field->getFieldAttr('alias');
            $this->fields[$alias] = $field;
        }
    }

    public function save(Array $data = [])
    {
        $q = new Application;

        return $q->save($data);
    }

    public function validate(Array $data = [])
    {
        $q = new Application;

        return $q->validate($data);
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(String $alias): Field
    {
        $fields = $this->getFields();
        
        return $fields[$alias];
    }

    public function isComplete(): bool
    {
        $fields = $this->getFields();
        foreach ($fields as $field) {
            if (!$field->isComplete()) {
                return $field->isComplete();
            }
        }

        return true;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function getSlug(): string
    {
        $slug = strtolower($this->name);
        $slug = str_replace(' ', '-', $slug);
        
        return $slug;
    }
}
