{{--<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>--}}

@if(backpack_user()->company_id)
<li class='nav-item'>
    <a class='nav-link' href='{{ backpack_url('category') }}'>
        <i class='nav-icon la la-list'></i> <span>Kategoriler</span>
    </a>
</li>
<li class='nav-item'>
    <a class='nav-link' href='{{ backpack_url('product') }}'>
        <i class='nav-icon la la-cubes'></i> <span>Ürünler</span>
    </a>
</li>
{{--<li class='nav-item'>
    <a class='nav-link' href='{{ backpack_url('test') }}'>
        <i class='nav-icon la la-question'></i> <span>Ayarlar</span>
    </a>
</li>--}}
@elseif(backpack_user()->is_admin)
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('company') }}">
        <i class="nav-icon la la-question"></i> <span>Şirketler</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('user') }}">
        <i class="nav-icon la la-user"></i> <span>Kullanıcılar</span>
    </a>
</li>
@endif
