<x-filament::page>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="p-6 bg-white rounded-xl shadow">
            <h2 class="text-lg font-bold tracking-tight">Bienvenue dans KOMPLEMON Admin</h2>
            <p class="mt-2">Gérez facilement vos produits, commandes, clients et plus encore.</p>
        </div>
    </div>

    @foreach ($this->getHeaderWidgets() as $widget)
        @livewire(\Livewire\Livewire::getAlias($widget), [], key($widget))
    @endforeach
</x-filament::page>