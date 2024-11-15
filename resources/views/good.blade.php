@extends('template')

@section('title_page', 'Системное сообщенеи')
    
<style>
    .good {
  min-width: 60%;
  max-width: 80%;
  height: auto;
  margin: 50px auto;
  color: rgb(255, 255, 255);
  background-color: rgba(30, 32, 30, 0.753);
  font-size: min(max(50%, 7vw), 100%);
  font-family: Verdana, Geneva, Tahoma, sans-serif;
  border: 1px solid #fff;
  border-radius: 30px;
  text-align: center;
  vertical-align: auto;
}

.blue_btn {
    /* margin: 0 5% 5% 5%; */
    display: inline-block;
    color: #ffffff;
    font-size: large;
    background: #0b0c18;
    padding: 15px 30px;
    border: 1px solid #d7fc09;
    border-radius: 10px;
    box-shadow: 0 0 20px #000;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    gap: 15px;
    margin-bottom: 25px;
  }



  .blue_btn:hover {
    box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
    transform: scale(1.05);
    color: #ffffff;
    background: #0b0c18;
    
  }
</style>
@section('main')
    <div class="good">
        <br>
        <h2>            
        <?php
        if ( $action == 'edit') {echo 'Редактирование';}
        if ( $action == 'create') {echo 'Создание';}

        if ( $post == 'news'){ echo ' новости'; }
        if ( $post == 'offers'){ echo ' предложения'; }

            ?>   
        </h2><br>
        <h2>прошло успешно!</h2>
        <br>
        <a href="/{{ $post }}/{{ $id }}" >
            <h2 class="blue_btn">посмотреть</h2>
        </a>
        <br>
    </div>
@endsection
