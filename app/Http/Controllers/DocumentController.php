<?php

namespace App\Http\Controllers;

use App\Models\PersonalData;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DocumentController extends Controller
{
    /**
     * Descargar todos los documentos de un empleado
     */
    public function downloadAllDocuments($id)
    {
        try {
            // 1. Buscar al empleado
            $employee = PersonalData::with('documents')->findOrFail($id);

            \Log::info("Iniciando descarga para: {$employee->first_name} {$employee->last_name}");
            \Log::info("Total documentos en BD: " . $employee->documents->count());

            // 2. Verificar si tiene documentos
            if ($employee->documents->isEmpty()) {
                return back()->with('error', 'Este empleado no tiene documentos.');
            }

            // 3. Crear ZIP
            $cleanName = preg_replace('/[^a-zA-Z0-9]/', '_', $employee->first_name . '_' . $employee->last_name);
            $zipFileName = 'documentos_' . $cleanName . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            // Crear directorio temporal
            if (!is_dir(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // 4. Crear archivo ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $addedFiles = 0;

                foreach ($employee->documents as $document) {
                    // Verificar si el archivo existe
                    $filePath = storage_path('app/public/' . $document->file_path);

                    if (file_exists($filePath)) {
                        // Obtener extensión REAL
                        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                        // Si no tiene extensión, intentar detectarla
                        if (empty($extension)) {
                            $mime = mime_content_type($filePath);
                            $extension = $this->mimeToExtension($mime);
                        }

                        // Nombre único con ID del documento
                        $friendlyName = $this->getDocumentType($document->document_type) .
                            '_' . $cleanName .
                            '_' . $document->id .
                            '.' . $extension;

                        if ($zip->addFile($filePath, $friendlyName)) {
                            $addedFiles++;
                            \Log::info("✓ Agregado: {$friendlyName} (Tipo: {$document->document_type})");
                        }
                    } else {
                        \Log::warning("✗ Archivo no encontrado: {$document->file_path}");
                    }
                }

                $zip->close();

                \Log::info("Total archivos agregados al ZIP: {$addedFiles} de " . $employee->documents->count());

                if ($addedFiles === 0) {
                    unlink($zipPath);
                    return back()->with('error', 'No se encontraron archivos físicos para descargar.');
                }

                // 5. Descargar
                return response()->download($zipPath, $zipFileName)
                    ->deleteFileAfterSend(true);
            }

            return back()->with('error', 'No se pudo crear el archivo ZIP.');

        } catch (\Exception $e) {
            \Log::error('Error en downloadAllDocuments: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Convertir MIME type a extensión
     */
    private function mimeToExtension($mime)
    {
        $mime_map = [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        ];

        return $mime_map[$mime] ?? 'file';
    }

    /**
     * Obtener nombre del tipo de documento
     */
    private function getDocumentType($type)
    {
        $types = [
            'eps' => 'EPS',
            'hoja_vida' => 'Hoja_de_Vida',
            'nit' => 'NIT',
            'certificacion_bancaria' => 'Certificacion_Bancaria',
            'fondo_pensiones' => 'Fondo_Pensiones',
            'cesantias' => 'Cesantias',
            'fondo_ahorro' => 'Fondo_Ahorro',
            'certificado_estudios' => 'Certificado_Estudios',
            'document' => 'Documento',
            'file' => 'Archivo',
        ];

        return $types[$type] ?? ucfirst($type);
    }

    /**
     * Obtener nombre del tipo de documento
     */


    /**
     * Verificar documentos (para AJAX)
     */
    public function checkDocuments($id)
    {
        try {
            $employee = PersonalData::with('documents')->findOrFail($id);

            return response()->json([
                'success' => true,
                'hasDocuments' => !$employee->documents->isEmpty(),
                'count' => $employee->documents->count(),
                'employee' => [
                    'id' => $employee->personal_data_id,
                    'name' => $employee->first_name . ' ' . $employee->last_name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Página de debug
     */
    public function debugDocuments($id)
    {
        $employee = PersonalData::with('documents')->findOrFail($id);

        echo "<h1>Debug: {$employee->first_name} {$employee->last_name}</h1>";
        echo "<p>Total documentos en BD: " . $employee->documents->count() . "</p>";

        foreach ($employee->documents as $doc) {
            echo "<hr>";
            echo "<p><strong>Tipo:</strong> {$doc->document_type}</p>";
            echo "<p><strong>Ruta en BD:</strong> {$doc->file_path}</p>";

            // Verificar si el archivo existe
            $filePath = storage_path('app/public/' . $doc->file_path);
            echo "<p><strong>Ruta física:</strong> {$filePath}</p>";
            echo "<p><strong>Existe:</strong> " . (file_exists($filePath) ? '✅ SÍ' : '❌ NO') . "</p>";

            if (file_exists($filePath)) {
                echo "<p><strong>Tamaño:</strong> " . filesize($filePath) . " bytes</p>";
            }
        }

        echo "<hr>";
        echo '<a href="' . route('documents.downloadAll', $id) . '">🔗 Probar descarga</a>';

        return '';
    }
}
