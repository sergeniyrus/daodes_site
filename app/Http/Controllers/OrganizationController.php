<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    /**
     * Список организаций текущего пользователя.
     */
    public function index(): View
    {
        $user = Auth::user();

        $organizations = $user->organizations()
            ->withPivot([
                'role',
                'status',
            ])
            ->get();

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Форма создания организации.
     */
    public function create(): View
    {
        return view('organizations.create');
    }

    /**
     * Создание организации.
     *
     * Текущий пользователь автоматически становится
     * менеджером созданной организации.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $user = Auth::user();

        $organization = DB::transaction(function () use ($validated, $user) {

            $organization = Organization::create([
                'name' => $validated['name'],
                'status' => 'active',
            ]);

            $organization->users()->attach($user->id, [
                'role' => 'manager',
                'status' => 'active',
            ]);

            return $organization;
        });

        return redirect()
            ->route('organizations.manage', $organization)
            ->with(
                'success',
                __('organizations.created_successfully')
            );
    }

    /**
     * Панель управления организацией.
     *
     * Доступ проверяется middleware:
     * organization.manager
     */
    public function manage(Organization $organization): View
    {
        /*
         * Получаем участников организации
         * вместе с их профилями.
         */
        $members = $organization->users()
            ->with('profile')
            ->withPivot([
                'role',
                'status',
            ])
            ->get();

        /*
         * ID пользователей, которые уже состоят
         * в организации.
         */
        $memberIds = $members->pluck('id');

        /*
         * Пользователи DAODES, которых можно добавить
         * в организацию.
         *
         * Профиль загружаем сразу,
         * чтобы впоследствии можно было использовать avatar_url.
         */
        $availableUsers = User::query()
            ->with('profile')
            ->whereNotIn('id', $memberIds)
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view('organizations.manage', [
            'organization' => $organization,
            'members' => $members,
            'availableUsers' => $availableUsers,
        ]);
    }

    /**
     * Добавление сотрудника в организацию.
     *
     * Доступ проверяется middleware:
     * organization.manager
     */
    public function storeEmployee(
        Request $request,
        Organization $organization
    ): RedirectResponse {
        $user = Auth::user();

        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ]);

        /*
         * Проверяем, что пользователь ещё не является
         * участником организации.
         */
        $alreadyMember = $organization->users()
            ->where('users.id', $validated['user_id'])
            ->exists();

        if ($alreadyMember) {
            return redirect()
                ->route('organizations.manage', $organization)
                ->with(
                    'error',
                    __('organizations.messages.already_member')
                );
        }

        /*
         * Нельзя добавить самого себя как employee.
         */
        if ((int) $validated['user_id'] === (int) $user->id) {
            return redirect()
                ->route('organizations.manage', $organization)
                ->with(
                    'error',
                    __('organizations.messages.cannot_add_self')
                );
        }

        $organization->users()->attach($validated['user_id'], [
            'role' => 'employee',
            'status' => 'active',
        ]);

        return redirect()
            ->route('organizations.manage', $organization)
            ->with(
                'success',
                __('organizations.messages.employee_added')
            );
    }

    /**
     * Изменение роли сотрудника.
     *
     * Доступ проверяется middleware:
     * organization.manager
     */
    public function updateEmployeeRole(
        Request $request,
        Organization $organization,
        User $user
    ): RedirectResponse {
        $manager = Auth::user();

        /*
         * Проверяем, что пользователь действительно
         * является участником этой организации.
         */
        $member = $organization->users()
            ->where('users.id', $user->id)
            ->first();

        abort_unless($member, 404);

        /*
         * Проверяем допустимую роль.
         */
        $validated = $request->validate([
            'role' => [
                'required',
                'in:manager,employee',
            ],
        ]);

        /*
         * Нельзя снять роль с самого себя.
         *
         * Иначе менеджер может случайно оставить
         * организацию без управляющего.
         */
        if ((int) $manager->id === (int) $user->id) {
            return redirect()
                ->route('organizations.manage', $organization)
                ->with(
                    'error',
                    __('organizations.messages.cannot_change_own_role')
                );
        }

        /*
         * Нельзя убрать последнего активного менеджера.
         */
        if (
            $member->pivot->role === 'manager' &&
            $validated['role'] === 'employee'
        ) {
            $activeManagersCount = $organization->users()
                ->wherePivot('role', 'manager')
                ->wherePivot('status', 'active')
                ->count();

            if ($activeManagersCount <= 1) {
                return redirect()
                    ->route('organizations.manage', $organization)
                    ->with(
                        'error',
                        __('organizations.messages.last_manager')
                    );
            }
        }

        $organization->users()->updateExistingPivot(
            $user->id,
            [
                'role' => $validated['role'],
            ]
        );

        return redirect()
            ->route('organizations.manage', $organization)
            ->with(
                'success',
                __('organizations.messages.role_updated')
            );
    }

    /**
     * Изменение статуса сотрудника.
     *
     * Доступ проверяется middleware:
     * organization.manager
     */
    public function updateEmployeeStatus(
        Request $request,
        Organization $organization,
        User $user
    ): RedirectResponse {
        $manager = Auth::user();

        /*
         * Проверяем участие пользователя.
         */
        $member = $organization->users()
            ->where('users.id', $user->id)
            ->first();

        abort_unless($member, 404);

        $validated = $request->validate([
            'status' => [
                'required',
                'in:active,inactive,blocked',
            ],
        ]);

        /*
         * Нельзя изменить собственный статус.
         */
        if ((int) $manager->id === (int) $user->id) {
            return redirect()
                ->route('organizations.manage', $organization)
                ->with(
                    'error',
                    __('organizations.messages.cannot_change_own_status')
                );
        }

        /*
         * Нельзя деактивировать/заблокировать
         * последнего активного менеджера.
         */
        if (
            $member->pivot->role === 'manager' &&
            $member->pivot->status === 'active' &&
            $validated['status'] !== 'active'
        ) {
            $activeManagersCount = $organization->users()
                ->wherePivot('role', 'manager')
                ->wherePivot('status', 'active')
                ->count();

            if ($activeManagersCount <= 1) {
                return redirect()
                    ->route('organizations.manage', $organization)
                    ->with(
                        'error',
                        __('organizations.messages.last_manager')
                    );
            }
        }

        $organization->users()->updateExistingPivot(
            $user->id,
            [
                'status' => $validated['status'],
            ]
        );

        return redirect()
            ->route('organizations.manage', $organization)
            ->with(
                'success',
                __('organizations.messages.status_updated')
            );
    }

    /**
     * Удаление сотрудника из организации.
     *
     * Доступ проверяется middleware:
     * organization.manager
     */
    public function destroyEmployee(
        Organization $organization,
        User $user
    ): RedirectResponse {
        $manager = Auth::user();

        /*
         * Проверяем, что пользователь действительно
         * является участником организации.
         */
        $member = $organization->users()
            ->where('users.id', $user->id)
            ->first();

        abort_unless($member, 404);

        /*
         * Нельзя удалить самого себя.
         */
        if ((int) $manager->id === (int) $user->id) {
            return redirect()
                ->route('organizations.manage', $organization)
                ->with(
                    'error',
                    __('organizations.messages.cannot_remove_self')
                );
        }

        /*
         * Нельзя удалить последнего активного менеджера.
         */
        if (
            $member->pivot->role === 'manager' &&
            $member->pivot->status === 'active'
        ) {
            $activeManagersCount = $organization->users()
                ->wherePivot('role', 'manager')
                ->wherePivot('status', 'active')
                ->count();

            if ($activeManagersCount <= 1) {
                return redirect()
                    ->route('organizations.manage', $organization)
                    ->with(
                        'error',
                        __('organizations.messages.last_manager')
                    );
            }
        }

        $organization->users()->detach($user->id);

        return redirect()
            ->route('organizations.manage', $organization)
            ->with(
                'success',
                __('organizations.messages.employee_removed')
            );
    }
}