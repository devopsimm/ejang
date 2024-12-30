<x-app-layout>

<x-slot name="header">
    {{ $user->name ?? 'Show User' }}
</x-slot>


    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">User Details</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $user->name }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $user->email }}
                        </div>
                        <h6>NDA</h6>
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $user->nda_date }}
                        </div>
                        <div class="form-group">
                            <strong>Month:</strong>
                            {{ $user->nad_month }}
                        </div>
                        <div class="form-group">
                            <strong>Year:</strong>
                            {{ $user->nda_year }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $user->nda_name }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $user->nda_name_two }}
                        </div>
                        <div class="form-group">
                            <strong>Location:</strong>
                            {{ $user->nda_loc }}
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
