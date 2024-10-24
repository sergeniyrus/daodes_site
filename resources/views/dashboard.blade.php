<style>
    .task-line {
    color: #00ccff;
  }

  p .task-line{
    text-align: center;
  }
    </style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }} Что тут?
        </h2>
    </x-slot>

    <div >
        <div  style="text-align: center">
            
                    <p class="task-line" style=""> 
                        Вы вошли в систему!
                    </p>
                        <br>
                    <p>
                        Полноценная панель управления находится в разработке. 
                    </p>
                    
        </div>
    </div>
</x-app-layout>
