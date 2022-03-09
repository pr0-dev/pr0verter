<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\GetYoutubeDataRequest;
use App\Jobs\ConvertVideoJob;
use App\Models\Conversion;
use App\Models\Upload;
use App\Utilities\Converter;
use DateInterval;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Locale;
use Storage;
use Youtube;

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

        $conversion = Conversion::initialize($upload->id, Upload::class, 'uploadSource', 'uploadResult', $request->getClientIp());

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
}
