@extends('template')

@section('title_page')
In DAODES
@endsection

@section('main')
<style>
  .button-container {
    text-align: center;
    background: none;
    
  }

  .blue_btn {
    /* margin: 0 5% 5% 5%; */
    display: inline-block;
    color: #000000;
    font-size: xx-large;
    background-color: #d7fc09;
    /* padding: 15px 30px; */
    border: 1px solid gold;
    border-radius: 10px;
    box-shadow: 0 0 20px #000;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    gap: 15px;
  }

.likebtn  {
    background: none;
    
}

  .blue_btn:hover {
    box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
    transform: scale(1.05);
    color: #fff;
    
  }


  .task-line {
    color: #00ccff;
    font-size: xx-large;
    margin-bottom: 10px;
  }
  .task-line2 {
    color: #ffffff;
    font-size: x-large;
  }
  .regwin {
            background-color: #0b0c18;
            border: 1px solid #fff;
            border-radius: 20px;
            /* color: #fff; */
            padding: 20px;
            max-width: 50%;
            min-width: 280px;
            margin: 20px auto 20px auto;
            text-align: center;
            /* font-size: min(max(50%, 1.5vw), 80%); */
        }
</style>
<div class="header">
    {{-- <div class="imgcontainer "> --}}
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
    {{-- </div> --}}
    
        <div class="regwin">
            
                {{ $slot }}
            
        </div>    
    
</div>        
@endsection