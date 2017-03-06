<?php

namespace EBMApp\Base;

use EBMApp\Base\AbstractApplication;

class Section
{
    protected $fields = [];
    protected $isVisible = true;
    protected $name = 'Default template';

    /** 
    * @description A reference to the base application
    */
    private $app = null;

    /* 
     * __construct
     * 
     * Add the section fields and set a reference to the application
     * 
     * @param Array $fields, an array of Field instances
     *
     * @param AbstractApplication $app, a reference to the application that holds the fields
     * 
     * */
    public function __construct(Array $fields, AbstractApplication $app){
        foreach ($fields as $field) {
            $alias = $field->getFieldAttr('alias');
            $this->fields[$alias] = $field;
        }

        if ($this->app == null) {
            $this->setApplication($app);
        }
    }

    public function getApplication()
    {
        return $this->app;
    }

    public function setApplication(AbstractApplication $app)
    {
        $this->app = $app;

        return $this;
    }

    /* 
     * save
     * 
     * Facade to AbstractApplication save method
     * 
     * @param Array $data, the fields data
     * 
     * @return AbstractApplication 
     * */
    public function save(Array $data = [])
    {
        $app = $this->getApplication();

        return $app->save($data);
    }

    /* 
     * validate
     * 
     * Facade to AbstractApplication validate method
     * 
     * @param Array $data, the fields data
     * 
     * @return AbstractApplication 
     * */
    public function validate(Array $data = [])
    {
        $app = $this->getApplication();

        return $app->validate($data);
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
                return false;
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
        $slug = str_replace('_', '-', $slug);
        $slug = str_replace(['á'], 'a', $slug);
        $slug = str_replace(['é'], 'e', $slug);
        $slug = str_replace(['í'], 'i', $slug);
        $slug = str_replace(['ó'], 'o', $slug);
        $slug = str_replace(['ú'], 'u', $slug);
        
        return $slug;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
