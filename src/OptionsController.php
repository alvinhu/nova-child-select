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
        $field = $fields->findFieldByAttribute($attribute);
        if (!$field)
        {
            if ($fields->count() == 1 && isset($fields['Tabs']))
            {
                $tab = $fields->first();
                if (isset($tab['fields']))
                {
                    $fields = $tab['fields'];
                    $field = $fields->findFieldByAttribute($attribute);
                }
            }
        }
        $options = $field->getOptions($parentValue);

        return $options;
    }
}
