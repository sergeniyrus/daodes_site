<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationMenuController extends Controller
{
    /**
     * =========================================================
     * ГЛАВНАЯ СТРАНИЦА МЕНЮ ОРГАНИЗАЦИИ
     * =========================================================
     *
     * GET:
     * /organizations/{organization}/menu
     *
     * View:
     * resources/views/organizations/menu/index.blade.php
     *
     * Route:
     * organizations.menu.index
     */
    public function index(Organization $organization): View
    {
        $sections = $organization->menuSections()
            ->with([
                'items',
            ])
            ->orderBy('sort_order')
            ->get();

        return view('organizations.menu.index', [
            'organization' => $organization,
            'sections' => $sections,
        ]);
    }

    /**
     * =========================================================
     * ФОРМА СОЗДАНИЯ КАТЕГОРИИ МЕНЮ
     * =========================================================
     *
     * GET:
     * /organizations/{organization}/menu/sections/create
     *
     * View:
     * resources/views/organizations/menu/sections/create.blade.php
     *
     * Route:
     * organizations.menu.sections.create
     */
    public function createSection(
        Organization $organization
    ): View {
        return view('organizations.menu.sections.create', [
            'organization' => $organization,
        ]);
    }

    /**
     * =========================================================
     * СОЗДАНИЕ КАТЕГОРИИ МЕНЮ
     * =========================================================
     *
     * POST:
     * /organizations/{organization}/menu/sections
     *
     * Route:
     * organizations.menu.sections.store
     */
    public function storeSection(
        Request $request,
        Organization $organization
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $maxSortOrder = $organization->menuSections()
            ->max('sort_order');

        $organization->menuSections()->create([
            'name' => $validated['name'],
            'sort_order' => ($maxSortOrder ?? 0) + 1,
            'is_active' => true,
        ]);

        return redirect()
            ->route(
                'organizations.menu.index',
                $organization
            )
            ->with(
                'success',
                'Категория меню успешно добавлена.'
            );
    }

    /**
     * =========================================================
     * ФОРМА РЕДАКТИРОВАНИЯ КАТЕГОРИИ
     * =========================================================
     *
     * GET:
     * /organizations/{organization}/menu/sections/{section}/edit
     *
     * View:
     * resources/views/organizations/menu/sections/edit.blade.php
     *
     * Route:
     * organizations.menu.sections.edit
     */
    public function editSection(
        Organization $organization,
        MenuSection $section
    ): View {
        $this->ensureSectionBelongsToOrganization(
            $section,
            $organization
        );

        return view('organizations.menu.sections.edit', [
            'organization' => $organization,
            'section' => $section,
        ]);
    }

    /**
     * =========================================================
     * ОБНОВЛЕНИЕ КАТЕГОРИИ
     * =========================================================
     *
     * PUT:
     * /organizations/{organization}/menu/sections/{section}
     *
     * Route:
     * organizations.menu.sections.update
     */
    public function updateSection(
        Request $request,
        Organization $organization,
        MenuSection $section
    ): RedirectResponse {
        $this->ensureSectionBelongsToOrganization(
            $section,
            $organization
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $section->update([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route(
                'organizations.menu.index',
                $organization
            )
            ->with(
                'success',
                'Категория меню успешно изменена.'
            );
    }

    /**
     * =========================================================
     * УДАЛЕНИЕ КАТЕГОРИИ
     * =========================================================
     *
     * Вместе с категорией удаляются её блюда.
     *
     * DELETE:
     * /organizations/{organization}/menu/sections/{section}
     *
     * Route:
     * organizations.menu.sections.destroy
     */
    public function destroySection(
        Organization $organization,
        MenuSection $section
    ): RedirectResponse {
        $this->ensureSectionBelongsToOrganization(
            $section,
            $organization
        );

        $section->items()->delete();

        $section->delete();

        return redirect()
            ->route(
                'organizations.menu.index',
                $organization
            )
            ->with(
                'success',
                'Категория меню удалена.'
            );
    }

    /**
     * =========================================================
     * ФОРМА СОЗДАНИЯ БЛЮДА
     * =========================================================
     *
     * GET:
     * /organizations/{organization}/menu/sections/{section}/items/create
     *
     * View:
     * resources/views/organizations/menu/items/create.blade.php
     *
     * Route:
     * organizations.menu.items.create
     */
    public function createItem(
        Organization $organization,
        MenuSection $section
    ): View {
        $this->ensureSectionBelongsToOrganization(
            $section,
            $organization
        );

        return view('organizations.menu.items.create', [
            'organization' => $organization,
            'section' => $section,
        ]);
    }

    /**
     * =========================================================
     * СОЗДАНИЕ БЛЮДА
     * =========================================================
     *
     * POST:
     * /organizations/{organization}/menu/sections/{section}/items
     *
     * Route:
     * organizations.menu.items.store
     */
    public function storeItem(
        Request $request,
        Organization $organization,
        MenuSection $section
    ): RedirectResponse {
        $this->ensureSectionBelongsToOrganization(
            $section,
            $organization
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $maxSortOrder = $section->items()
            ->max('sort_order');

        $section->items()->create([
            'name' => $validated['name'],
            'weight' => $validated['weight'] ?? null,
            'unit' => $validated['unit'] ?? null,
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'sort_order' => ($maxSortOrder ?? 0) + 1,
            'is_active' => true,
        ]);

        return redirect()
            ->route(
                'organizations.menu.index',
                $organization
            )
            ->with(
                'success',
                'Блюдо успешно добавлено.'
            );
    }

    /**
     * =========================================================
     * ФОРМА РЕДАКТИРОВАНИЯ БЛЮДА
     * =========================================================
     *
     * GET:
     * /organizations/{organization}/menu/sections/{section}/items/{item}/edit
     *
     * View:
     * resources/views/organizations/menu/items/edit.blade.php
     *
     * Route:
     * organizations.menu.items.edit
     */
    public function editItem(
        Organization $organization,
        MenuSection $section,
        MenuItem $item
    ): View {
        $this->ensureSectionBelongsToOrganization(
            $section,
            $organization
        );

        $this->ensureItemBelongsToSection(
            $item,
            $section
        );

        return view('organizations.menu.items.edit', [
            'organization' => $organization,
            'section' => $section,
            'item' => $item,
        ]);
    }

    /**
     * =========================================================
     * ОБНОВЛЕНИЕ БЛЮДА
     * =========================================================
     *
     * PUT:
     * /organizations/{organization}/menu/sections/{section}/items/{item}
     *
     * Route:
     * organizations.menu.items.update
     */
    public function updateItem(
        Request $request,
        Organization $organization,
        MenuSection $section,
        MenuItem $item
    ): RedirectResponse {
        $this->ensureSectionBelongsToOrganization(
            $section,
            $organization
        );

        $this->ensureItemBelongsToSection(
            $item,
            $section
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $item->update([
            'name' => $validated['name'],
            'weight' => $validated['weight'] ?? null,
            'unit' => $validated['unit'] ?? null,
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route(
                'organizations.menu.index',
                $organization
            )
            ->with(
                'success',
                'Блюдо успешно изменено.'
            );
    }

    /**
     * =========================================================
     * УДАЛЕНИЕ БЛЮДА
     * =========================================================
     *
     * DELETE:
     * /organizations/{organization}/menu/sections/{section}/items/{item}
     *
     * Route:
     * organizations.menu.items.destroy
     */
    public function destroyItem(
        Organization $organization,
        MenuSection $section,
        MenuItem $item
    ): RedirectResponse {
        $this->ensureSectionBelongsToOrganization(
            $section,
            $organization
        );

        $this->ensureItemBelongsToSection(
            $item,
            $section
        );

        $item->delete();

        return redirect()
            ->route(
                'organizations.menu.index',
                $organization
            )
            ->with(
                'success',
                'Блюдо удалено.'
            );
    }

    /**
     * =========================================================
     * ПРОВЕРКА КАТЕГОРИИ
     * =========================================================
     *
     * Категория должна принадлежать указанной организации.
     */
    private function ensureSectionBelongsToOrganization(
        MenuSection $section,
        Organization $organization
    ): void {
        abort_unless(
            (int) $section->organization_id === (int) $organization->id,
            404
        );
    }

    /**
     * =========================================================
     * ПРОВЕРКА БЛЮДА
     * =========================================================
     *
     * Блюдо должно принадлежать указанной категории.
     */
    private function ensureItemBelongsToSection(
        MenuItem $item,
        MenuSection $section
    ): void {
        abort_unless(
            (int) $item->menu_section_id === (int) $section->id,
            404
        );
    }
}