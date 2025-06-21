<x-filament-panels::page>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold tracking-tight">
                    Commandes de {{ $customer->nom }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $customer->email }} | {{ $customer->telephone ?? 'Pas de téléphone' }}
                </p>
                @if($customer->adresse)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $customer->adresse }}
                    </p>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                <x-filament::badge color="primary">
                    {{ $this->getTableRecords()->count() }} commandes
                </x-filament::badge>
                <x-filament::badge color="success">
                    {{ number_format($this->getTableRecords()->sum('total'), 2) }} €
                </x-filament::badge>
            </div>
        </div>
    </x-filament::section>

    {{ $this->table }}
</x-filament-panels::page>
