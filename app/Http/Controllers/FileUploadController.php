<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

final class FileUploadController extends Controller
{
    public function process(Request $request): string
    {
        // Мы не знаем имя переданного файла, поэтому нам нужно захватить
        // все файлы из запроса и взять первый файл.
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();

        if (empty($files)) {
            abort(422, 'Файлы не были загружены.');
        }

        if (count($files) > 1) {
            abort(422, 'Только 1 файл может быть загружен за раз.');
        }
        // Теперь, когда мы знаем, что есть только один ключ, мы можем
        // использовать его для получения файла из запроса.
        $requestKey = array_key_first($files);

        // Если мы разрешаем загрузку нескольких файлов, поле запроса будет
        // представлять собой массив с одним файлов, а не один файл (например,
        // `csv[]`, а не `csv`). итак, нам нужно получить первый файл из массива.
        // В противном случае мы можем предположить, что загруженный файл
        // предназначен для ввода одного файла, и мы можем его непосредственно
        // из запроса.
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        // Сохраняем файл во временной локации и возвращаем её для использования
        // FilePond
        return $file->store(
            path: 'tmp/'.now()->timestamp.'-'.Str::random(20)
        );
    }
}







