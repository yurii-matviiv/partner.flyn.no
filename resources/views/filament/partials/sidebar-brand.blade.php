{{-- Desktop only: логотип стоїть на осі іконок навігації і відкриває/закриває sidebar (як у flyn-erm). --}}
<div class="flyn-sidebar-brand">
    <button
        type="button"
        class="flyn-sidebar-logo-button"
        aria-label="Vis meny"
        title="Vis meny"
        x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()"
        x-bind:aria-label="$store.sidebar.isOpen ? 'Skjul meny' : 'Vis meny'"
        x-bind:title="$store.sidebar.isOpen ? 'Skjul meny' : 'Vis meny'"
    >
        <x-filament-panels::logo />
    </button>

    {{-- Назва видима лише при розгорнутому меню, щоб не вилазити за згорнутий sidebar. --}}
    <span
        class="flyn-sidebar-company-name"
        x-show="$store.sidebar.isOpen"
        x-cloak
    >
        {{ filament()->getBrandName() }}
    </span>
</div>
