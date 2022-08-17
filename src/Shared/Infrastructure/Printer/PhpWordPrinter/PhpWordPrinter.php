<?php

namespace Qalis\Shared\Infrastructure\Printer\PhpWordPrinter;

use DOMDocument;
use DOMXPath;
use Exception;
use Qalis\Shared\Domain\Printer\WordPrinter;
use Qalis\Shared\Infrastructure\Printer\PHPWord\PHPWordTemplateProcessor;
use Qalis\Shared\Utils\FileUtils;
use SimpleXMLElement;

class PhpWordPrinter implements WordPrinter {

    private $printer;
    private string $extension;

    public function __construct(string $pathToFile)
    {
        try {
            $this->printer = new PHPWordTemplateProcessor($pathToFile);
            $this->extension = FileUtils::getExtensionFromPath($pathToFile);
        } catch(Exception $e){
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }
    }

    public function extension(bool $withDot = true): string {
        return $withDot ? '.'.$this->extension : $this->extension;
    }

    public function getBinaryContent() {
        $tmpFile = $this->printer->save();
        $handler = fopen($tmpFile, "r");
        $binaryContent = fread($handler, filesize($tmpFile));
        fclose($handler);
        return $binaryContent;
    }

    public function printSingleDataArray(array $values): void
    {
        $this->printer->setValues($this->cleanText($values));
    }

    public function printSingleData(string $param, $value): void
    {
        $this->printer->setValue($param, $this->cleanText($value));
    }

    public function cloneTableRow(string $referencedParam, array $data): void
    {
        $this->printer->cloneRowAndSetValues(
            $referencedParam,
            $this->cleanText($data)
        );
    }

    public function cloneBlock(string $blockCode, array $data): void
    {
        //Esta línea hay que meterla ya que si no no funciona el cloneBlock, parece que por el límite de tamaño...
        ini_set("pcre.backtrack_limit", "2000000");

        $this->printer->cloneBlock(
            $blockCode, 0, true, false,
            $data
        );
    }

    public function deleteBlockByReferenceParam(string $referenceParam): void {

        $referenceParam = '${'.$referenceParam.'}';
        $tempDocumentMainPart = $this->printer->getTempDocumentMainPart();

        $tableToRemove = '';

        preg_match_all('/<w:tbl>(.*?)<\/w:tbl>/i', $tempDocumentMainPart, $matches);
        foreach($matches[0] as $match) {
            if (strpos($match, $referenceParam) !== false) {
                $tableToRemove = $match;
            }
        }

        preg_match_all('/<w:p (.*?)>(.*?)<\/w:p>/i', $tempDocumentMainPart, $matchesParagraphs);
        foreach($matchesParagraphs[0] as $matchesParagraphWithTag) {
            $tempDocumentMainPart = str_replace($matchesParagraphWithTag.$tableToRemove, "", $tempDocumentMainPart);
        }

        $this->printer->setTempDocumentMainPart($tempDocumentMainPart);
    }

    public function emptyTagsBlock(string $blockCode): void {
        $tempDocumentMainPart = $this->printer->getTempDocumentMainPart();
        $tempDocumentMainPart = str_replace('${'.$blockCode.'}', '', $tempDocumentMainPart);
        $tempDocumentMainPart = str_replace('${/'.$blockCode.'}', '', $tempDocumentMainPart);
        $this->printer->setTempDocumentMainPart($tempDocumentMainPart);
    }

    private function cleanText($data) {
        if (!is_array($data)) {
            return $this->rnToWbr(htmlspecialchars($this->wbrToRn($data)));
        } else {
            foreach($data as $key => $value) {
                $data[$key] = $this->cleanText($value);
            }
            return $data;
        }
    }

    private function wbrToRn($value) {
        return str_replace("<w:br />", "\r\n", $value);
    }

    private function rnToWbr($value) {
        return str_replace("\r\n", "<w:br />", $value);
    }

}
