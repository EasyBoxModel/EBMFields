<?php

namespace EBM\Field\Section;

use EBM\Field\Form\BaseForm;

class Section
{
    protected $sections = [];

    public function __construct(){}

    public function save(Array $data = [])
    {
        $baseForm = new BaseForm;

        return $baseForm->save($data);
    }

    public function validate(Array $data = [])
    {
        $baseForm = new BaseForm;

        return $baseForm->validate($data);
    }

    public function getFields()
    {
        $baseForm = new BaseForm;

        return $baseForm->getFields();
    }

    public function addSection($section)
    {
        return array_push($this->sections, $section);
    }

    public function getCurrentSection($alias = null)
    {
        foreach ($this->sections as $section) {
            // For the solicitud/actualizar route, get the $section where the field exists
            if ($alias != null) {
                foreach ($section->getFields() as $field) {
                    $field->isUpdating = true;
                    if ($alias == $field->getFieldAttr('alias')) {
                        $section->isUpdating = true;
                        return $section;
                    }
                }
            }
            if (!$section->isComplete()) {
                return $section;
            }
        }

        return null;
    }

    public function getSections()
    {
        return array_filter($this->sections, function($section){
            return $section->isVisible();
        });
    }
}
