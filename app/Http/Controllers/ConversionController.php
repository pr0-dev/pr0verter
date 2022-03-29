<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDownloadRequest;
use App\Http\Requests\StoreYoutubeDownloadRequest;
use App\Jobs\DownloadJob;
use App\Jobs\YoutubeDownloadJob;
use App\Models\Download;
use App\Http\Requests\StoreFileRequest;
use App\Jobs\ConvertVideoJob;
use App\Models\Conversion;
use App\Models\Upload;
use App\Models\Youtube;
use App\Utilities\Converter;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ConversionController extends Controller
{
    /**
     * Returns a list of all conversions
     *
     * @return JsonResponse
     */
    public function listConversions(): JsonResponse
    {
        return response()->json(Conversion::with('typeInfo')->get()->makeHidden('guid'));
    }

    /**
     * @param Conversion $conversion
     * @return JsonResponse
     */
    public function showConversion(Conversion $conversion): JsonResponse
    {
        return response()->json($conversion->load('typeInfo'));
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
        dispatch((new ConvertVideoJob($converter->getFFMpegConfig()))->onQueue('convert'));

        return response()->json($conversion);
    }

    /**
     * @param StoreDownloadRequest $request
     * @return JsonResponse
     */
    public function storeDownload(StoreDownloadRequest $request): JsonResponse
    {
        $download = Download::initialize($request->get('url'));

        $conversion = Conversion::initialize($download->id, Download::class, 'downloadSource', 'downloadResult', $request->except('url'));
        dispatch((new DownloadJob($download, $conversion))->onQueue('download'));

        return response()->json($conversion);
    }

    /**
     * @param StoreYoutubeDownloadRequest $request
     * @return JsonResponse
     */
    public function storeYoutube(StoreYoutubeDownloadRequest $request): JsonResponse
    {
        $youtube = Youtube::initialize($request->all(['url', 'subtitle']));

        $conversion = Conversion::initialize($youtube->id, Youtube::class, 'youtubeSource', 'youtubeResult', $request->except(['url', 'subtitle']));

        dispatch((new YoutubeDownloadJob($youtube, $conversion))->onQueue('youtube'));

        return response()->json($conversion);
    }

    /**
     * @param Conversion $conversion
     * @return Response|BinaryFileResponse|Application|ResponseFactory
     */
    public function downloadConversion(Conversion $conversion): Response|BinaryFileResponse|Application|ResponseFactory
    {
        if($conversion->converter_progress === 100)
            return response()->download(Storage::disk($conversion->result_disk)->path($conversion->filename.'.mp4'));
        return response(null, 404);
    }

    /**
     * @param Conversion $conversion
     * @return Application|ResponseFactory|Response
     */
    public function viewConversion(Conversion $conversion): Response|Application|ResponseFactory
    {
        if($conversion->converter_progress === 100)
            return response(Storage::disk($conversion->result_disk)->get($conversion->filename.'.mp4'), 200)->header('Content-Type', 'video/mp4');
        return response(null, 404);
    }
}
