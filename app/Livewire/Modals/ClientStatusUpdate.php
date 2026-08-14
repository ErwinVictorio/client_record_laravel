<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\clients;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;


class ClientStatusUpdate extends Component
{
    use WithFileUploads;

    public $clientId;

    #[Validate('required')]
    public $SelectedStatus;

    #[Validate('required')]
    public $salesList_no;


    #[Validate('nullable')]
    public $bank_account_number;

    public array $supporting_docs = [];
    public $existing_supporting_document_path;
    public array $existing_supporting_document_paths = [];

    public array $vehicles = [];

    public function mount($clientId)
    {
        $this->clientId = $clientId;

        $client = clients::find($clientId);

        if ($client) {
            $this->SelectedStatus = $client->status;
            $this->salesList_no = $client->salesList_no;
            $this->bank_account_number = $client->bank_account_number;
            $this->existing_supporting_document_path = $client->supporting_document_path;
            $this->existing_supporting_document_paths = $this->normalizeDocumentPaths($client->supporting_document_paths, $client->supporting_document_path);
            $this->vehicles = $this->normalizeVehicles($client->vehicle_specifications);
        }

        if (count($this->vehicles) === 0) {
            $this->addVehicle();
        }

        if (count($this->supporting_docs) === 0) {
            $this->addSupportingDocument();
        }
    }

    public function addSupportingDocument()
    {
        $this->supporting_docs[] = null;
    }

    public function removeSupportingDocument($index)
    {
        if (count($this->supporting_docs) <= 1) {
            return;
        }

        unset($this->supporting_docs[$index]);
        $this->supporting_docs = array_values($this->supporting_docs);
    }

    public function addVehicle()
    {
        $this->vehicles[] = [
            'brand' => '',
            'model' => '',
            'engine' => '',
            'engine_series' => '',
            'loading_capacity' => '',
            'lifting_height' => '',
            'mast_type' => '',
            'power_type' => '',
            'tire' => '',
            'fork_length' => '',
            'attachment' => '',
        ];
    }

    public function removeVehicle($index)
    {
        if (count($this->vehicles) <= 1) {
            return;
        }

        unset($this->vehicles[$index]);
        $this->vehicles = array_values($this->vehicles);
    }

    public function change_status()
    {
        // Filter out null or empty file inputs
        $uploadedDocs = array_filter($this->supporting_docs, fn($file) => $file !== null);

        // CHC-CHECK: Kung WALANG na-upload na bagong file AT WALANG umiiral na lumang file
        if (empty($uploadedDocs) && empty($this->existing_supporting_document_paths)) {
            $this->addError('supporting_docs', 'Kailangan mag-upload ng kahit isang supporting document (PDF o image).');
            return;
        }

        $this->validate([
            'SelectedStatus' => 'required',
            'salesList_no' => 'required',
            'bank_account_number' => 'nullable|string',
            'supporting_docs' => 'array',
            'supporting_docs.*' => 'nullable|file|mimetypes:application/pdf,image/*|max:5120',
            'vehicles' => 'array|min:1',
            'vehicles.*.brand' => 'required|string',
            'vehicles.*.model' => 'required|string',
            'vehicles.*.engine' => 'nullable|string',
            'vehicles.*.engine_series' => 'nullable|string',
            'vehicles.*.loading_capacity' => 'nullable|string',
            'vehicles.*.lifting_height' => 'nullable|string',
            'vehicles.*.mast_type' => 'nullable|string',
            'vehicles.*.power_type' => 'nullable|string',
            'vehicles.*.tire' => 'nullable|string',
            'vehicles.*.fork_length' => 'nullable|string',
            'vehicles.*.attachment' => 'nullable|string',
        ]);

        $client = clients::find($this->clientId);

        if ($client) {
            $supportingDocumentPaths = $this->normalizeDocumentPaths($client->supporting_document_paths, $client->supporting_document_path);

            foreach ($this->supporting_docs as $supportingDoc) {
                if ($supportingDoc) {
                    $supportingDocumentPaths[] = $supportingDoc->store('supporting-documents', 'public');
                }
            }

            $firstVehicle = $this->vehicles[0] ?? [];

            $client->status = $this->SelectedStatus;
            $client->salesList_no = $this->salesList_no;
            $client->bank_account_number = $this->bank_account_number;
            $client->supporting_document_path = $supportingDocumentPaths[0] ?? null;
            $client->supporting_document_paths = $supportingDocumentPaths;
            $client->vehicle_specifications = $this->vehicles;
            $client->item_name = $firstVehicle['brand'] ?? null;
            $client->model_number = $firstVehicle['model'] ?? null;
            $client->specification = $this->buildSpecificationSummary($firstVehicle);
            $client->save();

            $this->existing_supporting_document_path = $supportingDocumentPaths[0] ?? null;
            $this->existing_supporting_document_paths = $supportingDocumentPaths;

            session()->flash('success', 'Client status updated successfully.');
            $this->supporting_docs = [];
            $this->addSupportingDocument();
            $this->dispatch('clients-updated');
        } else {
            session()->flash('error', 'Client not found.');
        }
    }

    private function normalizeVehicles($vehicleSpecifications): array
    {
        if (!$vehicleSpecifications) {
            return [];
        }

        if (is_string($vehicleSpecifications)) {
            $vehicleSpecifications = json_decode($vehicleSpecifications, true);
        }

        return is_array($vehicleSpecifications) ? $vehicleSpecifications : [];
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

    private function buildSpecificationSummary(array $vehicle): ?string
    {
        $labels = [
            'engine' => 'Engine',
            'engine_series' => 'Engine Series',
            'loading_capacity' => 'Loading Capacity',
            'lifting_height' => 'Lifting Height',
            'mast_type' => 'Mast Type',
            'power_type' => 'Power Type',
            'tire' => 'Tire',
            'fork_length' => 'Fork Length',
            'attachment' => 'Attachment',
        ];

        $summary = [];

        foreach ($labels as $key => $label) {
            if (!empty($vehicle[$key])) {
                $summary[] = $label . ': ' . $vehicle[$key];
            }
        }

        return count($summary) > 0 ? implode("\n", $summary) : null;
    }

    public function render()
    {
        return view('livewire.modals.client-status-update', [
            'clientId' => $this->clientId
        ]);
    }
}
