<?php

namespace Qalis\Shared\Domain;

use Qalis\Shared\Domain\Bus\Query\Response;

class DownloadableDocumentResponse implements Response
{
    private $payload;
    private string $fileName;

    public function __construct($payload, string $fileName) {
        $this->payload = $payload;
        $this->fileName = $fileName;
    }

    public function payload() {
        return $this->payload;
    }

    public function fileName() {
        return $this->fileName;
    }

}
