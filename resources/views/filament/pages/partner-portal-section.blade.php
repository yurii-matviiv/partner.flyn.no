<x-filament-panels::page>
    <section class="partner-empty-section">
        <div class="partner-empty-section__icon">
            <x-filament::icon :icon="$section['icon']" class="h-8 w-8" />
        </div>
        <p class="partner-empty-section__eyebrow">FLYN PARTNER</p>
        <h1>{{ $section['title'] }}</h1>
        <p class="partner-empty-section__description">{{ $section['description'] }}</p>
        <div class="partner-empty-section__message">
            <x-filament::icon icon="heroicon-o-information-circle" class="h-5 w-5" />
            <p>{{ $section['detail'] }}</p>
        </div>
    </section>
</x-filament-panels::page>
