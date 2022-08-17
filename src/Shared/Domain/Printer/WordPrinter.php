<?php

namespace Qalis\Shared\Domain\Printer;

interface WordPrinter {
    public function printSingleData(string $param, $value) : void;
    public function printSingleDataArray(array $values) : void;
    public function cloneTableRow(string $referencedParam, array $data) : void;
    public function cloneBlock(string $blockCode, array $data) : void;
    public function getBinaryContent();
}
