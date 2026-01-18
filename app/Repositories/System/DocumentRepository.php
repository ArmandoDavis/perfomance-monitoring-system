<?php
namespace App\Repositories\System;

use App\Models\Attachment;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentRepository extends BaseRepository
{
    const MODEL = Attachment::class;

    public function store(Model $attachable, UploadedFile $file, array $options = []): Attachment {
        return DB::transaction(function () use ($attachable, $file, $options) {
            $directory = $options['directory'] ?? 'documents';
            $disk = $options['disk'] ?? config('filesystems.default');

            $path = $file->store($directory, $disk);
            return $this->query()->create([
                'attachable_id' => $attachable->getKey(),
                'attachable_type'=> get_class($attachable),

                'name' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,

                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),

                'uploaded_by'  => user_id(),
                'is_public' => $options['is_public'] ?? false,
            ]);
        });
    }

    public function update(Model $attachment, array $input): bool
    {
        return DB::transaction(function () use ($attachment, $input) {
            return $attachment->update([
                'name'       => $input['name'] ?? $attachment->name,
                'is_public' => $input['is_public'] ?? $attachment->is_public,
                'is_active' => $input['is_active'] ?? $attachment->is_active,
            ]);
        });
    }

    public function delete(Model $attachment): ?bool
    {
        return $attachment->delete();
    }

    public function findDocumentById(int $id)
    {
        return $this->find($id);
    }

    public function findDocumentByUuid($uid)
    {
        return $this->findByUid($uid);
    }
}
