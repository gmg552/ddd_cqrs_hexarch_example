<?php

namespace Qalis\Shared\Domain\Email;

use Exception;

class EmailCharset {

    public const UTF8 = 'utf8';

    private string $charset;

    public function __construct(string $charset)
    {
        $this->ensureIsAValidCharset($charset);
        $this->charset = $charset;
    }

    private function ensureIsAValidCharset($charset) {
        if ($charset !== self::UTF8)
            throw new Exception("La codificación establecida no existe <$charset>");
    }

    /**
     * @return string
     */
    public function charset(): string
    {
        return $this->charset;
    }


}
