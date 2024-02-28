<?php

namespace App\Service;


class VideoIframeExtractor
{

    public function extractSrc(?string $input): ?string
    {

        if($input === null) {
            return null;
        }

        $pattern = '/src=["\'](https?:\/\/[^"\']+)["\']/';

        if(preg_match($pattern, $input, $matches)) {
            return $matches[1];
        }

        return null;

    }

}