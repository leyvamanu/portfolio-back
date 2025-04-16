<x-filament-panels::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}
        <x-filament::button type="submit">
            Guardar cambios
        </x-filament::button>
    </form>
</x-filament-panels::page>
