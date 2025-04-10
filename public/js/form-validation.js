
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('news-form').addEventListener('submit', function (e) {
        if (editor_ru) {
            document.getElementById('content_ru').value = editor_ru.getData();
        }
        if (editor_en) {
            document.getElementById('content_en').value = editor_en.getData();
        }

        const contentRu = document.getElementById('content_ru').value.trim();
        const contentEn = document.getElementById('content_en').value.trim();
        const titleRu = document.getElementById('title_ru').value.trim();
        const titleEn = document.getElementById('title_en').value.trim();

        if (!contentRu || !contentEn || !titleRu || !titleEn) {
            e.preventDefault();
            alert('Content and Title are required in both languages');
            return false;
        }

        const content = document.getElementById('editor')?.value;
        if (content !== undefined && (!content || content.trim() === '')) {
            e.preventDefault();
            alert('Content is required');
            editor?.editing.view.focus();
            return false;
        }

        return true;
    });
});
