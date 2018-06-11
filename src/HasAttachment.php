<?php

namespace Ponich\Eloquent\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

trait HasAttachment
{
    /**
     * Get attachment relation files
     *
     * @return HasMany
     */
    public function attachments()
    {
        return $this->hasMany(
            AttachmentModel::class,
            'model_id',
            $this->primaryKey
        )->where('model_class', get_class($this));
    }

    /**
     * Attach file to model
     *
     * @param UploadedFile|string $file
     * @param string|null $disk storage disk name
     * @return AttachmentModel|null
     */
    public function attach($file, $disk = null)
    {
        $disk = $disk ?? config('filesystems.default');

        if (!$file instanceof UploadedFile) {
            $file = new UploadedFile($file, File::mimeType($file));
        }

        // create new model
        $attach = new AttachmentModel();
        $attach->model_class = get_class($this);
        $attach->model_id = array_get($this, $this->primaryKey);
        $attach->md5 = md5($file->getPathname() . time());
        $attach->disk = $disk;
        $attach->path = 'attachments/' . $attach->md5 . ".{$file->getExtension()}";
        $attach->name = $file->getClientOriginalName();
        $attach->type = $file->getMimeType();
        $attach->size = $file->getSize();

        // move file to storage
        $fStorage = Storage::disk($disk)->put($attach->path, File::get($file->getPathname()));

        // save
        if ($fStorage && $attach->save()) {
            $this->load('attachments');

            return $attach;
        }

        return null;
    }
}
