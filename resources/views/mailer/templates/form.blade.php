@extends('mailer.admin_layout')
@section('title', $mode === 'edit' ? 'Редактировать шаблон' : 'Просмотр шаблона')

@section('content')
    <h2>{{ $mode === 'edit' ? 'Редактирование шаблона: ' : '' }}{{ $template->name }}</h2>

    <div style="margin:20px 0;">
        @if ($mode === 'edit')
            <a href="{{ route('mailer.templates.index') }}" class="btn">Назад к списку</a>
        @else
            <a href="{{ route('mailer.templates.edit', $template) }}" class="btn">Редактировать</a>
            <a href="{{ route('mailer.templates.index') }}" class="btn">Назад к списку</a>
        @endif
    </div>

    @if ($mode === 'edit')
        <form method="POST" action="{{ route('mailer.templates.update', $template) }}">
            @csrf
            @method('PUT')
    @endif

    <style>
        .split-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: flex-start;
        }
        .split-left, .split-right {
            flex: 1;
            min-width: 300px;
        }
        @media(max-width: 768px) {
            .split-container { flex-direction: column; }
        }

        .split-left input, .split-left textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #666;
            background: #222;
            color: #eee;
        }

        .editor-modes {
            margin-bottom: 15px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        .editor-modes button {
            background: #333;
            color: #eee;
            border: 1px solid #666;
            border-radius: 5px;
            padding: 8px 16px;
            margin-right: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }
        .editor-modes button:hover { background: #444; border-color: #888; }
        .editor-modes button.active { background: #0ab; color: #111; border-color: #0ab; font-weight: 600; }

        #preview-frame {
            width: 100%;
            height: 600px;
            border: 1px solid #444;
            border-radius: 8px;
            background: #111;
        }

        .code-editor-wrapper {
            border: 1px solid #444;
            border-radius: 8px;
            overflow: hidden;
            background: #282a36;
        }

        .CodeMirror {
            height: 500px !important;
            font-size: 14px !important;
            background: #282a36 !important;
            color: #f8f8f2 !important;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace !important;
            line-height: 1.5 !important;
        }
        .CodeMirror-gutters { background: #282a36 !important; border-right: 1px solid #444 !important; }
        .CodeMirror-linenumber { color: #6272a4 !important; padding: 0 8px !important; }
    </style>

    <div class="split-container">
        <div class="split-left">
            @if ($mode === 'edit')
                <label style="color: #ccc; margin-bottom: 5px; display: block;">Название шаблона</label>
                <input name="name" value="{{ $template->name }}" required>
                <label style="color: #ccc; margin-bottom: 5px; display: block;">Тема письма</label>
                <input name="subject" value="{{ $template->subject }}" required>
            @else
                <div style="background: #333; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <p style="margin: 0;"><strong style="color: #0ab;">Тема письма:</strong> <span style="color: #eee;">{{ $template->subject }}</span></p>
                </div>
            @endif

            <div class="editor-modes">
                <button type="button" id="mode-code" class="active">Исходный код</button>
                <button type="button" id="mode-preview">Предпросмотр</button>
            </div>

            <textarea id="code-editor" name="body" style="display:none;">{!! $template->body !!}</textarea>
            <div id="code-container" class="code-editor-wrapper"></div>
            <div id="preview" style="display:none; margin-top: 10px;">
                <iframe id="preview-frame" frameborder="0"></iframe>
            </div>

            <div style="background: #333; padding: 15px; border-radius: 5px; margin-top: 20px;">
                <p style="margin: 0; font-size: 14px; color: #aaa; line-height: 1.6;">
                    <strong style="color: #0ab;">Доступные переменные:</strong><br>
                    <code style="background: #222; padding: 2px 6px; border-radius: 3px; color: #f1fa8c;">@{{ name }}</code> — для обращения к читателю<br>
                    <code style="background: #222; padding: 2px 6px; border-radius: 3px; color: #f1fa8c;">@{{ tracker }}</code> — для вставки трекера прочтения
                </p>
            </div>

            @if ($mode === 'edit')
                <button type="submit" class="btn" style="margin-top: 20px; background: #0ab; color: #111; border: none; padding: 12px 24px; border-radius: 5px; font-weight: 600; cursor: pointer;">
                    Сохранить
                </button>
            @endif
        </div>
    </div>

    @if ($mode === 'edit')
        </form>
    @endif

    <!-- CodeMirror: стили -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/theme/dracula.min.css">

    <!-- CodeMirror: ядро и режимы -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/mode/htmlmixed/htmlmixed.min.js"></script>

    <!-- 🔥 Обязательно для кастомной подсветки -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/addon/mode/multiplex.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/addon/edit/closetag.min.js"></script>

    @verbatim
    <script>
        (function() {
            'use strict';

            let codeEditor = null;
            let currentMode = 'code';

            function renderPreviewHtml(html) {
                return html
                    .replace(/\@\{\{\s*name\s*\}\}/g, '<strong style="color:#0ab;">Иван Петров</strong>')
                    .replace(/\@\{\{\s*tracker\s*\}\}/g, '<img src="/images/tracker.png" width="1" height="1" style="border:1px solid #0ab; opacity:0.5;">');
            }

            function updatePreview() {
                const iframe = document.getElementById('preview-frame');
                const doc = iframe.contentDocument || iframe.contentWindow.document;
                const rawHtml = codeEditor.getValue();
                const previewHtml = renderPreviewHtml(rawHtml);

                const fullHtml = `<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { background: #111; color: #eee; font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; max-width: 800px; margin: 0 auto; }
        a { color: #0ab; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
${previewHtml}
</body>
</html>`;

                doc.open();
                doc.write(fullHtml);
                doc.close();
            }

            function switchMode(mode) {
                currentMode = mode;
                document.querySelectorAll('.editor-modes button').forEach(b => b.classList.remove('active'));
                document.getElementById('mode-' + mode)?.classList.add('active');

                const codeContainer = document.getElementById('code-container');
                const preview = document.getElementById('preview');

                if (mode === 'code') {
                    codeContainer.style.display = 'block';
                    preview.style.display = 'none';
                } else if (mode === 'preview') {
                    codeContainer.style.display = 'none';
                    preview.style.display = 'block';
                    updatePreview();
                }
            }

            function initCodeMirror() {
                const textarea = document.getElementById('code-editor');
                const container = document.getElementById('code-container');

                // Регистрация кастомного режима с подсветкой
                CodeMirror.defineMode("email-html", function(config) {
                    return CodeMirror.multiplexingMode(
                        CodeMirror.getMode(config, "htmlmixed"),
                        {
                            open: 'style="',
                            close: '"',
                            mode: CodeMirror.getMode(config, "css"),
                            delimStyle: null,
                            innerStyle: "property"
                        },
                        {
                            open: "@{{",
                            close: "}}",
                            mode: CodeMirror.getMode(config, "text/plain"),
                            delimStyle: "email-delimiter",
                            innerStyle: "email-variable"
                        }
                    );
                });

                // Кастомные цвета для переменных и CSS
                const style = document.createElement('style');
                style.textContent = `
                    .cm-email-delimiter { color: #bd93f9 !important; font-weight: bold; }
                    .cm-email-variable { color: #ff79c6 !important; font-weight: bold; background: rgba(255,121,198,0.1) !important; padding: 0 2px; border-radius: 2px; }
                    .cm-property { color: #50fa7b !important; }
                `;
                document.head.appendChild(style);

                codeEditor = CodeMirror(container, {
                    value: textarea.value,
                    mode: "email-html",
                    theme: "dracula",
                    lineNumbers: true,
                    lineWrapping: true,
                    indentUnit: 2,
                    autoCloseTags: true
                });

                codeEditor.on('change', () => {
                    textarea.value = codeEditor.getValue();
                    if (currentMode === 'preview') {
                        updatePreview();
                    }
                });
            }

            function init() {
                initCodeMirror();
                document.getElementById('mode-code').addEventListener('click', () => switchMode('code'));
                document.getElementById('mode-preview').addEventListener('click', () => switchMode('preview'));
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
    @endverbatim

@endsection