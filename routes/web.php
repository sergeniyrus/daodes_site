<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ReleaseAdmin;

use App\Models\User;
use App\Models\Chat;

use App\Http\Controllers\{
    ProfileController,
    NewsController,
    OffersController,
    SeedController,
    KeywordResetPasswordController,
    WalletController,
    VoteController,
    CommentController,
    SpamController,
    DiscussionController,
    TasksController,
    CategoryTasksController,
    BidController,
    HomeController,
    TeamController,
    WpController,
    UserProfileController,
    UploadController,
    ChatController,
    LanguageController,
    CaptchaController,
    NotificationController,
    CookieConsentController,
    MailerAdminController,
    MailerTrackController,
    MailerClickController,
    MailTemplateController,
    UserStatusController,
    UserKeyController,
    SeedSetupController,
    MessageController,
};

use App\Http\Controllers\Admin\AppVersionAdminController;
use App\Http\Controllers\Admin\ReleaseAdminController;
use App\Http\Controllers\AppDownloadController;

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationMenuController;


/*
|--------------------------------------------------------------------------
| RELEASE ADMIN PANEL
|--------------------------------------------------------------------------
|
| Управление релизами приложения "Ёлки Иголки".
|
| Доступ только DAO_Root.
|
*/

Route::middleware([
    'auth',
    ReleaseAdmin::class,
])
    ->prefix('admin/releases')
    ->name('admin.releases.')
    ->group(function () {

        // Список релизов
        Route::get('/', [
            ReleaseAdminController::class,
            'index',
        ])->name('index');

        // Создание релиза
        Route::get('/create', [
            ReleaseAdminController::class,
            'create',
        ])->name('create');

        // Сохранение релиза
        Route::post('/', [
            ReleaseAdminController::class,
            'store',
        ])->name('store');

        // Редактирование релиза
        Route::get('/{release}/edit', [
            ReleaseAdminController::class,
            'edit',
        ])->name('edit');

        // Обновление релиза
        Route::put('/{release}', [
            ReleaseAdminController::class,
            'update',
        ])->name('update');

        // Удаление релиза
        Route::delete('/{release}', [
            ReleaseAdminController::class,
            'destroy',
        ])->name('destroy');

        // Активация релиза
        Route::post('/{release}/activate', [
            ReleaseAdminController::class,
            'activate',
        ])->name('activate');
    });


/*
|--------------------------------------------------------------------------
| E2E / КРИПТОГРАФИЯ ЧАТОВ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Установка публичного ключа профиля
    Route::post(
        '/profile/set-public-key',
        [UserProfileController::class, 'setPublicKey']
    );


    // Получение собственного ключа чата
    Route::get('/chats/{chat}/my-key', function (Chat $chat) {

        if (!$chat->users->contains(auth()->id())) {
            abort(403, 'You are not a member of this chat');
        }

        $keyRecord = $chat->memberKeys()
            ->where('user_id', auth()->id())
            ->first();

        if (!$keyRecord) {
            return response()->json([
                'error' => 'Encrypted chat key not found',
            ], 404);
        }

        $initiator = $chat->initiator;

        if (
            !$initiator ||
            !$initiator->profile?->public_key
        ) {
            return response()->json([
                'error' => 'Chat initiator or their public key not found',
            ], 400);
        }

        return response()->json([
            'encrypted_key' => $keyRecord->encrypted_key,
            'nonce' => $keyRecord->nonce,
            'initiator_public_key' => $initiator->profile->public_key,
        ]);
    });


    // Список пользователей для нового чата
    Route::get('/api/users/list-for-chat', function () {

        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->whereNotIn('id', [1, 2])
            ->select([
                'id',
                'name',
            ])
            ->get();

        return response()->json($users);
    });
});


/*
|--------------------------------------------------------------------------
| ПУБЛИЧНЫЕ КЛЮЧИ ПОЛЬЗОВАТЕЛЕЙ
|--------------------------------------------------------------------------
*/

Route::post(
    '/users/public-keys',
    [UserKeyController::class, 'getPublicKeys']
)->name('users.public-keys');


