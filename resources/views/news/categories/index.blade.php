@extends('template')
@section('title_page', 'Управление категориями новостей')

@section('main')
<style>
    .container {
        padding: 20px;
        margin: 0 auto;
        text-align: center;
        max-width: 800px;
        background-color: rgba(20, 20, 20, 0.9);
        border-radius: 20px;
        border: 1px solid #d7fc09;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        color: #f8f9fa;
        font-family: 'Verdana', sans-serif;
        margin-top: 30px;
    }

    .new_post {
        width: 90%;
        margin: 20px auto;
        padding-bottom: 20px;
        color: #fff;
        background-color: rgba(30, 30, 30, 0.9);
        border: 1px solid #fff;
        font-size: 20px;
        border-radius: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        color: #fff;
    }

    .table th {
        background-color: #444;
    }

    .des-btn {
        display: inline-block;
        color: #ffffff;
        font-size: large;
        background: #0b0c18;
        padding: 10px 20px;
        border: 1px solid #d7fc09;
        border-radius: 10px;
        box-shadow: 0 0 20px #000;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        margin-top: 10px;
        cursor: pointer;
    }

    .des-btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #1a1a1a;
    }
        
</style>
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
                    <td>{{ $category->name }}</td>
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
