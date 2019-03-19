<?php

namespace Alvinhu\ChildSelect;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class OptionsController extends Controller
{
    public function index(NovaRequest $request)
    {
        $attribute = $request->input('attribute');
        $parentValues = $request->input('parents');

        $resource = $request->newResource();
        $fields = $resource->updateFields($request);
        $field = $fields->findFieldByAttribute($attribute);
        $options = $field->getOptions($parentValues);

        return $options;
    }
}
