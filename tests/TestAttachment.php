<?php

namespace Ponich\Eloquent\Traits\Tests;

use Ponich\Eloquent\Traits\Tests\Models\Post;
use Ponich\Eloquent\Traits\Tests\Models\User;
use Ponich\Eloquent\Traits\AttachmentModel;
use Ponich\Eloquent\Traits\HasAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;

class TestAttachment extends TestCase
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
     * Check eloquent model if use trait
     *
     * @return void
     */
    public function testModelUseTrait()
    {
        $use = array_key_exists(
            HasAttachment::class,
            class_uses_recursive(Post::class)
        );

        $notUse = array_key_exists(
            HasAttachment::class,
            class_uses_recursive(User::class)
        );

        // has
        $this->assertTrue($use);

        // not has
        $this->assertNotTrue($notUse);
    }

    /**
     * Try attach file by request
     *
     * @return void
     */
    public function testAddAttachByUploadFile()
    {
        $file = new UploadedFile(__DIR__ . '/data/file2.jpeg', 'file2.jpeg');
        $this->post->attach($file);
        $this->assertInstanceOf(AttachmentModel::class, $this->post->attachments->first());
    }

    /**
     * Try attach file by file pathname
     *
     * @return void
     */
    public function testAddAttachByFilePath()
    {
        $this->post->attach(__DIR__ . '/data/file1.txt');
        $this->assertInstanceOf(AttachmentModel::class, $this->post->attachments->first());
    }

    /**
     * Get all attachments from model
     *
     * @return void
     */
    public function testGetAttachFiles()
    {
        $this->testAddAttachByUploadFile();
        $this->testAddAttachByFilePath();

        $this->assertInstanceOf(AttachmentModel::class, $this->post->attachments->first());
        $this->assertInstanceOf(Collection::class, $this->post->attachments);
        $this->assertEquals(2, $this->post->attachments->count());
    }
}
