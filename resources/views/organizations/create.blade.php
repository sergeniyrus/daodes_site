@extends('template')

@section('title_page')
    {{ __('organizations.create_title') }}
@endsection

@push('styles')
    @vite('resources/css/organizations.css')
@endpush

@section('main')

    <div class="organization-container">

        <div class="organization-header">

            <h1 class="organization-title">
                {{ __('organizations.create_title') }}
            </h1>

            <p class="organization-subtitle">
                DAODES
            </p>

        </div>

        @if($errors->any())

            <div class="organization-alert error">

                <strong>
                    {{ __('organizations.validation_error') }}
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            method="POST"
            action="{{ route('organizations.store') }}"
            class="organization-form"
        >

            @csrf

            <div class="organization-form-group">

                <label
                    for="name"
                    class="organization-form-label"
                >
                    {{ __('organizations.organization_name') }}
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="organization-input"
                    placeholder="{{ __('organizations.name_placeholder') }}"
                    required
                    maxlength="255"
                    autocomplete="organization"
                >

            </div>

            <div class="organization-form-actions">

                <button
                    type="submit"
                    class="organization-btn primary"
                >
                    <i class="fa-solid fa-plus"></i>
                    {{ __('organizations.create') }}
                </button>

                <a
                    href="{{ route('organizations.index') }}"
                    class="organization-btn secondary"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    {{ __('organizations.back') }}
                </a>

            </div>

        </form>

    </div>

@endsection