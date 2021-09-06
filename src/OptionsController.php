<?php

namespace Alvinhu\ChildSelect;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class OptionsController extends Controller
{
    public function index(NovaRequest $request)
    {
        $attribute = $request->query('attribute');
        $parentValue = $request->query('parent');

        $resource = $request->newResource();
        $fields = $resource->updateFields($request);
        $field = $this->findFieldByAttribute($fields,$attribute);
        $options = $field->getOptions($parentValue);

        return $options;
    }

    public function findFieldByAttribute($fieldObj,$attribute){
        foreach ($fieldObj as $field){
            if(gettype($field) == "array" && $field["component"] == "tabs"){
                foreach ($field["fields"] as $value){
                    if (isset($value->attribute) && $value->attribute == $attribute && $value->component == "child-select"){
                        return $value;
                    }
                }
            }else{
                if(isset($field->attribute) &&
                    $field->attribute == $attribute){
                    return $field;
                }
            }
        }
    }
}
