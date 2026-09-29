<div class="partner-language-switcher" aria-label="Language">
    @foreach (['nb' => 'Norsk', 'en' => 'English'] as $switchLocale => $label)
        <form method="POST" action="{{ route('partner.locale', ['locale' => $switchLocale]) }}">
            @csrf
            <input type="hidden" name="return_to" value="{{ request()->getRequestUri() }}">
            <button type="submit" class="{{ app()->getLocale() === $switchLocale ? 'is-active' : '' }}">{{ $label }}</button>
        </form>
    @endforeach
</div>
