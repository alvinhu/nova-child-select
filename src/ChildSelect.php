<?php

namespace Alvinhu\ChildSelect;

use Laravel\Nova\Fields\Field;

class ChildSelect extends Field
{
    public $component = 'child-select';

    protected $options;

    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions($parameters = [], $multiParents)
    {
        if($multiParents)
            $parameters = (object)$parameters;
        else
            $parameters = reset($parameters);

        $options = call_user_func($this->options, $parameters);

        $result = [];
        foreach ($options as $key => $option) {
            $result[] = [
                'label' => $option,
                'value' => $key,
            ];
        }

        return $result;
    }

    public function parent($attribute)
    {
        return $this->parents([$attribute], false);
    }

    public function parents($attributes, $multiParents = true)
    {
        $this->withMeta([
            'parentAttributes' => $attributes,
            'multiParents' => $multiParents
        ]);

        return $this;
    }
}
