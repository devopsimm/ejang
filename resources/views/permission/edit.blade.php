<x-app-layout>

    <x-slot name="header">
    Update Permission
    </x-slot>


    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <x-alertBox />

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Permission</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('permissions.update', $permission->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('permission.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
