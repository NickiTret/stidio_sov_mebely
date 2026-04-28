@if (session()->has('success') || $errors->any())
    <div class="form-status">
        @if (session()->has('success'))
            <div class="form-status__item form-status__item--success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="form-status__item form-status__item--error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
@endif
