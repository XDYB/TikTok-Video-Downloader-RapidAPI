<?php
require_once __DIR__ . "/constants.php";
require_once INC_DIR . "functions.php";
if (isset($_GET['url']) && !empty($_GET['url'])) {
    $url   = get_param('url');
    $key   = get_param('key');
    $api   = new \TikTok\Api(["api-key" => get_option('rapidapi_key')]);
    $video = $api->getVideo($url);
    if ($video) {
        switch ($key) {
            case "video":
            default:
                if (isset($video->video->downloadAddr) && !empty($video->video->downloadAddr)) {
                    $src      = $video->video->downloadAddr;
                    $filename = $video->id . ".mp4";
                    force_download($src, $filename);
                }
                break;
            case "no-watermark":

                if (isset($video->video->noWatermark) && !empty($video->video->noWatermark)) {
                    $src      = $video->video->noWatermark;
                    $filename = $video->id . "-no-watermark.mp4";
                    force_download($src, $filename);
                }

                break;
            case "music":
                if (isset($video->music->playUrl) && !empty($video->music->playUrl)) {
                    $src      = $video->music->playUrl;
                    $filename = $video->music->id . "-music.mp3";
                    force_download($src, $filename);
                }

                break;
        }
    }
}
exit;