/*
|--------------------------------------------------------------------------
| SEED / НАСТРОЙКА КЛЮЧЕЙ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Проверка наличия public_key
    Route::get(
        '/profile/has-public-key',
        [SeedSetupController::class, 'hasPublicKey']
    )->name('profile.has-public-key');


    // Страница ввода seed-фразы
    Route::get(
        '/setup-keys',
        [SeedSetupController::class, 'show']
    )->name('auth.seed.setup');


    // Фиксация ошибки seed-фразы
    Route::post(
        '/setup-keys/report-invalid',
        [SeedSetupController::class, 'reportInvalidSeed']
    );


    // Получение публичного ключа
    Route::get(
        '/profile/public-key',
        [SeedSetupController::class, 'getPublicKey']
    )->name('profile.public-key');


    // Проверка seed-фразы
    Route::post(
        '/setup-keys/verify',
        [SeedSetupController::class, 'verifySeedPhrase']
    )->name('setup-keys.verify');
});


/*
|--------------------------------------------------------------------------
| СТАТУС ПОЛЬЗОВАТЕЛЯ / ONLINE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post(
        '/user/online',
        [UserStatusController::class, 'online']
    )
        ->withoutMiddleware([
            VerifyCsrfToken::class,
        ])
        ->name('user.online');


    Route::get('/user/{user}/status', function (User $user) {

        return response()->json([
            'is_online' => $user->isOnline(),
            'last_seen_human' => $user->lastSeenHuman(),
        ]);
    })->name('user.status');
});


/*
|--------------------------------------------------------------------------
| HEALTH CHECK
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response('OK', 200)
        ->header('Content-Type', 'text/plain');
});


/*
|--------------------------------------------------------------------------
| MAILER ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'admin',
])
    ->prefix('admin/mailer')
    ->name('mailer.')
    ->group(function () {

        // Dashboard
        Route::get('/', [
            MailerAdminController::class,
            'dashboard',
        ])->name('dashboard');


        /*
         * Шаблоны писем
         */

        Route::prefix('templates')
            ->name('templates.')
            ->group(function () {

                Route::get('/', [
                    MailTemplateController::class,
                    'index',
                ])->name('index');

                Route::get('/create', [
                    MailTemplateController::class,
                    'create',
                ])->name('create');

                Route::post('/', [
                    MailTemplateController::class,
                    'store',
                ])->name('store');

                Route::get('/{template}/edit', [
                    MailTemplateController::class,
                    'edit',
                ])->name('edit');

                Route::put('/{template}', [
                    MailTemplateController::class,
                    'update',
                ])->name('update');

                Route::delete('/{template}', [
                    MailTemplateController::class,
                    'destroy',
                ])->name('destroy');

                Route::get('/{template}', [
                    MailTemplateController::class,
                    'show',
                ])->name('show');
            });


        /*
         * Контакты
         */

        Route::get('/recipients', [
            MailerAdminController::class,
            'recipientsIndex',
        ])->name('recipients.index');

        Route::get('/recipients/create', [
            MailerAdminController::class,
            'recipientsCreate',
        ])->name('recipients.create');

        Route::post('/recipients', [
            MailerAdminController::class,
            'recipientsStore',
        ])->name('recipients.store');

        Route::get('/recipients/{recipient}/edit', [
            MailerAdminController::class,
            'recipientsEdit',
        ])->name('recipients.edit');

        Route::put('/recipients/{recipient}', [
            MailerAdminController::class,
            'recipientsUpdate',
        ])->name('recipients.update');

        Route::delete('/recipients/{recipient}', [
            MailerAdminController::class,
            'recipientsDestroy',
        ])->name('recipients.destroy');

        Route::get('/recipients/import', [
            MailerAdminController::class,
            'recipientsImportForm',
        ])->name('recipients.import.form');

        Route::post('/recipients/import', [
            MailerAdminController::class,
            'recipientsImport',
        ])->name('recipients.import');


        /*
         * Списки контактов
         */

        Route::get('/lists', [
            MailerAdminController::class,
            'listsIndex',
        ])->name('lists.index');

        Route::get('/lists/create', [
            MailerAdminController::class,
            'listsCreate',
        ])->name('lists.create');

        Route::post('/lists', [
            MailerAdminController::class,
            'listsStore',
        ])->name('lists.store');

        Route::get('/lists/{list}/edit', [
            MailerAdminController::class,
            'listsEdit',
        ])->name('lists.edit');

        Route::put('/lists/{list}', [
            MailerAdminController::class,
            'listsUpdate',
        ])->name('lists.update');

        Route::delete('/lists/{list}', [
            MailerAdminController::class,
            'listsDestroy',
        ])->name('lists.destroy');


        /*
         * Рассылки
         */

        Route::get('/send', [
            MailerAdminController::class,
            'sendForm',
        ])->name('send.form');

        Route::post('/send', [
            MailerAdminController::class,
            'send',
        ])->name('send');

        Route::get('/history', [
            MailerAdminController::class,
            'history',
        ])->name('history');
    });


