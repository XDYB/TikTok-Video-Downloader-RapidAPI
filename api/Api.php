<?php
namespace TikTok;

class Api
{
    const API_BASE = "https://tiktok9.p.rapidapi.com/";

    private $_config = [];

    private $defaults = [
        "api-key"        => "",
        "api-host"       => "tiktok9.p.rapidapi.com",
        "user-agent"     => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
        "proxy-host"     => false,
        "proxy-port"     => false,
        "proxy-username" => false,
        "proxy-password" => false,
    ];

    public function __construct($config = [])
    {
        $this->_config = array_merge($this->defaults, $config);
    }

    public function getChallenge($challenge = "")
    {
        if (empty($challenge)) {
            throw new \Exception("Invalid Challenge");
        }
        $result = $this->remote_call(self::API_BASE . "challenge/{$challenge}");
        if (!isset($result->error)) {
            return $result;
        }
        return false;
    }

    public function getChallengeFeed($challenge = "", $maxCursor = '0')
    {
        if (empty($challenge)) {
            throw new \Exception("Invalid Challenge");
        }
        $param = [
            "maxCursor" => $maxCursor,
        ];
        $result = $this->remote_call(self::API_BASE . "challenge/${challenge}/feed?" . http_build_query($param));
        if (!isset($result->error)) {
            return $result;
        }
        return false;
    }

    public function getMusic($music_id = "")
    {
        if (empty($music_id)) {
            throw new \Exception("Invalid Music ID");
        }
        $result = $this->remote_call(self::API_BASE . "music/{$music_id}");
        if (!isset($result->error)) {
            return $result;
        }
        return false;
    }

    public function getMusicFeed($music_id = "", $maxCursor = '0')
    {
        if (empty($music_id)) {
            throw new \Exception("Invalid Music ID");
        }
        $param = [
            "maxCursor" => $maxCursor,
        ];
        $result = $this->remote_call(self::API_BASE . "music/{$music_id}/feed?" . http_build_query($param));

        if (!isset($result->error)) {
            return $result;
        }
        return false;
    }

    public function getUser($username = "")
    {
        if (empty($username)) {
            throw new \Exception("Invalid Username");
        }
        $result = $this->remote_call(self::API_BASE . "user/${username}");
        if (!isset($result->error)) {
            return $result;
        }
        return false;
    }

    public function getUserFeed($username = "", $maxCursor = '0')
    {
        if (empty($username)) {
            throw new \Exception("Invalid Username");
        }
        $param = [
            "maxCursor" => $maxCursor,
        ];
        $result = $this->remote_call(self::API_BASE . "user/${username}/feed?" . http_build_query($param));
        if (!isset($result->error)) {
            return $result;
        }
        return false;
    }

    public function getVideoByUrl($url = "")
    {

        if (empty($url)) {
            throw new \Exception("Invalid video URL");
        }
        $param = [
            "url" => $url,
        ];
        $result = $this->remote_call(self::API_BASE . "url?" . http_build_query($param));
        if (!isset($result->error)) {
            return $result;
        }
        return false;
    }
    public function getVideo($url = "")
    {

        if (empty($url)) {
            throw new \Exception("Invalid video URL");
        }
        $param = [
            "url" => $url,
        ];
        $result = $this->remote_call(self::API_BASE . "?" . http_build_query($param));
        if (!isset($result->error)) {
            return $result;
        }
        return false;
    }

    private function remote_call($url = "", $isJson = true)
    {
        if (!is_null(cacheEngine()->get(normalize($url)))) {
            return cacheEngine()->get(normalize($url));
        }
        $ch      = curl_init();
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => $this->_config['user-agent'],
            CURLOPT_ENCODING       => "utf-8",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_HTTPHEADER     => [
                "x-rapidapi-host: " . $this->_config['api-host'],
                "x-rapidapi-key: " . $this->_config['api-key'],
            ],
        ];
        curl_setopt_array($ch, $options);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        if ($this->_config['proxy-host'] && $this->_config['proxy-port']) {
            curl_setopt($ch, CURLOPT_PROXY, $this->_config['proxy-host'] . ":" . $this->_config['proxy-port']);
            if ($this->_config['proxy-username'] && $this->_config['proxy-password']) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->_config['proxy-username'] . ":" . $this->_config['proxy-password']);
            }
        }
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code < 400) {
            if ($isJson) {
                $data = @json_decode($data);
            }
            cacheEngine()->set(normalize($url), $data, 3600);

            return $data;
        }
        return false;
    }
}
