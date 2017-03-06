<?php

namespace EBMApp\Base;

class Field
{
    // Field config
    private $id = null;
    private $label = null;
    private $value = null;
    private $options = [];
    private $alias = null;
    private $placeholder = null;

    private $fieldModel = null;
    private $fieldValidators = [];
    private $saveStrategy = null;

    private $allowEmpty = false;
    private $isEditable = true;
    private $isVisible = true;
    public $isUpdating = false;
    public $isAddressField = false;

    public function setColumn(String $column)
    {
        $this->id = $column;

        return $this;
    }

    public function setAlias(String $alias)
    {
        $this->alias = $alias;

        return $this;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getModel()
    {
        return $this->fieldModel;
    }

    public function setModel($model)
    {
        $this->fieldModel = $model;
    }

    public function addValidator($validator)
    {
        array_push($this->fieldValidators, $validator);

        return $this;
    }

    public function getValidators()
    {
        return $this->fieldValidators;
    }

    /**
    * @description If the value is null, look into the defined model, get the column value and assign it to the field value
    * @description else set the value param or null if none
    * @param String, Int, Array (for checkboxes), Date
    */
    public function setValue($value = null)
    {
        $hasNoValue = is_null($value);
        $hasAModel = !is_null($this->fieldModel);
        $hasAColumn = !is_null($this->id);
        if ($hasNoValue && $hasAModel && $hasAColumn) {
            return $this->setValueFromDb();
        }
        $this->value = $value;

        return $this;
    }

    private function setValueFromDb()
    {
        $model = $this->fieldModel;
        $column = $this->id;
        $this->value = $model->$column;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setLabel(String $label)
    {
        $this->label = $label;

        return $this;
    }

    public function setPlaceholder(String $placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function setOptions(Array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getFieldAttr(String $attr)
    {
        return $this->getFieldConfig()[$attr];
    }

    public function getFieldConfig(): array
    {
        return [
            'id' => $this->id,
            'alias' => $this->alias,
            'label' => $this->label,
            'value' => $this->value,
            'options' => $this->options,
            'placeholder' => $this->placeholder,
        ];
    }

    public function isComplete(): bool
    {
        if (!$this->allowEmpty) {
            return !$this->hasEmptyValue();
        }

        return true;
    }

    public function allowEmpty()
    {
        $this->allowEmpty = true;

        return $this;
    }

    /**
    * @description Set a save strategy function which determines how to save a field in case the default method does not suit the field
    * @param Array(MyClass, 'methodName'), sets the function without executing it, refer to the save method to see the execution

    class MyClass {
        static function methodName($params)
        {
            // Save method goes here
        }
    }
    */
    public function setSaveStrategy($strategy)
    {
        $this->saveStrategy = $strategy;

        return $this;
    }

    /**
    * @description Uses a save strategy if previously defined or saves the field value using the defined eloquent model
    */
    public function save()
    {
        $field = $this;

        if ($this->isUpdating && !$this->isEditable()) {
            return null;
        }

        if ($this->saveStrategy != null) {
            $saveStrategy = $this->saveStrategy;
            $saveStrategy($field);
            return true;
        }

        $model = $field->getModel();
        $column = $field->getFieldAttr('id');
        $value = $field->getValue();
        $model->$column = $value;

        $model->save();
        return true;
    }

    public function isEditable(): bool
    {
        return $this->isEditable;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function notEditable()
    {
        $this->isEditable = false;

        return $this;
    }

    public function notVisible()
    {
        $this->isVisible = false;

        return $this;
    }

    public function getOptionsValue()
    {
        if ($this->hasEmptyValue($this->getValue())) {
            return 'N/A';
        }

        if (count($this->getFieldAttr('options') > 0)) {
            foreach ($this->getFieldAttr('options') as $option) {
                if ($option['key'] == $this->getValue()) {
                    return $option['value'];
                }
            }
        }

        return $this->getValue();
    }

    public function isDisabled(): bool
    {
        return !$this->isEditable() && $this->isUpdating;
    }

    public function addressField()
    {
        $this->isAddressField = true;

        return $this;
    }

    private function hasEmptyValue(): bool
    {
        $value = $this->getValue();
        
        if ($value == null || $value == '' || count($value) < 1) {
            return true;
        }

        return false;
    }
}
