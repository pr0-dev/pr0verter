<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDownloadRequest;
use App\Jobs\DownloadJob;
use App\Models\Download;
use App\Http\Requests\StoreFileRequest;
use App\Jobs\ConvertVideoJob;
use App\Models\Conversion;
use App\Models\Upload;
use App\Utilities\Converter;
use Exception;
use Illuminate\Http\JsonResponse;
use Storage;

class ConversionController extends Controller
{
    /**
     * Returns a list of all conversions
     *
     * @return JsonResponse
     */
    public function listConversions(): JsonResponse
    {
        return response()->json(Conversion::all());
    }

    /**
     * @param Conversion $conversion
     * @return JsonResponse
     */
    public function showConversion(Conversion $conversion): JsonResponse
    {
        return response()->json($conversion);
    }

    public function editConversion(Conversion $conversion): JsonResponse
    {
        return response()->json($conversion);
    }

    /**
     * @param Conversion $conversion
     * @return JsonResponse
     */
    public function deleteConversion(Conversion $conversion): JsonResponse
    {
        return response()->json($conversion);
    }

    public function storeUpload(StoreFileRequest $request): JsonResponse
    {
        $upload = Upload::initialize($request);

        $conversion = Conversion::initialize($upload->id, Upload::class, 'uploadSource', 'uploadResult', $request->except('video'));

        $request->file('video')->storeAs('/', $conversion->filename, $conversion->source_disk);

        try {
            $converter = new Converter(Storage::disk($conversion->source_disk)->path($conversion->filename), $conversion);
        } catch (Exception $exception) {
            $conversion->failed = true;
            $conversion->probe_error = $exception->getMessage();
            $conversion->save();
            return response()->json($conversion, 501);
        }
        $this->dispatch((new ConvertVideoJob($converter->getFFMpegConfig()))->onQueue('convert'));

        return response()->json($conversion);
    }

    public function storeDownload(StoreDownloadRequest $request)
    {
        $download = Download::initialize($request->get('url'));

        $conversion = Conversion::initialize($download->id, Download::class, 'downloadSource', 'downloadResult', $request->except('url'));

        $this->dispatch((new DownloadJob($download, $conversion))->onQueue('download'));
    }
}
