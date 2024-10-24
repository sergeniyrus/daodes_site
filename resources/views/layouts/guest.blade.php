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
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 20px #000;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    gap: 15px;
  }

.likebtn {
    background: none;
    
}

  .blue_btn:hover {
    box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
    transform: scale(1.05);
    color: #fff;
    
  }


  .task-line {
    color: #00ccff;
  }
  .task-line2 {
    color: #ffffff;
  }
</style>
<div class="modal-content">
    <div class="imgcontainer ">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        <div class="regwin">
            
                {{ $slot }}
            
        </div>    
    </div>
</div>        
@endsection