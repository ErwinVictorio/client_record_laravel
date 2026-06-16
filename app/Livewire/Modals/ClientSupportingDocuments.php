<?php

namespace App\Livewire\Modals;

use App\Models\clients;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ClientSupportingDocuments extends Component
{
    public $clientId;
    public $companyName = '';
    public array $documents = [];

    public function mount($clientId)
    {
        $this->clientId = $clientId;
        $this->loadDocuments();
    }

    public function loadDocuments()
    {
        $client = clients::find($this->clientId);

        if (!$client) {
            $this->documents = [];
            return;
        }

        $this->companyName = $client->company_name;
        $this->documents = $this->normalizeDocumentPaths(
            $client->supporting_document_paths,
            $client->supporting_document_path
        );
    }

    public function removeDocument($index)
    {
        $client = clients::find($this->clientId);

        if (!$client || !isset($this->documents[$index])) {
            session()->flash('documents_error', 'Document not found.');
            return;
        }

        $path = $this->documents[$index];

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        unset($this->documents[$index]);
        $this->documents = array_values($this->documents);

        $client->supporting_document_paths = $this->documents;
        $client->supporting_document_path = $this->documents[0] ?? null;
        $client->save();

        session()->flash('documents_message', 'Document removed successfully.');
        $this->dispatch('clients-updated');
    }

    private function normalizeDocumentPaths($documentPaths, $legacyDocumentPath = null): array
    {
        if (is_string($documentPaths)) {
            $documentPaths = json_decode($documentPaths, true);
        }

        $paths = is_array($documentPaths) ? $documentPaths : [];

        if ($legacyDocumentPath && !in_array($legacyDocumentPath, $paths, true)) {
            array_unshift($paths, $legacyDocumentPath);
        }

        return array_values(array_filter($paths));
    }

    public function render()
    {
        return view('livewire.modals.client-supporting-documents');
    }
}
