@extends('template')

@section('title_page')
    {{ __('organizations.title') }}
@endsection

@push('styles')
    @vite('resources/css/organizations.css')
@endpush

@section('main')

    <div class="organization-container">

        {{-- =====================================================
             ЗАГОЛОВОК
             ===================================================== --}}

        <div class="organization-header">

            <h1 class="organization-title">
                {{ __('organizations.my_organizations') }}
            </h1>

            <a
                href="{{ route('organizations.create') }}"
                class="organization-button"
            >
                <i class="fa-solid fa-plus"></i>
                {{ __('organizations.create') }}
            </a>

        </div>


        {{-- =====================================================
             УСПЕШНОЕ УВЕДОМЛЕНИЕ
             ===================================================== --}}

        @if(session('success'))

            <div class="organization-alert success">

                <i class="fa-solid fa-circle-check"></i>

                {{ session('success') }}

            </div>

        @endif


        {{-- =====================================================
             ОШИБКА
             ===================================================== --}}

        @if(session('error'))

            <div class="organization-alert error">

                <i class="fa-solid fa-circle-exclamation"></i>

                {{ session('error') }}

            </div>

        @endif


        {{-- =====================================================
             СПИСОК ОРГАНИЗАЦИЙ
             ===================================================== --}}

        @if($organizations->isNotEmpty())

            <div class="organizations-list">

                @foreach($organizations as $organization)

                    @php

                        $status = $organization->status
                            ?? $organization->pivot->status
                            ?? 'inactive';

                        $statusClass = match($status) {

                            'active' => 'active',

                            'testing' => 'testing',

                            'inactive' => 'inactive',

                            'blocked' => 'blocked',

                            default => 'inactive',

                        };

                    @endphp


                    <div class="organization-card">

                        <div class="organization-card-main">

                            {{-- =================================================
                                 НАЗВАНИЕ ОРГАНИЗАЦИИ
                                 СРАЗУ ПЕРЕХОДИТ В ПАНЕЛЬ УПРАВЛЕНИЯ
                                 ================================================= --}}

                            <a
                                href="{{ route('organizations.manage', $organization) }}"
                                class="organization-name"
                            >
                                {{ $organization->name }}
                            </a>


                            {{-- =================================================
                                 РОЛЬ И СТАТУС
                                 ================================================= --}}

                            <div class="organization-meta">

                                <span class="organization-role">

                                    <i class="fa-solid fa-user-shield"></i>

                                    {{ __('organizations.roles.' . $organization->pivot->role) }}

                                </span>


                                <span class="organization-status {{ $statusClass }}">

                                    <span class="organization-status-dot"></span>

                                    {{ __('organizations.statuses.' . $status) }}

                                </span>

                            </div>

                        </div>


                        {{-- =================================================
                             КНОПКА ПЕРЕХОДА В ПАНЕЛЬ
                             ================================================= --}}

                        <a
                            href="{{ route('organizations.manage', $organization) }}"
                            class="organization-open"
                            title="{{ __('organizations.management') }}"
                        >
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>

                    </div>

                @endforeach

            </div>


        @else

            {{-- =====================================================
                 НЕТ ОРГАНИЗАЦИЙ
                 ===================================================== --}}

            <div class="organization-empty">

                <i class="fa-solid fa-building"></i>

                <div class="organization-empty-title">
                    {{ __('organizations.no_organizations') }}
                </div>

                <a
                    href="{{ route('organizations.create') }}"
                    class="organization-button"
                >
                    <i class="fa-solid fa-plus"></i>
                    {{ __('organizations.create') }}
                </a>

            </div>

        @endif

    </div>

@endsection