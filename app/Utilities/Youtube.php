<?php

namespace App\Utilities;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use StdClass;

class Youtube
{

    /**
     * @var array
     */
    public array $APIs = [
        'videos.list' => 'https://www.googleapis.com/youtube/v3/videos',
        'captions.list' => 'https://www.googleapis.com/youtube/v3/captions',
        'categories.list' => 'https://www.googleapis.com/youtube/v3/videoCategories',
        'search.list' => 'https://www.googleapis.com/youtube/v3/search',
        'channels.list' => 'https://www.googleapis.com/youtube/v3/channels',
        'playlists.list' => 'https://www.googleapis.com/youtube/v3/playlists',
        'playlistItems.list' => 'https://www.googleapis.com/youtube/v3/playlistItems',
        'activities' => 'https://www.googleapis.com/youtube/v3/activities',
        'commentThreads.list' => 'https://www.googleapis.com/youtube/v3/commentThreads',
    ];
    /**
     * @var array
     */
    public array $page_info = [];
    /**
     * @var string
     */
    protected string $youtube_key;
    /**
     * @var array
     */
    protected array $config = [];

    /**
     * Constructor
     * $youtube = new Youtube(['key' => 'KEY HERE'])
     *
     * @param string $key
     * @param array $config
     * @throws Exception
     */
    public function __construct(string $key, array $config = [])
    {
        if (!empty($key)) {
            $this->youtube_key = $key;
        } else {
            throw new Exception('Google API key is Required, please visit https://console.developers.google.com/');
        }
        $this->config['use-http-host'] = $config['use-http-host'] ?? false;
    }

    /**
     * Parse a YouTube URL to get the YouTube Vid.
     * Support both full URL (www.youtube.com) and short URL (YouTu.be)
     *
     * @param string $youtube_url
     * @return string Video Id
     * @throws Exception
     */
    public static function parseVidFromURL(string $youtube_url): string
    {
        if (strpos($youtube_url, 'youtube.com')) {
            if (strpos($youtube_url, 'embed')) {
                $path = static::_parse_url_path($youtube_url);
                return substr($path, 7);
            } else {
                $params = static::_parse_url_query($youtube_url);
                return $params['v'];
            }
        } else if (strpos($youtube_url, 'youtu.be')) {
            $path = static::_parse_url_path($youtube_url);
            return substr($path, 1);
        } else {
            throw new Exception('The supplied URL does not look like a Youtube URL');
        }
    }

    /**
     * Parse the input url string and return just the path part
     *
     * @param string $url the URL
     * @return string      the path string
     */
    public static function _parse_url_path(string $url): string
    {
        $array = parse_url($url);

        return $array['path'];
    }

    /**
     * Parse the input url string and return an array of query params
     *
     * @param string $url the URL
     * @return array      array of query params
     */
    public static function _parse_url_query(string $url): array
    {
        $array = parse_url($url);
        $query = $array['query'];

        $queryParts = explode('&', $query);

        $params = [];
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = empty($item[1]) ? '' : $item[1];
        }

