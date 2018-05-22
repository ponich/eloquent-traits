<?php

namespace Ponich\Eloquent\Traits\Tests;

use Ponich\Eloquent\Traits\Tests\Models\Post;
use Ponich\Eloquent\Traits\Tests\Models\User;
use Ponich\Eloquent\Traits\VirtualAttributeModel;
use Ponich\Eloquent\Traits\VirtualAttribute;
use Illuminate\Database\QueryException;

class TestVirtualAttribute extends TestCase
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $post;

    /**
     * Setup congigurate
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->post = Post::first();
    }

    /**
     * Column not found in database table
     * Need assert new QueryException
     * Error message must assert by regexp template from trait
     *
     * @return void
     */
    public function testQueryExceptionNotFoundColumn()
    {
        try {
            factory(User::class)->create([
                'column_not_exists' => true
            ]);
        } catch (QueryException $e) {
            $this->assertInstanceOf(QueryException::class, $e);
            $this->assertRegExp(Post::getRegExpMissAttribute(), $e->getMessage());
        }
    }

    /**
     * Model has attribute
     * Check in mutator attributes and native (from database)
     *
     * @return void
     */
    public function testModelAttributeExists()
    {
        // exists mutator
        $this->assertFalse($this->post->hasGetMutator('id'));
        $this->assertArrayHasKey('id', $this->post->getAttributes());

        // exists native
        $this->assertTrue($this->post->hasGetMutator('exits'));
        $this->assertArrayNotHasKey('exits', $this->post->getAttributes());

        // not exists
        $this->assertFalse($this->post->hasGetMutator('notExits'));
        $this->assertArrayNotHasKey('notExits', $this->post->getAttributes());
    }

    /**
     * Try found attribute key from error message
     *
     * @return void
     */
    public function testSearchMissAtributeFromException()
    {
        $attrKey = 'test_attribute_name';
        $attrKeySearch = null;

        try {
            factory(Post::class)->create([
                $attrKey => true
            ]);
        } catch (QueryException $e) {
            $attrKeySearch = Post::getMissAttributeKey($e);
        }

        $this->assertEquals($attrKeySearch, $attrKey);
    }

    /**
     * Check eloquent model if use trait
     *
     * @return void
     */
    public function testModelUseTrait()
    {
        $use = array_key_exists(
            VirtualAttribute::class,
            class_uses_recursive(Post::class)
        );

        $notUse = array_key_exists(
            VirtualAttribute::class,
            class_uses_recursive(User::class)
        );

        // has
        $this->assertTrue($use);

        // not has
        $this->assertNotTrue($notUse);
    }

    /**
     * Try set attributes for model
     * And save virtual attributes in database
     * Try get attribute value from database
     *
     * @return void
     */
    public function testAttachVirtualAttributesForModelWithSave()
    {
        $tags = ['tag1', 'tag2', 'tag3'];

        $this->post->tags = $tags;
        $this->post->save();

        $vmodel = VirtualAttributeModel::where('model_class', get_class($this->post))
            ->where('model_id', $this->post->id)
            ->first();

        $this->assertEquals(
            serialize($tags),
            serialize($this->post->tags)
        );

        $this->assertEquals(
            serialize($tags),
            serialize($vmodel->value)
        );

        $this->post->refresh();

        $this->assertEquals(
            serialize($tags),
            serialize($this->post->tags)
        );
    }
}