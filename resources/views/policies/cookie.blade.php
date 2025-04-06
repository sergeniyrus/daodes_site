
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Заголовок и дата обновления -->
        <h1 class="text-3xl font-bold mb-2">{{ __('cookie.title') }}</h1>
        <p class="text-gray-500 mb-6">{{ __('cookie.last_updated', ['date' => now()->isoFormat('LL')]) }}</p>
        
        <!-- Что такое cookies -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-3">{{ __('cookie.what_are_cookies') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('cookie.cookies_definition') }}</p>
        </div>
        
        <!-- Типы cookies -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-3">{{ __('cookie.types_title') }}</h2>
            <ul class="space-y-3">
                @foreach(__('cookie.types') as $type => $description)
                <li class="flex items-start">
                    <svg class="h-5 w-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700">{{ $description }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        
        <!-- Управление cookies -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-3">{{ __('cookie.manage_title') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('cookie.manage_text') }}</p>
            <div class="bg-gray-50 p-4 rounded-md">
                <h3 class="font-medium mb-2">{{ __('Browser instructions') }}:</h3>
                <ul class="list-disc pl-5 space-y-1 text-gray-600">
                    <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" class="text-blue-600 hover:underline">Google Chrome</a></li>
                    <li><a href="https://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences" target="_blank" class="text-blue-600 hover:underline">Mozilla Firefox</a></li>
                    <li><a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" target="_blank" class="text-blue-600 hover:underline">Safari</a></li>
                    <li><a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" class="text-blue-600 hover:underline">Microsoft Edge</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Изменения политики -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-3">{{ __('cookie.changes_title') }}</h2>
            <p class="text-gray-700">{{ __('cookie.changes_text') }}</p>
        </div>
        
        <!-- Контакты -->
        <div>
            <h2 class="text-2xl font-semibold mb-3">{{ __('cookie.contact_title') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('cookie.contact_text') }}</p>
            <div class="bg-gray-50 p-4 rounded-md">
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span>privacy@{{ parse_url(config('app.url'), PHP_URL_HOST) }}</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span>+1 (234) 567-8900</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>123 Privacy Street, Data City, DC 12345</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Кнопка возврата -->
        <div class="mt-8">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                {{ __('Back to previous page') }}
            </a>
        </div>
    </div>
</div>
