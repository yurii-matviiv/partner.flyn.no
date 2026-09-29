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

    <span class="flyn-sidebar-company-name">
        FLYN Partner
    </span>
</div>