/*
|--------------------------------------------------------------------------
| TRACKING MAILER
|--------------------------------------------------------------------------
*/

Route::get(
    '/mailer/track/{logId}',
    [MailerTrackController::class, 'track']
)->name('mailer.track');

Route::get(
    '/mailer/c/{logId}',
    [MailerClickController::class, 'redirect']
)->name('mailer.click');


/*
|--------------------------------------------------------------------------
| COOKIE CONSENT
|--------------------------------------------------------------------------
*/

Route::post(
    '/cookies/accept',
    [CookieConsentController::class, 'accept']
)->name('cookies.accept');

Route::post(
    '/cookies/reject',
    [CookieConsentController::class, 'reject']
)->name('cookies.reject');

Route::get(
    '/cookie-policy',
    [CookieConsentController::class, 'policy']
)->name('cookie.policy');


/*
|--------------------------------------------------------------------------
| УВЕДОМЛЕНИЯ
|--------------------------------------------------------------------------
*/

Route::get(
    '/notifications/unread-count',
    [NotificationController::class, 'unreadCount']
);


/*
|--------------------------------------------------------------------------
| CAPTCHA
|--------------------------------------------------------------------------
*/

Route::get(
    '/captcha',
    [CaptchaController::class, 'show']
)
    ->name('captcha.show')
    ->withoutMiddleware([SetLocale::class]);

Route::post(
    '/captcha',
    [CaptchaController::class, 'verify']
)
    ->name('captcha.verify')
    ->withoutMiddleware([SetLocale::class]);


/*
|--------------------------------------------------------------------------
| ЯЗЫК
|--------------------------------------------------------------------------
*/

Route::get(
    '/language/{locale}',
    [LanguageController::class, 'change']
)->name('language.change');


/*
|--------------------------------------------------------------------------
| ЧАТЫ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Список чатов
    Route::get(
        '/chats',
        [ChatController::class, 'index']
    )->name('chats.index');


    // Создание чата
    Route::get(
        '/chats/create',
        [ChatController::class, 'create']
    )->name('chats.create');


    Route::post(
        '/chats',
        [ChatController::class, 'store']
    )->name('chats.store');


    // Просмотр чата
    Route::get(
        '/chats/{chat}',
        [ChatController::class, 'show']
    )->name('chats.show');


    // Ключ шифрования
    Route::get(
        '/chats/{chat}/my-key',
        [ChatController::class, 'myKey']
    );


    /*
     * Сообщения
     */

    Route::get(
        '/chats/{chat}/messages',
        [MessageController::class, 'index']
    )->name('messages.get');

    Route::post(
        '/chats/{chat}/messages',
        [MessageController::class, 'store']
    )->name('messages.send');

    Route::patch(
        '/messages/{message}',
        [MessageController::class, 'update']
    );

    Route::delete(
        '/messages/{message}',
        [MessageController::class, 'destroy']
    );

    Route::get(
        '/messages/{message}/reply-info',
        [MessageController::class, 'getReplyInfo']
    )->name('messages.replyInfo');

    Route::get(
        '/chats/{chat}/messages/ids',
        [MessageController::class, 'getMessageIds']
    )->name('messages.ids');


    /*
     * Уведомления чатов
     */

    Route::get(
        '/notifications',
        [ChatController::class, 'notifications']
    )->name('chats.notifications');

    Route::post(
        '/notifications/{notification}/mark-as-read',
        [ChatController::class, 'markAsRead']
    );


    /*
     * Создание чата с пользователем
     */

    Route::get(
        '/chats/create-with-user/{user}',
        [ChatController::class, 'createWithUser']
    );
});


/*
|--------------------------------------------------------------------------
| СОЗДАНИЕ / ОТКРЫТИЕ ЧАТА
|--------------------------------------------------------------------------
*/

