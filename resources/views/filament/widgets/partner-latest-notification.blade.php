<section class="partner-latest-notification">
    <div class="partner-latest-notification__icon">
        <x-filament::icon icon="heroicon-o-bell" class="h-6 w-6" />
    </div>
    <div class="partner-latest-notification__content">
        <p class="partner-latest-notification__eyebrow">{{ $isEnglish ? 'LATEST NOTIFICATION' : 'SISTE VARSEL' }}</p>
        <h2>{{ data_get($notification->data, 'title') }}</h2>
        @if ($body = data_get($notification->data, 'body'))
            <p>{{ $body }}</p>
        @endif
    </div>
    <a class="partner-latest-notification__action" href="{{ $notificationsUrl }}">
        {{ $isEnglish ? 'View all notifications' : 'Se alle varsler' }}
        <x-filament::icon icon="heroicon-m-arrow-right" class="h-4 w-4" />
    </a>
</section>
