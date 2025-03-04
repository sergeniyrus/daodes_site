@extends('template')

@section('title_page')
Create an offer
@endsection

@section('main')
    <style>
        .container {
            padding: 20px;
            margin: 0 auto;
            max-width: 800px;
            background-color: rgba(20, 20, 20, 0.9);
            border-radius: 20px;
            border: 1px solid #d7fc09;
            color: #f8f9fa;
            font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
            margin-top: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .form-group label {
            color: #d7fc09;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input_dark,
        textarea {
            background-color: #1a1a1a;
            color: #a0ff08;
            border: 1px solid #d7fc09;
            border-radius: 5px;
            padding: 12px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .input_dark:focus,
        textarea:focus {
            border: 1px solid #a0ff08;
            outline: none;
            box-shadow: 0 0 5px #d7fc09;
        }

        .blue_btn {
            display: inline-block;
            color: #ffffff;
            font-size: 1.2rem;
            background: #0b0c18;
            padding: 12px 25px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: box-shadow 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
            margin-top: 20px;
        }

        .blue_btn:hover {
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            transform: scale(1.05);
            background: #1a1a1a;
        }

        .file-input-wrapper {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        #preview {
            max-width: 100%;
            margin-top: 10px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            display: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        #file-name {
            font-size: 0.9rem;
            color: #a0ff08;
            text-align: center;
            margin-top: 5px;
        }
        
    </style>

<div class="container">
    <h2 class="text-center">Create Offer</h2>

    <form id="offers-form" method="POST" action="{{ route('offers.create') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Offer Title</label>
            <input type="text" name="title" class="input_dark" value="{{ htmlspecialchars(old('title'), ENT_QUOTES, 'UTF-8') }}" />
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" class="input_dark">
                <option value="0" selected>Select a category</option>
                @foreach (DB::table('category_offers')->get() as $category)
                    <option value="{{ htmlspecialchars($category->id, ENT_QUOTES, 'UTF-8') }}">{{ htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="filename">Image</label>
            <div class="file-input-wrapper">
                <img id="preview" src="#" alt="Image preview">
                <button type="button" class="blue_btn" onclick="document.getElementById('file-input').click();">Choose file</button>
                <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                <div id="file-name">No file chosen</div>
            </div>
            <p style="color: red; text-align: center; margin-top: 20px; font-size:0.9rem;">The image must be 1:1. The file name must be in English.</p>
        </div>

        <div class="form-group">
            <label for="content">Offer Content</label>
            <textarea id="editor" name="content" rows="10" placeholder="Enter the offer text">{{ htmlspecialchars(old('content'), ENT_QUOTES, 'UTF-8') }}</textarea>
        </div>

        <div style="text-align: center;">
            <button type="submit" class="blue_btn">Create Offer</button>
        </div>
    </form>
</div>

    
    {{-- // Инициализация cropper --}}
    <script src="{{ asset('js/image-cropper.js') }}"></script>
    {{-- // Инициализация CKEditor --}}
    <link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
    <script src="{{ asset('js/ckeditor.js') }}"></script>
@endsection
