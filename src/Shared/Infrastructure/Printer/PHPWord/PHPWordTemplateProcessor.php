<?php

namespace Qalis\Shared\Infrastructure\Printer\PHPWord;

use PhpOffice\PhpWord\TemplateProcessor;

final class PHPWordTemplateProcessor extends TemplateProcessor {

    public function getTempDocumentMainPart() {
        return $this->tempDocumentMainPart;
    }

    public function setTempDocumentMainPart($tempDocumentMainPart) {
        $this->tempDocumentMainPart = $tempDocumentMainPart;
    }

}
