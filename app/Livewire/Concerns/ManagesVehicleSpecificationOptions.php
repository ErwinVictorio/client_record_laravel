<?php

namespace App\Livewire\Concerns;

trait ManagesVehicleSpecificationOptions
{
    protected function vehicleSpecificationOptionRules(): array
    {
        return [
            'vehicles.*.mast_type_selection' => 'nullable|in:M,ZM,ZSM,Other',
            'vehicles.*.mast_type_other' => 'nullable|string|max:255|required_if:vehicles.*.mast_type_selection,Other',
            'vehicles.*.power_type_selection' => 'nullable|in:Electric-Li,Electric Lead Acid,Diesel,Other',
            'vehicles.*.power_type_other' => 'nullable|string|max:255|required_if:vehicles.*.power_type_selection,Other',
            'vehicles.*.tire_selection' => 'nullable|in:Solid,Pneumatic,Other',
            'vehicles.*.tire_other' => 'nullable|string|max:255|required_if:vehicles.*.tire_selection,Other',
        ];
    }

    protected function withVehicleSpecificationSelections(array $vehicle): array
    {
        return array_merge($vehicle, $this->selectionState('mast_type', $vehicle['mast_type'] ?? '', ['M', 'ZM', 'ZSM']),
            $this->selectionState('power_type', $vehicle['power_type'] ?? '', ['Electric-Li', 'Electric Lead Acid', 'Diesel']),
            $this->selectionState('tire', $vehicle['tire'] ?? '', ['Solid', 'Pneumatic']));
    }

    protected function vehiclesForStorage(array $vehicles): array
    {
        return array_map(function (array $vehicle): array {
            foreach (['mast_type', 'power_type', 'tire'] as $field) {
                $selection = $vehicle[$field.'_selection'] ?? '';
                $vehicle[$field] = $selection === 'Other'
                    ? trim((string) ($vehicle[$field.'_other'] ?? ''))
                    : $selection;

                unset($vehicle[$field.'_selection'], $vehicle[$field.'_other']);
            }

            return $vehicle;
        }, $vehicles);
    }

    protected function vehicleSpecificationViewData(): array
    {
        return [
            'mastTypeOptions' => ['M', 'ZM', 'ZSM'],
            'powerTypeOptions' => ['Electric-Li', 'Electric Lead Acid', 'Diesel'],
            'tireOptions' => ['Solid', 'Pneumatic'],
        ];
    }

    private function selectionState(string $field, $value, array $options): array
    {
        $value = trim((string) $value);
        $isCustomValue = $value !== '' && ! in_array($value, $options, true);

        return [
            $field.'_selection' => $isCustomValue ? 'Other' : $value,
            $field.'_other' => $isCustomValue ? $value : '',
        ];
    }
}
