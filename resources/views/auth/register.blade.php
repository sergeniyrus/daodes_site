<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Login')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- keyword -->
        <div class="mt-4">
            <x-input-label for="keyword" :value="__('Keyword')" />
            <x-text-input id="keyword" class="block mt-1 w-full" type="text" name="keyword" :value="old('keyword')" required autocomplete="keyword" />
            <x-input-error :messages="$errors->get('keyword')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="ms-4">
                {{ __('Регистрация') }}
            </x-primary-button>
        </div>
        <div class="flex justify-center pt-3">
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
            {{ __('Войти') }}
        </a>
        </div>
    </form>

{{-- //генерируем сид-фразу --}}
<?php
    $n = 23;
    $keyword = "secret";

    $lines = file('../public/base.txt');
    shuffle($lines);
    $value = [];
    $value = array_rand($lines, $n);

    foreach ($value as $line_num => $line) {}

    $seed = ($lines[$value[0]] . $lines[$value[1]] . $lines[$value[2]] . $lines[$value[3]] . $lines[$value[4]] . $lines[$value[5]] . $lines[$value[6]] . $lines[$value[7]] . $lines[$value[8]] . $lines[$value[9]] . $lines[$value[10]] . $lines[$value[11]] . $lines[$value[12]] . $lines[$value[13]] . $lines[$value[14]] . $lines[$value[15]] . $lines[$value[16]] . $lines[$value[17]] . $lines[$value[18]] . $lines[$value[19]] . $lines[$value[20]] . $lines[$value[21]] . $lines[$value[22]] . $keyword);

    $hash_seed = password_hash($seed, PASSWORD_DEFAULT);
    $n = 0;
    

    ?>

    <div class="tabseed">
        <table class="tabseed" >
        <tr >
          <td>1  <input size="5" type="text" value="<?php echo $lines[$value[0]]; ?>"></td>
          <td>2  <input size="5" type="text" value="<?php echo $lines[$value[1]]; ?>"></td>
          <td>3  <input size="5" type="text" value="<?php echo $lines[$value[2]]; ?>"></td>
          <td>4  <input size="5" type="text" value="<?php echo $lines[$value[3]]; ?>"></td>
        </tr>
        <tr>
          <td>5  <input size="5" type="text" value="<?php echo $lines[$value[4]]; ?>"></td>
          <td>6  <input size="5" type="text" value="<?php echo $lines[$value[5]]; ?>"></td>
          <td>7  <input size="5" type="text" value="<?php echo $lines[$value[6]]; ?>"></td>
          <td>8  <input size="5" type="text" value="<?php echo $lines[$value[7]]; ?>"></td>
        </tr>
        <tr>
          <td>9  <input size="5" type="text" value="<?php echo $lines[$value[8]]; ?>"></td>
          <td>10 <input size="5" type="text" value="<?php echo $lines[$value[9]]; ?>"></td>
          <td>11 <input size="5" type="text" value="<?php echo $lines[$value[10]]; ?>"></td>
          <td>12 <input size="5" type="text" value="<?php echo $lines[$value[11]]; ?>"></td>
        </tr>
        <tr>
          <td>13 <input size="5" type="text" value="<?php echo $lines[$value[12]]; ?>"></td>
          <td>14 <input size="5" type="text" value="<?php echo $lines[$value[13]]; ?>"></td>
          <td>15 <input size="5" type="text" value="<?php echo $lines[$value[14]]; ?>"></td>
          <td>16 <input size="5" type="text" value="<?php echo $lines[$value[15]]; ?>"></td>
          </tr>
        <tr>
          <td>17 <input size="5" type="text" value="<?php echo $lines[$value[16]]; ?>"></td>
          <td>18 <input size="5" type="text" value="<?php echo $lines[$value[17]]; ?>"></td>
          <td>19 <input size="5" type="text" value="<?php echo $lines[$value[18]]; ?>"></td>
          <td>20 <input size="5" type="text" value="<?php echo $lines[$value[19]]; ?>"></td>
          </tr>
        <tr>
          <td>21 <input size="5" type="text" value="<?php echo $lines[$value[20]]; ?>"></td>
          <td>22 <input size="5" type="text" value="<?php echo $lines[$value[21]]; ?>"></td>
          <td>23 <input size="5" type="text" value="<?php echo $lines[$value[22]]; ?>"></td>
          <td>24 <input size="5" type="text" value="<?php echo 'you keyword'; ?>"></td>
        </tr>
        </table></div><br>
    </x-guest-layout>