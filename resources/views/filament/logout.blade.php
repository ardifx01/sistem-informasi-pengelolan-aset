<x-filament::button
    tag="a"
    color="danger"
    icon="heroicon-o-arrow-right-on-rectangle"
    href="{{ route('filament.auth.logout') }}"
    method="post"
    :form="true"
    class="w-full justify-center"
>
    Logout
</x-filament::button>
