<?php

namespace App\Livewire\Admin\Pages;

use App\Models\clients;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class DataCleanup extends Component
{
    use WithPagination;

    public string $startDate = '';
    public string $endDate = '';
    public string $status = '';
    public string $search = '';
    public bool $filtersApplied = false;
    public array $selectedIds = [];
    public string $confirmationText = '';

    public function applyFilters(): void
    {
        $this->validateFilters();
        $this->filtersApplied = true;
        $this->selectedIds = [];
        $this->confirmationText = '';
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('startDate', 'endDate', 'status', 'search', 'filtersApplied', 'selectedIds', 'confirmationText');
        $this->resetValidation();
        $this->resetPage();
    }

    public function selectCurrentPage(): void
    {
        if (! $this->filtersApplied) {
            return;
        }

        $pageIds = $this->filteredQuery()
            ->latest()
            ->forPage($this->getPage(), 20)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        $this->selectedIds = array_values(array_unique([...$this->selectedIds, ...$pageIds]));
    }

    public function clearSelection(): void
    {
        $this->selectedIds = [];
        $this->confirmationText = '';
    }

    public function permanentlyDeleteSelected(): void
    {
        $this->validateFilters();
        $this->validate([
            'selectedIds' => ['required', 'array', 'min:1'],
            'selectedIds.*' => ['integer'],
            'confirmationText' => ['required', 'in:DELETE'],
        ], [
            'selectedIds.required' => 'Select at least one client record.',
            'selectedIds.min' => 'Select at least one client record.',
            'confirmationText.in' => 'Type DELETE exactly to confirm permanent deletion.',
        ]);

        $clients = $this->filteredQuery()
            ->whereKey($this->selectedIds)
            ->get();

        if ($clients->isEmpty()) {
            $this->addError('selectedIds', 'The selected records no longer match the active filters.');
            return;
        }

        $paths = $clients->flatMap(function (clients $client): array {
            return array_values(array_unique(array_filter([
                $client->supporting_document_path,
                ...($client->supporting_document_paths ?? []),
            ])));
        })->unique()->values();

        DB::transaction(function () use ($clients): void {
            DB::table('after_sales_records')
                ->whereIn('client_id', $clients->modelKeys())
                ->update(['client_id' => null]);

            clients::query()->whereKey($clients->modelKeys())->delete();
        });

        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }

        $deletedCount = $clients->count();
        $this->selectedIds = [];
        $this->confirmationText = '';
        $this->resetPage();

        session()->flash('success', "{$deletedCount} client record(s) permanently deleted.");
        $this->dispatch('hide-permanent-cleanup-modal');
    }

    private function validateFilters(): void
    {
        $this->validate([
            'startDate' => ['required', 'date', 'before_or_equal:today'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate', 'before_or_equal:today'],
            'status' => ['required', Rule::in(['Pending', 'For Approval', 'Sold'])],
            'search' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function filteredQuery(): Builder
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return clients::query()
            ->with('salesman')
            ->withCount('afterSalesRecords')
            ->where('status', $this->status)
            ->whereBetween('created_at', [$start, $end])
            ->when(trim($this->search) !== '', function (Builder $query): void {
                $search = '%'.trim($this->search).'%';
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('company_name', 'like', $search)
                        ->orWhere('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('salesList_no', 'like', $search)
                        ->orWhereHas('salesman', fn (Builder $salesman) => $salesman
                            ->where('first_name', 'like', $search)
                            ->orWhere('last_name', 'like', $search));
                });
            });
    }

    public function render()
    {
        $clients = $this->filtersApplied
            ? $this->filteredQuery()->latest()->paginate(20)
            : null;

        return view('livewire.admin.pages.data-cleanup', compact('clients'));
    }
}
