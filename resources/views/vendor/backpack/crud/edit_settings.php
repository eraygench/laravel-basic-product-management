@extends(backpack_view('blank'))

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">{{ $company->name }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="">
                <div class="card no-padding no-border">
                    <table class="table table-striped mb-0">
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
@endsection