Route::post(
    '/chats/create-with-user/{userId}',
    [ChatController::class, 'createWithUser']
)->name('chats.createWithUser');

Route::post(
    '/chats/create-or-open/{userId}',
    [ChatController::class, 'createOrOpen']
)->name('chats.createOrOpen');


/*
|--------------------------------------------------------------------------
| ГЛАВНАЯ
|--------------------------------------------------------------------------
*/

Route::view(
    '/',
    'home'
)->name('home');

Route::get(
    'home',
    [HomeController::class, 'home']
)->name('home.alt');

Route::get(
    'good/{post}/{id}/{action}',
    [HomeController::class, 'good']
)->name('good');


/*
|--------------------------------------------------------------------------
| TEAM
|--------------------------------------------------------------------------
*/

Route::get(
    'team',
    [TeamController::class, 'team']
)->name('team');


/*
|--------------------------------------------------------------------------
| WHITE PAPER
|--------------------------------------------------------------------------
*/

Route::get(
    '/white_paper',
    [WpController::class, 'whitepaper']
)->name('white_paper');


/*
|--------------------------------------------------------------------------
| NEWS
|--------------------------------------------------------------------------
*/

// Создание новости
Route::get(
    '/news/create',
    [NewsController::class, 'create']
)
    ->name('news.create')
    ->middleware('auth');


