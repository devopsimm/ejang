<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

    </x-slot>

    <div class="py-12">
        <a class="btn btn-primary" href="{{ route('purgeLocalCache') }}">Purge Local Cache</a>
    </div>
</x-app-layout>
