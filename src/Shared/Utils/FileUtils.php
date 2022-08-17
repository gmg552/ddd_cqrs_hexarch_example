<?php

namespace Qalis\Shared\Utils;


class FileUtils
{

    public static function cleanFileName(string $name) {
        $notValidCharacters = ['#', '%', '&', '{', '}', "\\", '<', '>', '*', '?', '/', ' ', '$', '!', "'", '"', ':', ';', '@', '+', '`', '|', '=', ',', '.'];
        return str_replace($notValidCharacters, '', $name);
    }

    public static function getExtensionFromPath(string $path) {
        $pathInfo = pathinfo($path);
        if (!isset($pathInfo['extension'])) {
            throw new \InvalidArgumentException("La cadena no tiene extension");
        }
        return $pathInfo['extension'];
    }

}
