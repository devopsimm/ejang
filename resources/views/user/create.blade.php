<x-app-layout>

    <x-slot name="header">
          Create User
    </x-slot>


    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                 <x-alertBox />

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create User</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('user.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>