
let editor_ru, editor_en;

document.addEventListener('DOMContentLoaded', function () {
    ClassicEditor.create(document.querySelector('#editor-ru'))
        .then(editor => editor_ru = editor)
        .catch(console.error);

    ClassicEditor.create(document.querySelector('#editor-en'))
        .then(editor => editor_en = editor)
        .catch(console.error);
});
