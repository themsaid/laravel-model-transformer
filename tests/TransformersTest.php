<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Themsaid\Transformers\Tests\Models\THEMSAID_CATEGORY_MODEL as Category;
use Themsaid\Transformers\Tests\Models\THEMSAID_TAG_MODEL as Tag;
use Themsaid\Transformers\Tests\Transformers\THEMSAID_CATEGORY_MODELTransformer as CategoryTransformer;
use Themsaid\Transformers\Tests\Transformers\THEMSAID_PRODUCT_MODELTransformer as ProductTransformer;

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
    public function test_passing_options_array()
    {
        $category = Category::create(['name' => 'Electronics']);

        $result = CategoryTransformer::transform($category, ['add_me' => 'Value']);
        $this->assertArrayHasKey('add_me', $result);
    }

    /**
     *
     * @return void
     */
    public function test_showing_pivot_data()
    {
        $category = Category::create(['name' => 'Electronics']);
        $product = $category->products()->create(['name' => 'iPhone']);
        $tag = Tag::create(['name' => 'On Sale']);

        $product->tags()->attach($tag->id, [
            'is_active' => 1
        ]);

        $product->load('tags');

        $result = ProductTransformer::transform($product);

        $this->assertEquals(1, $result['tags'][0]['relationship_data']['is_active']);
    }

    /**
     *
     * @expectedException Themsaid\Transformers\TransformerException
     */
    public function test_transform_short_hand_method_must_have_the_first_arg()
    {
        transform();
    }
    /**
     *
     * @expectedException Themsaid\Transformers\TransformerException
     */
    public function test_transform_short_hand_method_must_have_the_first_arg_model_or_collection()
    {
        transform(1);
    }
    /**
     *
     * @return void
     */
    public function test_transform_model_to_array_using_short_hand()
    {
        $category = Category::create(['name' => 'Electronics']);
        $result = transform($category);
        $this->assertArrayHasKey('name', $result);
    }
    /**
     *
     * @return void
     */
    public function test_transform_collection_to_array_using_short_hand()
    {
        $category1 = Category::create(['name' => 'Electronics']);
        $category2 = Category::create(['name' => 'Books']);
        $result = transform(
            Category::whereIn('id', [$category1->id, $category2->id])->get()
        );
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('name', $result[0]);
    }
}