Route::prefix('news')->group(function () {

    // Список новостей
    Route::get(
        '/',
        [NewsController::class, 'list']
    )->name('news.index');


    // Просмотр новости
    Route::get(
        '/{id}',
        [NewsController::class, 'show']
    )->name('news.show');


    Route::middleware('auth')->group(function () {

        // Создание
        Route::post(
            '/',
            [NewsController::class, 'store']
        )->name('news.store');


        // Редактирование
        Route::get(
            '/{id}/edit',
            [NewsController::class, 'edit']
        )->name('news.edit');


        // Обновление
        Route::put(
            '/{id}',
            [NewsController::class, 'update']
        )->name('news.update');


        // Удаление
        Route::delete(
            '/{id}',
            [NewsController::class, 'destroy']
        )->name('news.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| КАТЕГОРИИ НОВОСТЕЙ
|--------------------------------------------------------------------------
*/

Route::prefix('newscategories')
    ->name('newscategories.')
    ->middleware('auth')
    ->group(function () {

        Route::get(
            '/',
            [NewsController::class, 'categoryIndex']
        )->name('index');

        Route::get(
            '/create',
            [NewsController::class, 'categoryCreate']
        )->name('create');

        Route::post(
            '/',
            [NewsController::class, 'categoryStore']
        )->name('categoryStore');

        Route::get(
            '/{id}/edit',
            [NewsController::class, 'categoryEdit']
        )->name('edit');

        Route::put(
            '/{id}',
            [NewsController::class, 'categoryUpdate']
        )->name('update');

        Route::delete(
            '/{id}',
            [NewsController::class, 'categoryDestroy']
        )->name('destroy');
    });


/*
|--------------------------------------------------------------------------
| OFFERS
|--------------------------------------------------------------------------
*/

Route::get(
    'offers/create',
    [OffersController::class, 'create']
)
    ->name('offers.create')
    ->middleware('auth');

Route::post(
    'offers/store',
    [OffersController::class, 'store']
)
    ->name('offers.store')
    ->middleware('auth');


Route::prefix('offers')->group(function () {

    Route::get(
        '/',
        [OffersController::class, 'index']
    )->name('offers.index');

    Route::get(
        '/{id}',
        [OffersController::class, 'show']
    )->name('offers.show');


    Route::middleware('auth')->group(function () {

        Route::get(
            '/{id}/edit',
            [OffersController::class, 'edit']
        )->name('offers.edit');

        Route::put(
            '/{id}',
            [OffersController::class, 'update']
        )->name('offers.update');

        Route::delete(
            '/{id}',
            [OffersController::class, 'destroy']
        )->name('offers.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| КАТЕГОРИИ OFFERS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('offerscategories')
    ->name('offerscategories.')
    ->group(function () {

        Route::get(
            '/',
            [OffersController::class, 'categoryIndex']
        )->name('index');

        Route::get(
            '/create',
            [OffersController::class, 'categoryCreate']
        )->name('create');

        Route::post(
            '/',
            [OffersController::class, 'categoryStore']
        )->name('categoryStore');

        Route::get(
            '/{id}/edit',
            [OffersController::class, 'categoryEdit']
        )->name('edit');

        Route::put(
            '/{id}',
            [OffersController::class, 'categoryUpdate']
        )->name('update');

        Route::delete(
            '/{id}',
            [OffersController::class, 'categoryDestroy']
        )->name('destroy');
    });


/*
|--------------------------------------------------------------------------
| КОММЕНТАРИИ / СПАМ / ОБСУЖДЕНИЯ / ГОЛОСОВАНИЯ
|--------------------------------------------------------------------------
*/

Route::post(
    '/commentsoffers',
    [CommentController::class, 'offers']
)->name('comments.offers');

Route::post(
    '/commentsnews',
    [CommentController::class, 'news']
)->name('comments.news');

Route::post(
    '/spam',
    [SpamController::class, 'store']
)->name('spam.store');

Route::post(
    '/discussion',
    [DiscussionController::class, 'store']
)->name('discussion.store');

Route::post(
    '/vote',
    [VoteController::class, 'store']
)->name('vote.store');


/*
|--------------------------------------------------------------------------
| SEED
|--------------------------------------------------------------------------
*/

Route::middleware('guest')
    ->prefix('seed')
    ->name('seed.')
    ->group(function () {

        Route::get(
            '/',
            [SeedController::class, 'index']
        )->name('index');

        Route::post(
            '/save',
            [SeedController::class, 'saveSeed']
        )->name('save');
    });


/*
|--------------------------------------------------------------------------
| PHP INFO
|--------------------------------------------------------------------------
*/

Route::get(
    '/phpinfo',
    function () {
        phpinfo();
    }
);


/*
|--------------------------------------------------------------------------
| СБРОС ПАРОЛЯ ПО КЛЮЧЕВОМУ СЛОВУ
|--------------------------------------------------------------------------
*/

Route::middleware('guest')
    ->prefix('password')
    ->name('password.')
    ->group(function () {

        Route::get(
            '/forgot',
            [KeywordResetPasswordController::class, 'showKeywordForm']
        )->name('keyword');

        Route::post(
            '/submit',
            [KeywordResetPasswordController::class, 'submitKeyword']
        )->name('submit');

        Route::get(
            '/reset',
            [KeywordResetPasswordController::class, 'showResetForm']
        )->name('reset');

        Route::put(
            '/update',
            [KeywordResetPasswordController::class, 'updatePassword']
        )->name('update');
    });


/*
|--------------------------------------------------------------------------
| ОРГАНИЗАЦИИ
|--------------------------------------------------------------------------
|
| Здесь находится ЕДИНСТВЕННЫЙ блок маршрутов организаций.
|
| ВАЖНО:
| organizations.menu.index
| organizations.menu.manage
|
| оба ведут на OrganizationMenuController@index.
|
*/

Route::middleware('auth')
    ->prefix('organizations')
    ->name('organizations.')
    ->group(function () {


        /*
         * =====================================================
         * СПИСОК ОРГАНИЗАЦИЙ
         * =====================================================
         */

        Route::get(
            '/',
            [OrganizationController::class, 'index']
        )->name('index');


        /*
         * =====================================================
         * СОЗДАНИЕ ОРГАНИЗАЦИИ
         * =====================================================
         */

        Route::get(
            '/create',
            [OrganizationController::class, 'create']
        )->name('create');

        Route::post(
            '/',
            [OrganizationController::class, 'store']
        )->name('store');


        /*
         * =====================================================
         * УПРАВЛЕНИЕ ОРГАНИЗАЦИЕЙ
         * =====================================================
         */

        Route::get(
            '/{organization}/manage',
            [OrganizationController::class, 'manage']
        )
            ->middleware('organization.manager')
            ->name('manage');


        /*
         * =====================================================
         * СОТРУДНИКИ
         * =====================================================
         */

        // Добавление сотрудника
        Route::post(
            '/{organization}/employees',
            [OrganizationController::class, 'storeEmployee']
        )
            ->middleware('organization.manager')
            ->name('employees.store');


        // Изменение роли
        Route::post(
            '/{organization}/employees/{user}/role',
            [OrganizationController::class, 'updateEmployeeRole']
        )
            ->middleware('organization.manager')
            ->name('employees.role');


        // Изменение статуса
        Route::post(
            '/{organization}/employees/{user}/status',
            [OrganizationController::class, 'updateEmployeeStatus']
        )
            ->middleware('organization.manager')
            ->name('employees.status');


        // Удаление сотрудника
        Route::delete(
            '/{organization}/employees/{user}',
            [OrganizationController::class, 'destroyEmployee']
        )
            ->middleware('organization.manager')
            ->name('employees.destroy');


        /*
         * =====================================================
         * УПРАВЛЕНИЕ МЕНЮ
         * =====================================================
         *
         * Главное представление меню.
         *
         * Основное имя:
         * organizations.menu.index
         *
         * Также оставляем:
         * organizations.menu.manage
         *
         * чтобы существующий код, использующий manage,
         * продолжал работать.
         */


        Route::get(
            '/{organization}/menu',
            [
                OrganizationMenuController::class,
                'index',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.index');


        Route::get(
            '/{organization}/menu/manage',
            [
                OrganizationMenuController::class,
                'index',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.manage');


        /*
         * =====================================================
         * КАТЕГОРИИ МЕНЮ
         * =====================================================
         */


        // Форма создания категории

        Route::get(
            '/{organization}/menu/sections/create',
            [
                OrganizationMenuController::class,
                'createSection',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.sections.create');


        // Сохранение категории

        Route::post(
            '/{organization}/menu/sections',
            [
                OrganizationMenuController::class,
                'storeSection',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.sections.store');


        // Форма редактирования категории

        Route::get(
            '/{organization}/menu/sections/{section}/edit',
            [
                OrganizationMenuController::class,
                'editSection',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.sections.edit');


        // Обновление категории

        Route::put(
            '/{organization}/menu/sections/{section}',
            [
                OrganizationMenuController::class,
                'updateSection',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.sections.update');


        // Удаление категории

        Route::delete(
            '/{organization}/menu/sections/{section}',
            [
                OrganizationMenuController::class,
                'destroySection',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.sections.destroy');


        /*
         * =====================================================
         * БЛЮДА
         * =====================================================
         */


        // Форма создания блюда

        Route::get(
            '/{organization}/menu/sections/{section}/items/create',
            [
                OrganizationMenuController::class,
                'createItem',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.items.create');


        // Сохранение блюда

        Route::post(
            '/{organization}/menu/sections/{section}/items',
            [
                OrganizationMenuController::class,
                'storeItem',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.items.store');


        // Форма редактирования блюда

        Route::get(
            '/{organization}/menu/sections/{section}/items/{item}/edit',
            [
                OrganizationMenuController::class,
                'editItem',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.items.edit');


        // Обновление блюда

        Route::put(
            '/{organization}/menu/sections/{section}/items/{item}',
            [
                OrganizationMenuController::class,
                'updateItem',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.items.update');


        // Удаление блюда

        Route::delete(
            '/{organization}/menu/sections/{section}/items/{item}',
            [
                OrganizationMenuController::class,
                'destroyItem',
            ]
        )
            ->middleware('organization.manager')
            ->name('menu.items.destroy');
    });


/*
|--------------------------------------------------------------------------
| КОШЕЛЁК
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('wallet')
    ->name('wallet.')
    ->group(function () {

        Route::get(
            '/',
            [WalletController::class, 'wallet']
        )->name('index');

        Route::get(
            '/transfer',
            [WalletController::class, 'showTransferForm']
        )->name('transfer.form');

        Route::post(
            '/transfer',
            [WalletController::class, 'transfer']
        )->name('transfer.submit');

        Route::get(
            '/history',
            [WalletController::class, 'history']
        )->name('history');
    });


/*
|--------------------------------------------------------------------------
| ЗАДАЧИ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('tasks')
    ->name('tasks.')
    ->group(function () {

        // Просмотр задач
        Route::get(
            '/',
            [TasksController::class, 'list']
        )->name('list');

        Route::get(
            '/{task}',
            [TasksController::class, 'show']
        )->name('show');


        // Создание
        Route::get(
            '/create',
            [TasksController::class, 'create']
        )->name('create');

        Route::post(
            '/store',
            [TasksController::class, 'store']
        )->name('store');


        // Редактирование
        Route::get(
            '/{task}/edit',
            [TasksController::class, 'edit']
        )->name('edit');

        Route::put(
            '/{task}',
            [TasksController::class, 'update']
        )->name('update');

        Route::delete(
            '/{task}',
            [TasksController::class, 'destroy']
        )->name('destroy');


        // Взаимодействие
        Route::post(
            '/{task}/bid',
            [TasksController::class, 'bid']
        )->name('bid');

        Route::post(
            '/{task}/like',
            [TasksController::class, 'like']
        )->name('like');

        Route::post(
            '/{task}/dislike',
            [TasksController::class, 'dislike']
        )->name('dislike');


        // Работа
        Route::post(
            '/{task}/start-work',
            [TasksController::class, 'startWork']
        )->name('start_work');

        Route::post(
            '/{task}/complete',
            [TasksController::class, 'complete']
        )->name('complete');

        Route::post(
            '/{task}/freelancer-complete',
            [TasksController::class, 'freelancerComplete']
        )->name('freelancerComplete');

        Route::post(
            '/{task}/accept',
            [TasksController::class, 'acceptTask']
        )->name('accept');

        Route::post(
            '/{task}/revision',
            [TasksController::class, 'requestRevision']
        )->name('revision');

        Route::post(
            '/{task}/continue',
            [TasksController::class, 'continueTask']
        )->name('continue');

        Route::post(
            '/{task}/fail',
            [TasksController::class, 'fail']
        )->name('fail');


        // Оценка и оплата
        Route::post(
            '/{task}/rate',
            [TasksController::class, 'rate']
        )->name('rate');

        Route::post(
            '/{task}/accept-bid/{bid}',
            [TasksController::class, 'acceptBid']
        )->name('accept-bid');
    });


/*
|--------------------------------------------------------------------------
| АЛЬТЕРНАТИВНОЕ СОЗДАНИЕ ЗАДАЧИ
|--------------------------------------------------------------------------
*/

Route::get(
    '/addtask',
    [TasksController::class, 'create']
)
    ->name('addtask')
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| BIDS
|--------------------------------------------------------------------------
*/

Route::post(
    '/bids/{bid}/accept',
    [BidController::class, 'accept']
)->name('bids.accept');


/*
|--------------------------------------------------------------------------
| КАТЕГОРИИ ЗАДАЧ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('taskscategories')
    ->name('taskscategories.')
    ->group(function () {

        Route::get(
            '/',
            [CategoryTasksController::class, 'index']
        )->name('index');

        Route::get(
            '/create',
            [CategoryTasksController::class, 'create']
        )->name('create');

        Route::post(
            '/',
            [CategoryTasksController::class, 'store']
        )->name('store');

        Route::get(
            '/{taskCategory}/edit',
            [CategoryTasksController::class, 'edit']
        )->name('edit');

        Route::put(
            '/{taskCategory}',
            [CategoryTasksController::class, 'update']
        )->name('update');

        Route::delete(
            '/{taskCategory}',
            [CategoryTasksController::class, 'destroy']
        )->name('destroy');
    });


/*
|--------------------------------------------------------------------------
| ПРОФИЛЬ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('profile')
    ->name('user_profile.')
    ->group(function () {

        // Свой / расширенный профиль
        Route::get(
            '/',
            [UserProfileController::class, 'index']
        )->name('index');


        // Чужой профиль
        Route::get(
            '/{id}',
            [UserProfileController::class, 'index']
        )
            ->whereNumber('id')
            ->name('show');


        // Создание профиля
        Route::get(
            '/create',
            [UserProfileController::class, 'create']
        )->name('create');

        Route::post(
            '/store',
            [UserProfileController::class, 'store']
        )->name('store');


        // Редактирование
        Route::get(
            '/edit',
            [UserProfileController::class, 'edit']
        )->name('edit');

        Route::put(
            '/update',
            [UserProfileController::class, 'updateFullProfile']
        )->name('update');


        // Удаление аккаунта
        Route::delete(
            '/',
            [UserProfileController::class, 'destroy']
        )->name('destroy');
    });


/*
|--------------------------------------------------------------------------
| ЗАГРУЗКА ИЗОБРАЖЕНИЙ CKEDITOR
|--------------------------------------------------------------------------
*/

Route::post(
    '/upload-image',
    [UploadController::class, 'uploadImage']
)->name('upload.image');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
])
    ->get(
        '/dashboard',
        function () {
            return view('dashboard');
        }
    )
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';