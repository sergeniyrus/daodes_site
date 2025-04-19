document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[id$="-form"]'); // подойдет и для news-form и task-form
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const contentRu = typeof editor_ru !== 'undefined' ? editor_ru.getData().trim() : '';
        const contentEn = typeof editor_en !== 'undefined' ? editor_en.getData().trim() : '';

        const textareaRu = document.querySelector('[name="content_ru"]');
        const textareaEn = document.querySelector('[name="content_en"]');
        if (textareaRu) textareaRu.value = contentRu;
        if (textareaEn) textareaEn.value = contentEn;

        const titleRu = document.querySelector('[name="title_ru"]').value.trim();
        const titleEn = document.querySelector('[name="title_en"]').value.trim();

        if (!titleRu || !titleEn || !contentRu || !contentEn) {
            e.preventDefault();

            alert('Все поля (заголовки и описания) должны быть заполнены на обоих языках.');

            if (!contentRu && typeof editor_ru !== 'undefined') {
                editor_ru.editing.view.focus();
            } else if (!contentEn && typeof editor_en !== 'undefined') {
                editor_en.editing.view.focus();
            }

            return false;
        }

        return true;
    });
});
