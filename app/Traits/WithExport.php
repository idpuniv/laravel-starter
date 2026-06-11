<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait WithExport
{
    /**
     * Generate a standardized, context-aware filename for the exported asset.
     *
     * @param string $extension The file extension (e.g., 'xlsx', 'pdf').
     * @param bool $exportAll Flag indicating if the export spans all records or the current page.
     * @return string
     */
    private function generateExportFilename(string $extension, bool $exportAll): string
    {
        $suffix = $exportAll ? 'all' : 'page_' . ($this->page ?? 1);
        return sprintf('export_%s_%s.%s', $suffix, date('Y-m-d_His'), $extension);
    }

    /**
     * Dynamically resolve the default Blade view path for PDF exports 
     * based on the pluralized, kebab-cased model name.
     *
     * @return string
     */
    private function resolvePdfView(): string
    {
        try {
            $modelClass = $this->getModelClass();
            $modelPluralKebab = Str::kebab(Str::plural(class_basename($modelClass)));
            return "exports.{$modelPluralKebab}-pdf";
        } catch (\Throwable $e) {
            Log::error('WithExport Trait: Failed to resolve PDF view path.', [
                'exception' => $e->getMessage(),
                'component' => static::class
            ]);
            return 'exports.default-pdf';
        }
    }

    /**
     * Primary action method intended to be invoked directly from the Blade template.
     * Streams the dataset as an Excel spreadsheet safely using try/catch blocks.
     *
     * @param bool $exportAll Flag indicating if the export spans all records or the current page.
     * @param callable|null $formatter Optional callback function to transform individual rows.
     * @param string|null $filename Optional override for the output filename.
     * @return StreamedResponse|null
     */
    public function exportExcel(bool $exportAll = false, ?callable $formatter = null, ?string $filename = null): ?StreamedResponse
    {
        try {
            $data = $this->getData($exportAll);
            
            if ($data->isEmpty()) {
                $this->handleExportError("No data available to export.");
                return null;
            }

            $finalFilename = $filename ?? $this->generateExportFilename('xlsx', $exportAll);

            return response()->streamDownload(function () use ($data, $formatter) {
                $excel = new FastExcel($data);
                return $formatter 
                    ? $excel->export('php://output', $formatter) 
                    : $excel->export('php://output');
            }, $finalFilename);

        } catch (\Throwable $e) {
            Log::error('WithExport Trait: Excel export failed.', [
                'exception' => $e->getMessage(),
                'component' => static::class,
                'trace'     => $e->getTraceAsString()
            ]);

            $this->handleExportError("An error occurred while generating the Excel file.");
            return null;
        }
    }

    /**
     * Process and stream the dataset as a PDF document safely.
     * Validates view existence before rendering to prevent critical runtime crashes.
     *
     * @param bool $exportAll Flag indicating if the export spans all records or the current page.
     * @param string|null $view Optional override for the target Blade template path.
     * @param string|null $filename Optional override for the output filename.
     * @return StreamedResponse|null
     */
    public function exportPDF(bool $exportAll = false, ?string $view = null, ?string $filename = null): ?StreamedResponse
    {
        try {
            $finalView = $view ?? $this->resolvePdfView();

            // Fail-safe check: Validate if the Blade view template actually exists
            if (!view()->exists($finalView)) {
                Log::error("WithExport Trait: Target PDF view template not found.", [
                    'resolved_view' => $finalView,
                    'component'     => static::class
                ]);

                $this->handleExportError("The PDF export template [{$finalView}] is missing. Please create it or notify administration.");
                return null;
            }

            $data = $this->getData($exportAll);
            
            if ($data->isEmpty()) {
                $this->handleExportError("No data available to export.");
                return null;
            }

            $finalFilename = $filename ?? $this->generateExportFilename('pdf', $exportAll);

            return response()->streamDownload(function () use ($data, $finalView) {
                $pdf = Pdf::loadView($finalView, ['data' => $data]);
                echo $pdf->output();
            }, $finalFilename);

        } catch (\Throwable $e) {
            Log::error('WithExport Trait: PDF export failed.', [
                'exception' => $e->getMessage(),
                'component' => static::class,
                'trace'     => $e->getTraceAsString()
            ]);

            $this->handleExportError("An error occurred while generating the PDF document.");
            return null;
        }
    }

    /**
     * Gracefully handle export faults by notifying the UI without crashing the cycle.
     *
     * @param string $message
     * @return void
     */
    private function handleExportError(string $message): void
    {
        // Fits multiple common Livewire notification patterns
        if (method_exists($this, 'notify')) {
            $this->notify($message, 'error');
        } elseif (method_exists($this, 'dispatch')) {
            $this->dispatch('notify', message: $message, type: 'error');
        } else {
            session()->flash('error', $message);
        }
    }
}