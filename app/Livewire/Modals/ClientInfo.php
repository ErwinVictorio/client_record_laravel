<?php

namespace App\Livewire\Modals;

use App\Models\clients;
use Livewire\Component;

class ClientInfo extends Component
{
    public $clientId;

    public string $rejectionReason = '';

    public bool $showRejectReason = false;

    public bool $showSoldConfirmation = false;

    public function showRejectForm(): void
    {
        $this->resetErrorBag();
        $this->showSoldConfirmation = false;
        $this->showRejectReason = true;
    }

    public function openSoldConfirmation(): void
    {
        $this->resetErrorBag();
        $this->showRejectReason = false;
        $this->showSoldConfirmation = true;
    }

    public function cancelReject(): void
    {
        $this->reset('rejectionReason', 'showRejectReason');
        $this->resetErrorBag();
    }

    public function cancelSoldConfirmation(): void
    {
        $this->showSoldConfirmation = false;
        $this->resetErrorBag();
    }

    public function markAsSold(): void
    {
        $client = $this->approvalClient();

        if (! $client) {
            return;
        }

        $client->update([
            'status' => 'Sold',
            'rejection_reason' => null,
        ]);

        $this->reset('rejectionReason', 'showRejectReason', 'showSoldConfirmation');
        session()->flash('success', 'Client successfully marked as sold.');
        $this->dispatch('clients-updated');
    }

    public function rejectClient(): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:2000',
        ], [
            'rejectionReason.required' => 'Please provide a reason for rejection.',
        ]);

        $client = $this->approvalClient();

        if (! $client) {
            return;
        }

        $client->update([
            'status' => 'Pending',
            'rejection_reason' => trim($this->rejectionReason),
        ]);

        $this->reset('rejectionReason', 'showRejectReason', 'showSoldConfirmation');
        session()->flash('success', 'Client rejected and returned to Pending status.');
        $this->dispatch('clients-updated');
    }

    private function approvalClient(): ?clients
    {
        $client = clients::find($this->clientId);

        if (! $client) {
            session()->flash('error', 'Client not found.');

            return null;
        }

        if ($client->status !== 'For Approval') {
            session()->flash('error', 'This client is no longer awaiting approval.');

            return null;
        }

        return $client;
    }

    public function render()
    {
        return view('livewire.modals.client-info', [
            'client' => clients::with('salesman')->find($this->clientId),
        ]);
    }
}
