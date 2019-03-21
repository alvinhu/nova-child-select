# Child select field for Laravel Nova

This field allows you to dynamically fill options of the select based on value in parent select field.

Field is based on [nova-ajax-select](https://github.com/dillingham/nova-ajax-select).
But instead of providing api endpoint, you can fill options by a closure function.

![](https://user-images.githubusercontent.com/29180903/52602810-15c53900-2e32-11e9-9ade-492bfe80b234.gif)

### Install
```
composer require alvinhu/nova-child-select
```

### Usage
Class have 3 special methods on top of default Select from Laravel Nova.
#### Single parent
`parent` should be a select field or another child select this one depends on.
`options` should be a callable. it will receive parent select field's value as first argument and should return an array to be shown on the child select field.

#### Multiple parent
`parents` should be an array of select fields or other child selects this one depends on.
`options` should be a callable. it will receive all parent field's values as an object as the first argument and should return an array to be shown on the child select field.



### Example

```
use Alvinhu\ChildSelect\ChildSelect;

public function fields(Request $request)
    {
        return [

            ID::make()->sortable(),

            Select::make('Country')
                ->options(Country::all()->mapWithKeys(function ($country) {
                    return [$country->id => $country->name];
                }))
                ->rules('required'),

            Select::make('Province')
                ->options(Province::all()->mapWithKeys(function ($province) {
                    return [$province->id => $province->name];
                }))
                ->rules('required'),

            // Single parent
            ChildSelect::make('City')
                ->parent('country')
                ->options(function ($value) { 
                    City::whereCountry($value)->get()->mapWithKeys(function ($city) {
                        return [$city->id => $city->name];
                    });
                })
                ->rules('required'),

            // Multiple parents
            ChildSelect::make('City')
                ->parents(['country','province'])
                ->options(function ($parents) { 
                    City::whereCountry($parents->country)->whereProvince($parents->province)->get()->mapWithKeys(function ($city) {
                        return [$city->id => $city->name];
                    });
                })
                ->rules('required'),
        ];
    }

```
