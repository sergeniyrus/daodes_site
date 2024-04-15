<x-app-layout>
    <div class="">
        <div class="">
            <div class="mini-win">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div><br>

            <div class="mini-win">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div><br>

            <div class="mini-win">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
