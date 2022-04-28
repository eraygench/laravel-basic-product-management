@extends(backpack_view('blank'))

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">{{ $company->name }}</span>
        </h2>
        <div class="btn-group mb-2">
            <button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="la la-qrcode"></i> QR Code Boyutu
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                @foreach ([100, 200, 300, 400, 500] as $item)
                    <li><a class="dropdown-item" href="{{ route('company_qrcode', ['id' => $company->id, 'size' => $item]) }}">{{ $item }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="">
                <div class="card no-padding no-border">
                    <table class="table table-striped mb-0">
                        <tbody>
                            @foreach(['images/qr-logo-1.png', 'images/qr-logo-2.png', 'images/qr-logo-4.png', 'images/qr-logo-3.png', 'images/qr-logo-5.png'] as $image)
                                <tr>
                                    <td>
                                        <img target="_blank" src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size($size)->errorCorrection('H')->merge(asset($image), .4, true)->generate('http://'.$company->slug.'.atiasoftmenu.com')) }}" alt="">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
@endsection
