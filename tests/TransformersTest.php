<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Themsaid\Transformer\Tests\Models\Category;
use Themsaid\Transformer\Tests\Transformers\CategoryTransformer;

class TransformersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     *
     * @return void
     */
    public function test_transform_model_to_array()
    {
        $category = Category::create(['name' => 'Electronics']);

        $result = CategoryTransformer::transform($category);

        $this->assertArrayHasKey('name', $result);
    }

    /**
     *
     * @return void
     */
    public function test_transform_collection_to_array()
    {
        $category1 = Category::create(['name' => 'Electronics']);
        $category2 = Category::create(['name' => 'Books']);

        $result = CategoryTransformer::transform(
            Category::whereIn('id', [$category1->id, $category2->id])->get()
        );

        $this->assertCount(2, $result);
        $this->assertArrayHasKey('name', $result[0]);
    }

    /**
     *
     * @return void
     */
    public function test_transform_model_with_eager_load_relation_returns_transformed_relation_with_it()
    {
        $category = Category::create(['name' => 'Electronics']);
        $category->products()->create(['name' => 'iPhone']);

        $category->load('products');

        $result = CategoryTransformer::transform($category);

        $this->assertArrayHasKey('products', $result);
        $this->assertEquals('iPhone', @$result['products'][0]['name']);
    }

    /**
     *
     * @return void
     */
    public function test_can_exclude_dummy_item()
    {
        $category = Category::create(['name' => 'Electronics']);

        $resultNormal = CategoryTransformer::transform($category);
        $this->assertArrayHasKey('dummy_item', $resultNormal);

        $resultExclude = CategoryTransformer::transform($category, [], function (Collection $output) {
            return $output->except('dummy_item');
        });
        $this->assertArrayNotHasKey('dummy_item', $resultExclude);
    }

}
