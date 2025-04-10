@extends('template')
@section('title_page', 'Управление категориями новостей')

@section('main')

<link href="{{ asset('css/category.css') }}" rel="stylesheet">
<div class="container">
    <h2>{{ __('category.categories_title') }}</h2>
    <div class="new_post">
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>{{ __('category.category_name') }}</th>
                    <th>{{ __('category.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name_ru }} / {{ $category->name_en }}</td>
                    <td>
                        <a href="{{ route('newscategories.edit', $category->id) }}" class="des-btn" title="{{ __('category.edit') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('newscategories.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="des-btn" title="{{ __('category.delete') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('newscategories.create') }}" method="GET" style="display:inline;">
            <button type="submit" class="des-btn">
                {!! __('category.add_category_button') !!}
            </button>
        </form>
    </div>
</div>

@endsection
