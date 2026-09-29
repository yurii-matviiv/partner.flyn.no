{{-- Authenticated area: follows ERM's USER_MENU_PROFILE_AFTER menu pattern. --}}
<div class="fi-dropdown-list" aria-label="{{ app()->getLocale() === 'en' ? 'Language' : 'Språk' }}">
    <div class="partner-language-menu-label">
        {{ app()->getLocale() === 'en' ? 'Language' : 'Språk' }}
    </div>

    @foreach (['nb' => 'Norsk', 'en' => 'English'] as $switchLocale => $label)
        <form method="POST" action="{{ route('partner.locale', ['locale' => $switchLocale]) }}">
            @csrf
            <input type="hidden" name="return_to" value="{{ request()->getRequestUri() }}">
            <button
                type="submit"
                class="fi-dropdown-list-item fi-dropdown-list-item-color-gray partner-language-menu-item {{ app()->getLocale() === $switchLocale ? 'is-active' : '' }}"
            >
                <span class="fi-dropdown-list-item-label">{{ $label }}</span>
                @if (app()->getLocale() === $switchLocale)
                    <span aria-hidden="true">✓</span>
                @endif
            </button>
        </form>
    @endforeach
</div>
