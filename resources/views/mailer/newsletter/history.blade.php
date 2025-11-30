@extends('mailer.admin_layout')

@section('title', 'История рассылок')

@section('content')
<style>
    /* ... твои существующие стили ... */

    /* Стили для блоков статистики */
    .stat-card {
        transition: all 0.2s ease;
    }
    .stat-card:not(.active):hover {
        background-color: #2638a0 !important;
    }
</style>
<div class="p-6 text-gray-100">
    {{-- <h1 class="text-2xl font-bold mb-6 text-gold">История рассылок</h1> --}}

{{-- === Статистика + Фильтрация — фиксированная высота, наведение, подсветка === --}}
<div style="margin-bottom: 24px; white-space: nowrap; overflow-x: auto; padding-bottom: 10px;">

    {{-- Всего --}}
    @php $isActive = empty($status); @endphp
    <a href="{{ route('mailer.history') }}"
       class="stat-card{{ $isActive ? ' active' : '' }}"
       style="display: inline-flex;
              align-items: center;
              justify-content: center;
              height: 56px;
              background-color: {{ $isActive ? 'gold' : '#1c1f2b' }};
              color: {{ $isActive ? '#000' : '#fff' }};
              border: 1px solid {{ $isActive ? 'gold' : 'gold' }};
              border-radius: 12px;
              padding: 0 16px;
              margin-right: 12px;
              min-width: 160px;
              text-decoration: none;
              box-sizing: border-box;
              font-size: 14px;">
        <span style="font-size: 20px; margin-right: 8px; line-height: 1;">📨</span>
        <span style="color: {{ $isActive ? '#000' : '#888' }}; margin-right: 4px;">Всего:</span>
        <span style="font-weight: bold; color: {{ $isActive ? '#000' : 'gold' }};">{{ $totalCount }}</span>
    </a>

    {{-- Отправлено --}}
    @php $isActive = $status === 'sent'; @endphp
    <a href="{{ route('mailer.history', ['status' => 'sent']) }}"
       class="stat-card{{ $isActive ? ' active' : '' }}"
       style="display: inline-flex;
              align-items: center;
              justify-content: center;
              height: 56px;
              background-color: {{ $isActive ? 'gold' : '#1c1f2b' }};
              color: {{ $isActive ? '#000' : '#fff' }};
              border: 1px solid {{ $isActive ? 'gold' : '#3b82f6' }};
              border-radius: 12px;
              padding: 0 16px;
              margin-right: 12px;
              min-width: 160px;
              text-decoration: none;
              box-sizing: border-box;
              font-size: 14px;">
        <span style="font-size: 20px; margin-right: 8px; line-height: 1;">🚀</span>
        <span style="color: {{ $isActive ? '#000' : '#888' }}; margin-right: 4px;">Отправлено:</span>
        <span style="font-weight: bold; color: {{ $isActive ? '#000' : '#3b82f6' }};">{{ $sentCount }}</span>
    </a>

    {{-- Прочитано --}}
    @php $isActive = $status === 'read'; @endphp
    <a href="{{ route('mailer.history', ['status' => 'read']) }}"
       class="stat-card{{ $isActive ? ' active' : '' }}"
       style="display: inline-flex;
              align-items: center;
              justify-content: center;
              height: 56px;
              background-color: {{ $isActive ? 'gold' : '#1c1f2b' }};
              color: {{ $isActive ? '#000' : '#fff' }};
              border: 1px solid {{ $isActive ? 'gold' : '#10b981' }};
              border-radius: 12px;
              padding: 0 16px;
              margin-right: 12px;
              min-width: 160px;
              text-decoration: none;
              box-sizing: border-box;
              font-size: 14px;">
        <span style="font-size: 20px; margin-right: 8px; line-height: 1;">👁</span>
        <span style="color: {{ $isActive ? '#000' : '#888' }}; margin-right: 4px;">Прочитано:</span>
        <span style="font-weight: bold; color: {{ $isActive ? '#000' : '#10b981' }};">{{ $readCount }}</span>
    </a>

    {{-- Ожидает --}}
    @php $isActive = $status === 'pending'; @endphp
    <a href="{{ route('mailer.history', ['status' => 'pending']) }}"
       class="stat-card{{ $isActive ? ' active' : '' }}"
       style="display: inline-flex;
              align-items: center;
              justify-content: center;
              height: 56px;
              background-color: {{ $isActive ? 'gold' : '#1c1f2b' }};
              color: {{ $isActive ? '#000' : '#fff' }};
              border: 1px solid {{ $isActive ? 'gold' : '#f59e0b' }};
              border-radius: 12px;
              padding: 0 16px;
              margin-right: 12px;
              min-width: 160px;
              text-decoration: none;
              box-sizing: border-box;
              font-size: 14px;">
        <span style="font-size: 20px; margin-right: 8px; line-height: 1;">⏳</span>
        <span style="color: {{ $isActive ? '#000' : '#888' }}; margin-right: 4px;">В очереди:</span>
        <span style="font-weight: bold; color: {{ $isActive ? '#000' : '#f59e0b' }};">{{ $pendingCount }}</span>
    </a>

    {{-- Ошибки --}}
    @php $isActive = $status === 'failed'; @endphp
    <a href="{{ route('mailer.history', ['status' => 'failed']) }}"
       class="stat-card{{ $isActive ? ' active' : '' }}"
       style="display: inline-flex;
              align-items: center;
              justify-content: center;
              height: 56px;
              background-color: {{ $isActive ? 'gold' : '#1c1f2b' }};
              color: {{ $isActive ? '#000' : '#fff' }};
              border: 1px solid {{ $isActive ? 'gold' : '#ef4444' }};
              border-radius: 12px;
              padding: 0 16px;
              margin-right: 12px;
              min-width: 160px;
              text-decoration: none;
              box-sizing: border-box;
              font-size: 14px;">
        <span style="font-size: 20px; margin-right: 8px; line-height: 1;">⚠️</span>
        <span style="color: {{ $isActive ? '#000' : '#888' }}; margin-right: 4px;">Ошибка:</span>
        <span style="font-weight: bold; color: {{ $isActive ? '#000' : '#ef4444' }};">{{ $failedCount }}</span>
    </a>

    {{-- Open Rate — статичный --}}
    <div class="stat-card"
         style="display: inline-flex;
                align-items: center;
                justify-content: center;
                height: 56px;
                background-color: #1c1f2b;
                color: #fff;
                border: 1px solid #10b981;
                border-radius: 12px;
                padding: 0 16px;
                margin-right: 12px;
                min-width: 160px;
                box-sizing: border-box;
                font-size: 14px;">
        <span style="font-size: 20px; margin-right: 8px; line-height: 1;">📊</span>
        <span style="color: #888; margin-right: 4px;">Open:</span>
        <span style="font-weight: bold; color: #10b981;">{{ $openRate }}%</span>
    </div>
</div>

    {{-- === Таблица логов === --}}
    <table class="w-full text-sm border border-gold rounded-xl overflow-hidden">
        <thead class="bg-[#1c1f2b] text-gold uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Получатель</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Шаблон</th>
                <th class="px-4 py-3 text-left">Статус</th>
                <th class="px-4 py-3 text-left">Отправлено</th>
                <th class="px-4 py-3 text-left">Прочитано</th>
            </tr>
        </thead>
        <tbody class="bg-[#0b0c18] divide-y divide-[#222]">
            @forelse ($logs as $log)
                <tr>
                    <td class="px-4 py-3">{{ $log->recipient->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $log->recipient->email ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $log->template->subject ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if ($log->status === 'read')
                            <span class="text-emerald-400 font-semibold">Прочитано</span>
                        @elseif ($log->status === 'sent')
                            <span class="text-blue-400">Отправлено</span>
                        @elseif ($log->status === 'pending')
                            <span class="text-yellow-400">Ожидает</span>
                        @elseif ($log->status === 'failed')
                            <span class="text-red-400">Ошибка</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i') : '—' }}</td>
                    <td class="px-4 py-3">{{ $log->read_at ? $log->read_at->format('Y-m-d H:i') : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-gray-400">Нет данных</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection
