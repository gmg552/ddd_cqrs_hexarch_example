<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Throwable;

final class PayloadException extends Exception
{

    private array $data;

    public $status = 400;

    public function __construct(array $data, string $message)
    {
        parent::__construct($message);
        $this->data = $data;
    }

    public function data() {
        return $this->data;
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return $this->handleAjax();
        }

        return redirect()->back()
            ->withInput()
            ->withErrors($this->getMessage());
    }

    /**
     * Handle an ajax response.
     */
    private function handleAjax()
    {
        return response()->json([
            'exception'   => 'PayloadException',
            'message' => $this->getMessage(),
            'data'    => $this->data,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => collect($this->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ], $this->status);
    }

}
