# Laravel 5 Model Transformers

[![Latest Version on Packagist](https://img.shields.io/packagist/v/themsaid/laravel-model-transformers.svg?style=flat-square)](https://packagist.org/packages/themsaid/laravel-model-transformers)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/themsaid/laravel-model-transformers.svg?style=flat-square)](https://packagist.org/packages/themsaid/laravel-model-transformers)

This package helps API developers to easily transform Eloquent models into arrays that are convertible to JSON.

Here's how you use it, let's say you have a model with the following data:

```json
{
	"name": "iPhone",
	"type": 1
}
```
Here you use a numerical value to represent the different types, you also have a mutator in the model that maps the numerical value to a string.

Inside the controller we can transform the model to be represented into more API friendly structure. 

```php
<?php
class SomeController{
	function getIndex(){
		$product = Product::find(1);
		
		return response([
			"product" => ProductTransformer::transform($product)
		]);
	}
}
```

The above code will result a JSON string that may look like this:

```json
{
	"product": {
		"name": "iPhone",
		"type": {
			"key": 1,
			"name": "Mobile Phone"
		}
	}
}
```

---

## Installation
Begin by installing the package through Composer. Run the following command in your terminal:

```
composer require themsaid/laravel-model-transformers:1.*
```

Once composer is done, add the package service provider in the providers array in `config/app.php`

```
Themsaid\Transformers\TransformersServiceProvider::class
```

Finally publish the config file:

```
php artisan vendor:publish
```

That's all what we need.

## Usage
Create a model transformer class by extending the AbstractTransformer class:

```php
<?php
class CategoryTransformer extends Themsaid\Transformers\AbstractTransformer
{
    public function transformModel(Model $item)
    {
        $output = [
            'name'		=> $item->name,
            'type'		=> [
            		'key'	=> $item->type,
            		'name'	=> $item->typeName
            ],
        ];

        return $output;
    }

}
```

Now you can call the transformer from any controller:

```php
<?php
return response(
	CategoryTransformer::transform( Category::find(1) )
);
```

You can also pass a collection and the result will be an array of transformed models:

```php
<?php
return response(
	CategoryTransformer::transform( Category::all() )
);
```

## Dealing with relationships
The package contains two helpful methods for dealing with relationships, the first one helps you know if a specific relation is eager-loaded:

### isRelationshipLoaded()

```php
<?php
class ProductTransformer extends AbstractTransformer
{
    public function transformModel(Model $item)
    {
        $output = array_only($item->toArray(), ['name', 'id']);

        if ($this->isRelationshipLoaded($item, 'tags')) {
            $output['tags'] = TagTransformer::transform($item->tags);
        }

        return $output;
    }
}
```

Now only if the tags are eager-loaded they will be presented in the $output array, this helps reminding you to eager-load when querying models with relationships.

### isLoadedFromPivotTable()
This method helps you know if the model is loaded from a ManyToMany relationship, it's helpful when there are pivot data in the table and you would like to present them, example for that:

```php
<?php
class TagTransformer extends AbstractTransformer
{
    public function transformModel(Model $item)
    {
        $output = array_only($item->toArray(), ['name', 'id']);

        if ($this->isLoadedFromPivotTable($item, 'products_tags')) {
            $output['relationship_data'] = [
                'is_active' => $item->pivot->is_active,
            ];
        }

        return $output;
    }
}
```

---

## Passing options to the transformer
You may need to pass some options from the controller to the transformer, you can do that by providing an array of options to the `transform()` method as a second parameter:

```php
<?php

CategoryTransformer::transform($category, ['hide_admin_id' => true])
```

Now from inside the CategoryTransformer you can check the options parameter:

```php
<?php
class CategoryTransformer extends AbstractTransformer
{
    public function transformModel(Model $item)
    {
    	$output = [];
    
		if (@$this->options['hide_admin_id']) {
			unset($output['admin_id']);
		}
		
		return $output;
	}
}
```

## Using the shorthand method
This package is shipped with a shorthand method for applying transformation for a Model or a Collection:

```php
<?php
class SomeController{
	function getIndex(){
		$product = Product::find(1);

		return response([
			"product" => transform($product)
		]);
	}
}
```

Using the `transform()` method, the package locates the suitable transformer based on the Model or the Collection passed as the first argument.

By default it assumes that all transformers are located under the `App\Transformers` namespace, you can change this behaviour in the config file.

You may also pass options to the transformer as a second argument:

```php
<?php

transform(Model::find(1), ['use_nl2br' => true])
```