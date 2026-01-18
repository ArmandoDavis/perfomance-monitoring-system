<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Repositories\System\DocumentRepository;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    protected $documentRepository;

    public function __construct()
    {
        $this->documentRepository = new DocumentRepository();
    }

    public function download(Attachment $attachment): StreamedResponse
    {
        logger($attachment);
        if (!Storage::exists($attachment->path)) {
            abort(404, __('File not found'));
        }
        return Storage::download($attachment->path, $attachment->name);
    }

    public function profile(Attachment $attachment)
    {
        if (!Storage::exists($attachment->path)) {
            abort(404);
        }
        return response()->file(
            Storage::path($attachment->path),
            [
                'Content-Type' => $attachment->mime_type,
            ]
        );
    }

    public function delete(Attachment $attachment)
    {
        $this->documentRepository->delete($attachment);
        return back()->with('success_flush', __('Document deleted successfully'));
    }
}
