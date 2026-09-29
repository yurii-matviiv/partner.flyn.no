<x-filament-panels::page>
    <section class="partner-notifications-page">
        @forelse ($notifications as $notification)
            <article @class(['partner-notification-card', 'is-unread' => is_null($notification->read_at)])>
                <div class="partner-notification-card__icon">
                    <x-filament::icon icon="heroicon-o-bell" class="h-5 w-5" />
                </div>
                <div>
                    <h2>{{ data_get($notification->data, 'title') }}</h2>
                    @if ($body = data_get($notification->data, 'body'))
                        <p>{{ $body }}</p>
                    @endif
                    <time datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->diffForHumans() }}</time>
                </div>
            </article>
        @empty
            <div class="partner-notifications-page__empty">
                <x-filament::icon icon="heroicon-o-bell-slash" class="h-8 w-8" />
                <h2>{{ $isEnglish ? 'No notifications yet' : 'Ingen varsler ennå' }}</h2>
                <p>{{ $isEnglish ? 'Important updates from FLYN will appear here.' : 'Viktige oppdateringer fra FLYN vises her.' }}</p>
            </div>
        @endforelse
    </section>
</x-filament-panels::page>
