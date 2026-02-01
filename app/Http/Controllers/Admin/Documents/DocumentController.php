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

    public function viewFile(Attachment $attachment)
    {
        if (!Storage::disk('private')->exists($attachment->path)) {
            abort(404);
        }
        $file = Storage::disk('private')->get($attachment->path);
        $type = $attachment->mime_type;
        return response($file, 200)->header('Content-Type', $type);
    }

    public function delete(Attachment $attachment)
    {
        $this->documentRepository->delete($attachment);
        return back()->with('success_flush', __('Document deleted successfully'));
    }
}
