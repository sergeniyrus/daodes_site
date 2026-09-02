<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuAdminController extends Controller
{
    /**
     * Управление меню организации.
     *
     * Доступ дополнительно защищается middleware:
     * organization.manager
     */
    public function index(Organization $organization): View
    {
        $sections = $organization->menuSections()
            ->with([
                'items' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->orderBy('sort_order')
            ->get();

        return view('organizations.menu.index', [
            'organization' => $organization,
            'sections' => $sections,
        ]);
    }

    /**
     * Форма создания раздела меню.
     */
    public function createSection(Organization $organization): View
    {
        return view('organizations.menu.sections.create', [
            'organization' => $organization,
        ]);
    }

    /**
     * Создание раздела меню.
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
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $organization->menuSections()->create([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('organizations.menu.index', $organization)
            ->with(
                'success',
                'Раздел меню успешно создан.'
            );
    }

    /**
     * Форма редактирования раздела.
     */
    public function editSection(
        Organization $organization,
        MenuSection $section
    ): View {
        $this->checkSectionBelongsToOrganization(
            $organization,
            $section
        );

        return view('organizations.menu.sections.edit', [
            'organization' => $organization,
            'section' => $section,
        ]);
    }

    /**
     * Обновление раздела.
     */
    public function updateSection(
        Request $request,
        Organization $organization,
        MenuSection $section
    ): RedirectResponse {
        $this->checkSectionBelongsToOrganization(
            $organization,
            $section
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $section->update([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('organizations.menu.index', $organization)
            ->with(
                'success',
                'Раздел меню успешно обновлён.'
            );
    }

    /**
     * Удаление раздела.
     *
     * Вместе с разделом удаляются его блюда.
     */
    public function destroySection(
        Organization $organization,
        MenuSection $section
    ): RedirectResponse {
        $this->checkSectionBelongsToOrganization(
            $organization,
            $section
        );

        $section->items()->delete();
        $section->delete();

        return redirect()
            ->route('organizations.menu.index', $organization)
            ->with(
                'success',
                'Раздел меню удалён.'
            );
    }

    /**
     * Форма создания блюда.
     */
    public function createItem(
        Organization $organization,
        MenuSection $section
    ): View {
        $this->checkSectionBelongsToOrganization(
            $organization,
            $section
        );

        return view('organizations.menu.items.create', [
            'organization' => $organization,
            'section' => $section,
        ]);
    }

    /**
     * Создание блюда.
     */
    public function storeItem(
        Request $request,
        Organization $organization,
        MenuSection $section
    ): RedirectResponse {
        $this->checkSectionBelongsToOrganization(
            $organization,
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
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $section->items()->create([
            'name' => $validated['name'],
            'weight' => $validated['weight'] ?? null,
            'unit' => $validated['unit'] ?? null,
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('organizations.menu.index', $organization)
            ->with(
                'success',
                'Блюдо успешно добавлено.'
            );
    }

    /**
     * Форма редактирования блюда.
     */
    public function editItem(
        Organization $organization,
        MenuSection $section,
        MenuItem $item
    ): View {
        $this->checkSectionBelongsToOrganization(
            $organization,
            $section
        );

        $this->checkItemBelongsToSection(
            $section,
            $item
        );

        return view('organizations.menu.items.edit', [
            'organization' => $organization,
            'section' => $section,
            'item' => $item,
        ]);
    }

    /**
     * Обновление блюда.
     */
    public function updateItem(
        Request $request,
        Organization $organization,
        MenuSection $section,
        MenuItem $item
    ): RedirectResponse {
        $this->checkSectionBelongsToOrganization(
            $organization,
            $section
        );

        $this->checkItemBelongsToSection(
            $section,
            $item
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
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
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
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('organizations.menu.index', $organization)
            ->with(
                'success',
                'Блюдо успешно обновлено.'
            );
    }

    /**
     * Удаление блюда.
     */
    public function destroyItem(
        Organization $organization,
        MenuSection $section,
        MenuItem $item
    ): RedirectResponse {
        $this->checkSectionBelongsToOrganization(
            $organization,
            $section
        );

        $this->checkItemBelongsToSection(
            $section,
            $item
        );

        $item->delete();

        return redirect()
            ->route('organizations.menu.index', $organization)
            ->with(
                'success',
                'Блюдо удалено.'
            );
    }

    /**
     * Проверка принадлежности раздела организации.
     */
    private function checkSectionBelongsToOrganization(
        Organization $organization,
        MenuSection $section
    ): void {
        abort_unless(
            (int) $section->organization_id === (int) $organization->id,
            404
        );
    }

    /**
     * Проверка принадлежности блюда разделу.
     */
    private function checkItemBelongsToSection(
        MenuSection $section,
        MenuItem $item
    ): void {
        abort_unless(
            (int) $item->menu_section_id === (int) $section->id,
            404
        );
    }
}