        return $params;
    }

    /**
     * @param $setting
     * @return Youtube
     */
    public function useHttpHost($setting): static
    {
        $this->config['use-http-host'] = !!$setting;

        return $this;
    }

    /**
     * @param $key
     * @return Youtube
     */
    public function setApiKey($key): static
    {
        $this->youtube_key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->youtube_key;
    }

    /**
     * @param string $regionCode
     * @param string[] $part
     * @return bool|array|StdClass
     * @throws Exception
     */
    public function getCategories(string $regionCode = 'US', array $part = ['snippet']): bool|array|StdClass
    {
        $API_URL = $this->getApi('categories.list');
        $params = [
            'key' => $this->youtube_key,
            'part' => implode(',', $part),
            'regionCode' => $regionCode
        ];

        $apiData = $this->api_get($API_URL, $params);
        return $this->decodeMultiple($apiData);
    }

    /**
     * @param null $videoId Instructs the API to return comment threads containing comments about the specified channel. (The response will not include comments left on videos that the channel uploaded.)
     * @param integer $maxResults Specifies the maximum number of items that should be returned in the result set. Acceptable values are 1 to 100, inclusive. The default value is 20.
     * @param null $order Specifies the order in which the API response should list comment threads. Valid values are: time, relevance.
     * @param array $part Specifies a list of one or more commentThread resource properties that the API response will include.
     * @param bool $pageInfo Add page info to returned array.
     * @return bool|array
     * @throws Exception
     */
    public function getCommentThreadsByVideoId($videoId = null, int $maxResults = 20, $order = null, array $part = ['id', 'replies', 'snippet'], bool $pageInfo = false): bool|array
    {

        return $this->getCommentThreads(null, null, $videoId, $maxResults, $order, $part, $pageInfo);
    }

    /**
     * @param null $channelId Instructs the API to return comment threads containing comments about the specified channel. (The response will not include comments left on videos that the channel uploaded.)
     * @param null $id Specifies a comma-separated list of comment thread IDs for the resources that should be retrieved.
     * @param null $videoId Instructs the API to return comment threads containing comments about the specified channel. (The response will not include comments left on videos that the channel uploaded.)
     * @param integer $maxResults Specifies the maximum number of items that should be returned to the result set. Acceptable values are 1 to 100, inclusive. The default value is 20.
     * @param null $order Specifies the order in which the API response should list comment threads. Valid values are: time, relevance.
     * @param array $part Specifies a list of one or more commentThread resource properties that the API response will include.
     * @param bool $pageInfo Add page info to returned array.
     * @return bool|array
     * @throws Exception
     */
    public function getCommentThreads($channelId = null, $id = null, $videoId = null, int $maxResults = 20, $order = null, array $part = ['id', 'replies', 'snippet'], $pageInfo = false): bool|array
    {
        $API_URL = $this->getApi('commentThreads.list');

        $params = array_filter([
            'channelId' => $channelId,
            'id' => $id,
            'videoId' => $videoId,
            'maxResults' => $maxResults,
            'part' => implode(',', $part),
            'order' => $order,
        ]);

        $apiData = $this->api_get($API_URL, $params);

        if ($pageInfo) {
            return [
                'results' => $this->decodeList($apiData),
                'info' => $this->page_info,
            ];
        } else {
            return $this->decodeList($apiData);
        }
    }

    /**
     * @param $vId
     * @param array $part
     * @return bool|array|StdClass
     * @throws Exception
     */
    public function getVideoInfo($vId, array $part = ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status']): bool|array|StdClass
    {
        $API_URL = $this->getApi('videos.list');
        $params = [
            'id' => is_array($vId) ? implode(',', $vId) : $vId,
            'key' => $this->youtube_key,
            'part' => implode(',', $part),
        ];

        $apiData = $this->api_get($API_URL, $params);

        if (is_array($vId)) {
            return $this->decodeMultiple($apiData);
        }

        return $this->decodeSingle($apiData);
    }

    /**
     * Gets localized video info by language (f.ex. de) by adding this parameter after video id
     * Youtube::getLocalizedVideoInfo($video->url, 'de')
     *
     * @param $vId
     * @param $language
     * @param array $part
     * @return bool|array|StdClass
     * @throws Exception
     */

    public function getLocalizedVideoInfo($vId, $language, array $part = ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status']): bool|array|StdClass
    {

        $API_URL = $this->getApi('videos.list');
        $params = [
            'id'    => is_array($vId) ? implode(',', $vId) : $vId,
            'key' => $this->youtube_key,
            'hl'    =>  $language,
            'part' => implode(',', $part),
        ];

        $apiData = $this->api_get($API_URL, $params);

        if (is_array($vId)) {
            return $this->decodeMultiple($apiData);
        }

        return $this->decodeSingle($apiData);
    }

    /**
     * Gets popular videos for a specific region (ISO 3166-1 alpha-2)
     *
     * @param $regionCode
     * @param integer $maxResults
     * @param array $part
     * @return bool|array
     * @throws Exception
     */
    public function getPopularVideos($regionCode, int $maxResults = 10, array $part = ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status']): bool|array
    {
        $API_URL = $this->getApi('videos.list');
        $params = [
            'chart' => 'mostPopular',
            'part' => implode(',', $part),
            'regionCode' => $regionCode,
            'maxResults' => $maxResults,
        ];

        $apiData = $this->api_get($API_URL, $params);

        return $this->decodeList($apiData);
    }

    /**
     * Simple search interface, this search all stuffs
     * and order by relevance
     *
     * @param $q
     * @param integer $maxResults
     * @param array $part
     * @return bool|array
     * @throws Exception
     */
    public function search($q, int $maxResults = 10, array $part = ['id', 'snippet']): bool|array
    {
        $params = [
            'q' => $q,
            'part' => implode(',', $part),
            'maxResults' => $maxResults,
        ];

        return $this->searchAdvanced($params);
    }

    /**
     * Search only videos
     *
     * @param string $q Query
     * @param integer $maxResults number of results to return
     * @param string|null $order Order by
     * @param array $part
     * @return array|bool  API results
     * @throws Exception
     */
    public function searchVideos(string $q, int $maxResults = 10, string $order = null, array $part = ['id']): bool|array
    {
        $params = [
            'q' => $q,
            'type' => 'video',
            'part' => implode(',', $part),
            'maxResults' => $maxResults,
        ];
        if (!empty($order)) {
            $params['order'] = $order;
        }

        return $this->searchAdvanced($params);
    }

    /**
     * Search only videos in the channel
     *
     * @param string $q
     * @param string $channelId
     * @param integer $maxResults
     * @param null $order
     * @param array $part
     * @param bool $pageInfo
     * @return bool|array
     * @throws Exception
     */
    public function searchChannelVideos(string $q, string $channelId, int $maxResults = 10, $order = null, array $part = ['id', 'snippet'], bool $pageInfo = false): bool|array
    {
        $params = [
            'q' => $q,
            'type' => 'video',
            'channelId' => $channelId,
            'part' => implode(',', $part),
            'maxResults' => $maxResults,
        ];
        if (!empty($order)) {
            $params['order'] = $order;
        }

        return $this->searchAdvanced($params, $pageInfo);
    }

    /**
     * List videos in the channel
     *
     * @param string $channelId
     * @param integer $maxResults
     * @param null $order
     * @param array $part
     * @param bool $pageInfo
     * @return bool|array
     * @throws Exception
     */
    public function listChannelVideos(string $channelId, int $maxResults = 10, $order = null, array $part = ['id', 'snippet'], bool $pageInfo = false): bool|array
    {
        $params = [
            'type' => 'video',
            'channelId' => $channelId,
            'part' => implode(',', $part),
            'maxResults' => $maxResults,
        ];
        if (!empty($order)) {
            $params['order'] = $order;
        }

        return $this->searchAdvanced($params, $pageInfo);
    }

    /**
     * Generic Search interface, use any parameters specified in
     * the API reference
     *
     * @param $params
     * @param bool $pageInfo
     * @return bool|array
     * @throws Exception
     */
    public function searchAdvanced($params, bool $pageInfo = false): bool|array
    {
        $API_URL = $this->getApi('search.list');

        if (empty($params) || (!isset($params['q']) && !isset($params['channelId']) && !isset($params['videoCategoryId']))) {
            throw new \InvalidArgumentException('at least the Search query or Channel ID or videoCategoryId must be supplied');
        }

        $apiData = $this->api_get($API_URL, $params);
        if ($pageInfo) {
            return [
                'results' => $this->decodeList($apiData),
                'info' => $this->page_info,
            ];
        } else {
            return $this->decodeList($apiData);
        }
    }

    /**
     * Generic Search Paginator, use any parameters specified in
     * the API reference and pass through nextPageToken as $token if set.
     *
     * @param $params
     * @param null $token
     * @return bool|array|null
     * @throws Exception
     */
    public function paginateResults($params, $token = null): bool|array|null
    {
        if (!is_null($token)) {
            $params['pageToken'] = $token;
        }

        if (!empty($params)) {
            return $this->searchAdvanced($params, true);
        }
        return null;
    }

    /**
     * @param $username
     * @param array $optionalParams
     * @param array $part
     * @return bool|StdClass
     * @throws Exception
     */
    public function getChannelByName($username, array $optionalParams = [], array $part = ['id', 'snippet', 'contentDetails', 'statistics']): bool|StdClass
    {
        $API_URL = $this->getApi('channels.list');
        $params = [
            'forUsername' => $username,
            'part' => implode(',', $part),
        ];

        $params = array_merge($params, $optionalParams);

        $apiData = $this->api_get($API_URL, $params);

        return $this->decodeSingle($apiData);
    }

    /**
     * @param $id
     * @param array $optionalParams
     * @param array $part
     * @return bool|array|StdClass
     * @throws Exception
     */
    public function getChannelById($id, array $optionalParams = [], array $part = ['id', 'snippet', 'contentDetails', 'statistics']): bool|array|StdClass
    {
        $API_URL = $this->getApi('channels.list');
        $params = [
            'id' => is_array($id) ? implode(',', $id) : $id,
            'part' => implode(',', $part),
        ];

        $params = array_merge($params, $optionalParams);

        $apiData = $this->api_get($API_URL, $params);

        if (is_array($id)) {
            return $this->decodeMultiple($apiData);
        }

        return $this->decodeSingle($apiData);
    }

    /**
     * @param string $channelId
     * @param array $optionalParams
     * @param array $part
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['results' => "array|bool"])]
    public function getPlaylistsByChannelId(string $channelId, array $optionalParams = [], array $part = ['id', 'snippet', 'status']): array
    {
        $API_URL = $this->getApi('playlists.list');
        $params = [
            'channelId' => $channelId,
            'part' => implode(',', $part)
        ];

        $params = array_merge($params, $optionalParams);

        $apiData = $this->api_get($API_URL, $params);

        return $this->extracted($apiData);
    }

    /**
     * @param $id
     * @param string[] $part
     * @return bool|array|StdClass
     * @throws Exception
     */
    public function getPlaylistById($id, array $part = ['id', 'snippet', 'status']): bool|array|StdClass
    {
        $API_URL = $this->getApi('playlists.list');
        $params = [
            'id' => is_array($id)? implode(',', $id) : $id,
            'part' => implode(',', $part),
        ];
        $apiData = $this->api_get($API_URL, $params);

        if (is_array($id)) {
            return $this->decodeMultiple($apiData);
        }

        return $this->decodeSingle($apiData);
    }

    /**
     * @param string $playlistId
     * @param string $pageToken
     * @param integer $maxResults
     * @param array $part
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['results' => "array|bool"])]
    public function getPlaylistItemsByPlaylistId(string $playlistId, string $pageToken = '', int $maxResults = 50, array $part = ['id', 'snippet', 'contentDetails', 'status']): array
    {
        $API_URL = $this->getApi('playlistItems.list');
        $params = [
            'playlistId' => $playlistId,
            'part' => implode(',', $part),
            'maxResults' => $maxResults,
        ];

        // Pass page token if it is given, an empty string won't change the api response
        $params['pageToken'] = $pageToken;

        $apiData = $this->api_get($API_URL, $params);
        return $this->extracted($apiData);
    }

    /**
     * @param $channelId
     * @param array $part
     * @param integer $maxResults
     * @param bool $pageInfo
     * @param string $pageToken
     * @return bool|array
     * @throws Exception
     */
    public function getActivitiesByChannelId($channelId, array $part = ['id', 'snippet', 'contentDetails'], int $maxResults = 5, bool $pageInfo = false, string $pageToken = ''): bool|array
    {
        if (empty($channelId)) {
            throw new \InvalidArgumentException('ChannelId must be supplied');
        }
        $API_URL = $this->getApi('activities');
        $params = [
            'channelId' => $channelId,
            'part' => implode(',', $part),
            'maxResults' => $maxResults,
            'pageToken' => $pageToken,
        ];
        $apiData = $this->api_get($API_URL, $params);

        if ($pageInfo) {
            return [
                'results' => $this->decodeList($apiData),
                'info' => $this->page_info,
            ];
        } else {
            return $this->decodeList($apiData);
        }
    }

    /**
     * @param string $videoId
     * @param integer $maxResults
     * @param array $part
     * @return bool|array
     * @throws Exception
     */
    public function getRelatedVideos(string $videoId, int $maxResults = 5, array $part = ['id', 'snippet']): bool|array
    {
        if (empty($videoId)) {
            throw new \InvalidArgumentException('A video id must be supplied');
        }
        $API_URL = $this->getApi('search.list');
        $params = [
            'type' => 'video',
            'relatedToVideoId' => $videoId,
            'part' => implode(',', $part),
            'maxResults' => $maxResults,
        ];
        $apiData = $this->api_get($API_URL, $params);

        return $this->decodeList($apiData);
    }

    /**
     * Get the channel object by supplying the URL of the channel page
     *
     * @param string $youtube_url
     * @return bool|array|StdClass Channel object
     * @throws Exception
     */
    public function getChannelFromURL(string $youtube_url): bool|array|StdClass
    {
        if (!str_contains($youtube_url, 'youtube.com')) {
            throw new Exception('The supplied URL does not look like a Youtube URL');
        }

        $path = static::_parse_url_path($youtube_url);
        if (str_starts_with($path, '/channel')) {
            $segments = explode('/', $path);
            $channelId = $segments[count($segments) - 1];
            $channel = $this->getChannelById($channelId);
        } else if (str_starts_with($path, '/user')) {
            $segments = explode('/', $path);
            $username = $segments[count($segments) - 1];
            $channel = $this->getChannelByName($username);
        } else {
            throw new Exception('The supplied URL does not look like a Youtube Channel URL');
        }

        return $channel;
    }


    /**
     * Decode the response from youtube, extract the single resource object.
     * (Don't use this to decode the response containing list of objects)
     *
     * @param string $apiData the api response from YouTube
     * @return bool|StdClass an YouTube resource object
     * @throws Exception
     */
    public function decodeSingle(string $apiData): bool|StdClass
    {
        $resObj = json_decode($apiData);
        if (isset($resObj->error)) {
            $msg = "Error " . $resObj->error->code . " " . $resObj->error->message;
            if (isset($resObj->error->errors[0])) {
                $msg .= " : " . $resObj->error->errors[0]->reason;
            }

            throw new Exception($msg);
        } else {
            $itemsArray = $resObj->items;
            if (!is_array($itemsArray) || count($itemsArray) == 0) {
                return false;
            } else {
                return $itemsArray[0];
            }
        }
    }

    /**
     * Decode the response from YouTube, extract the multiple resource object.
     *
     * @param string $apiData the api response from YouTube
     * @return bool|array|StdClass an YouTube resource object
     * @throws Exception
     */
    public function decodeMultiple(string $apiData): bool|array|StdClass
    {
        $resObj = json_decode($apiData);
        if (isset($resObj->error)) {
            $msg = "Error " . $resObj->error->code . " " . $resObj->error->message;
            if (isset($resObj->error->errors[0])) {
                $msg .= " : " . $resObj->error->errors[0]->reason;
            }

            throw new Exception($msg);
        } else {
            $itemsArray = $resObj->items;
            if (!is_array($itemsArray)) {
                return false;
            } else {
                return $itemsArray;
            }
        }
    }

    /**
     * Decode the response from YouTube, extract the list of resource objects
     *
     * @param string $apiData response string from YouTube
     * @return bool|array Array of StdClass objects
     * @throws Exception
     */
    public function decodeList(string $apiData): bool|array
    {
        $resObj = json_decode($apiData);
        if (isset($resObj->error)) {
            $msg = "Error " . $resObj->error->code . " " . $resObj->error->message;
            if (isset($resObj->error->errors[0])) {
                $msg .= " : " . $resObj->error->errors[0]->reason;
            }

            throw new Exception($msg);
        } else {
            $this->page_info = [
                'resultsPerPage' => $resObj->pageInfo->resultsPerPage,
                'totalResults' => $resObj->pageInfo->totalResults,
                'kind' => $resObj->kind,
                'etag' => $resObj->etag,
                'prevPageToken' => null,
                'nextPageToken' => null,
            ];

            if (isset($resObj->prevPageToken)) {
                $this->page_info['prevPageToken'] = $resObj->prevPageToken;
            }

            if (isset($resObj->nextPageToken)) {
                $this->page_info['nextPageToken'] = $resObj->nextPageToken;
            }

            $itemsArray = $resObj->items;
            if (!is_array($itemsArray) || count($itemsArray) == 0) {
                return false;
            } else {
                return $itemsArray;
            }
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getApi(string $name): mixed
    {
        return $this->APIs[$name];
    }

    /**
     * Using CURL to issue a GET request
     *
     * @param $url
     * @param $params
     * @return string|bool
     * @throws Exception
     */
    public function api_get($url, $params): string|bool
    {
        //Set the YouTube key
        $params['key'] = $this->youtube_key;

        //Boilerplate for CURL
        $tuCurl = curl_init();

        if (isset($_SERVER['HTTP_HOST']) && $this->config['use-http-host']) {
            curl_setopt($tuCurl, CURLOPT_HEADER, array('Referer' => $_SERVER['HTTP_HOST']));
        }

        curl_setopt($tuCurl, CURLOPT_URL, $url . (!str_contains($url, '?') ? '?' : '') . http_build_query($params));
        if (!str_contains($url, 'https')) {
            curl_setopt($tuCurl, CURLOPT_PORT, 80);
        } else {
            curl_setopt($tuCurl, CURLOPT_PORT, 443);
        }

        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
        $tuData = curl_exec($tuCurl);
        if (curl_errno($tuCurl)) {
            throw new Exception('Curl Error : ' . curl_error($tuCurl));
        }

        return $tuData;
    }

    /**
     * @param $vId
     * @param array $part
     * @return bool|array|StdClass
     * @throws Exception
     */
    public function getCaptionInfo($vId, array $part = ['id', 'snippet']): bool|array|StdClass
    {
        $API_URL = $this->getApi('captions.list');
        $params = [
            'videoId' => is_array($vId) ? implode(',', $vId) : $vId,
            'key' => $this->youtube_key,
            'part' => implode(',', $part),
        ];

        $apiData = json_decode($this->api_get($API_URL, $params));
        return $apiData->items;

    }

    /**
     * @param bool|string $apiData
     * @return array|array[]|bool[]
     * @throws Exception
     */
    public function extracted(bool|string $apiData): array
    {
        $result = ['results' => $this->decodeList($apiData)];
        $result['info']['totalResults'] = (isset($this->page_info['totalResults']) ? $this->page_info['totalResults'] : 0);
        $result['info']['nextPageToken'] = (isset($this->page_info['nextPageToken']) ? $this->page_info['nextPageToken'] : false);
        $result['info']['prevPageToken'] = (isset($this->page_info['prevPageToken']) ? $this->page_info['prevPageToken'] : false);

        return $result;
    }
}
