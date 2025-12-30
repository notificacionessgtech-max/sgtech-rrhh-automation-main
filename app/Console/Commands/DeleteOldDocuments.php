<?php

namespace App\Console\Commands;

use App\Models\UploadedDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteOldDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:clean-old-documents';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina documentos subidos hace más de 7 días';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Buscando documentos a eliminar...");

        $documents = UploadedDocument::where('created_at', '<', now()->subDays(7))->get();

        if ($documents->isEmpty()) {
            $this->info("No se encontraron documentos antiguos.");
            return;
        }
        foreach ($documents as $document) {
            if (Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
                $this->info("Eliminado: {$document->path}");
            } else {
                $this->warn("Archivo no encontrado: {$document->path}");
            }

            $document->delete();
        }
        $this->info("Documentos antiguos eliminados correctamente.");
    }
}
