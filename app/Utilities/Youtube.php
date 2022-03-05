<?php

namespace App\Utilities;

use Exception;
use StdClass;

class Youtube
{

    /**
     * @var string
     */
    protected string $youtube_key; // from the config file

    /**
     * @var array
     */
    public array $APIs = [
        'videos.list' => 'https://www.googleapis.com/youtube/v3/videos',
        'captions.list' => 'https://www.googleapis.com/youtube/v3/captions'
    ];

    /**
     * @var array
     */
    public array $page_info = [];

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
     * @param string $vId
     * @param array $part
     * @return StdClass|null
     * @throws Exception
     */
    public function getVideoInfo(string $vId, array $part = ['id', 'snippet', 'contentDetails', 'player']): StdClass|null
    {
        $API_URL = $this->getApi('videos.list');
        $params = [
            'id' => $vId,
            'key' => $this->youtube_key,
            'part' => implode(',', $part),
        ];

        $apiData = json_decode($this->api_get($API_URL, $params));
        if($apiData->items) {
            return $apiData->items[0];
        } else {
            return null;
        }
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
     * Parse a YouTube URL to get the YouTube Vid.
     * Support both full URL (www.youtube.com) and short URL (YouTu.be)
     *
     * @param string $youtube_url
     * @return string Video Id
     *@throws Exception
     */
    public static function parseVidFromURL(string $youtube_url): string
    {
        if (strpos($youtube_url, 'youtube.com')) {
            if (strpos($youtube_url, 'embed')) {
                $path = static::_parse_url_path($youtube_url);
                $vid = substr($path, 7);
                return $vid;
            } else {
                $params = static::_parse_url_query($youtube_url);
                return $params['v'];
            }
        } else if (strpos($youtube_url, 'youtu.be')) {
            $path = static::_parse_url_path($youtube_url);
            $vid = substr($path, 1);
            return $vid;
        } else {
            throw new Exception('The supplied URL does not look like a Youtube URL');
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
}
