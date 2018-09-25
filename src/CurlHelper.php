<?php

namespace JamChart;

class CurlHelper {

    public function fetch($url, $params) {

        $args = [];
        foreach($params as $key => $value) {
            $args[] =  $key . "=" . $value;
        }
        $fullurl = $url . "?" . \implode("&", $args);

        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_URL, $fullurl);
        $result = \curl_exec($ch);
        \curl_close($ch);

        return $result;
    }

    public function download($url, $target) {
        $fp = \fopen($target, "w+");
        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($ch, CURLOPT_FILE, $fp);
        \curl_setopt($ch, CURLOPT_URL, $url);
        \curl_exec($ch);
        \curl_close($ch);
        \fclose($fp);
    }

}

