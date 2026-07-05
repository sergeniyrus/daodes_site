@extends('template')

@section('title_page')
{{-- тайтл --}}
@endsection

@section('main')
<div class="container">
    <h1>Управление версиями приложения</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.app_versions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Version Code</label>
            <input type="number" name="version_code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Version Name</label>
            <input type="text" name="version_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>APK File</label>
            <input type="file" name="apk_file" class="form-control" accept=".apk" required>
        </div>

        <div class="mb-3">
            <label>Changelog (каждая строка = отдельное изменение)</label>
            <textarea name="changelog" class="form-control" rows="5"></textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="force_update" value="1" class="form-check-input">
            <label class="form-check-label">Принудительное обновление</label>
        </div>

        <button type="submit" class="btn btn-primary">Загрузить новую версию</button>
    </form>

    <hr>

    <h2>Список версий</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Version Code</th>
                <th>Version Name</th>
                <th>Force Update</th>
                <th>APK</th>
                <th>Дата</th>
            </tr>
        </thead>
        <tbody>
            @foreach($versions as $version)
                <tr>
                    <td>{{ $version->id }}</td>
                    <td>{{ $version->version_code }}</td>
                    <td>{{ $version->version_name }}</td>
                    <td>{{ $version->force_update ? 'Да' : 'Нет' }}</td>
                    <td>
                        <a href="{{ asset('storage/apk/' . $version->apk_file) }}" target="_blank">
                            Скачать APK
                        </a>
                    </td>
                    <td>{{ $version->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection