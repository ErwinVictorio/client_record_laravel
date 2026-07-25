<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Suffix;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ManageSuffix extends Component
{
    public $suffixes;
    public string $newSuffix = '';
    public ?int $editingSuffixId = null;
    public string $editingSuffix = '';

    public function mount(): void
    {
        $this->loadSuffixes();
    }

    public function loadSuffixes(): void
    {
        $this->suffixes = Suffix::query()->orderBy('suffix')->get();
    }

    public function createSuffix(): void
    {
        $this->newSuffix = trim($this->newSuffix);

        $this->validate([
            'newSuffix' => ['required', 'string', 'max:255', Rule::unique('company_suffix', 'suffix')],
        ], [], ['newSuffix' => 'suffix']);

        Suffix::create(['suffix' => $this->newSuffix]);

        $this->reset('newSuffix');
        $this->loadSuffixes();
        session()->flash('success', 'Company suffix added successfully.');
    }

    public function startEditing(int $suffixId): void
    {
        $suffix = Suffix::findOrFail($suffixId);

        $this->editingSuffixId = $suffix->id;
        $this->editingSuffix = $suffix->suffix;
        $this->resetValidation();
    }

    public function updateSuffix(): void
    {
        if ($this->editingSuffixId === null) {
            return;
        }

        $this->editingSuffix = trim($this->editingSuffix);

        $this->validate([
            'editingSuffix' => [
                'required',
                'string',
                'max:255',
                Rule::unique('company_suffix', 'suffix')->ignore($this->editingSuffixId),
            ],
        ], [], ['editingSuffix' => 'suffix']);

        Suffix::findOrFail($this->editingSuffixId)->update(['suffix' => $this->editingSuffix]);

        $this->cancelEditing();
        $this->loadSuffixes();
        session()->flash('success', 'Company suffix updated successfully.');
    }

    public function cancelEditing(): void
    {
        $this->reset('editingSuffixId', 'editingSuffix');
        $this->resetValidation();
    }

    public function deleteSuffix(int $suffixId): void
    {
        Suffix::findOrFail($suffixId)->delete();

        if ($this->editingSuffixId === $suffixId) {
            $this->cancelEditing();
        }

        $this->loadSuffixes();
        session()->flash('success', 'Company suffix deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.pages.manage-suffix');
    }
}
