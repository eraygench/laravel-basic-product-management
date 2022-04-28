<header class="{{ config('backpack.base.header_class') }}">
  <!-- Logo -->
  <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto ml-3" type="button" data-toggle="sidebar-show" aria-label="{{ trans('backpack::base.toggle_navigation')}}">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" target="{{ !backpack_user()->is_admin && backpack_user()->company_id != null ? "_blank" : "" }}" href="{{ !backpack_user()->is_admin && backpack_user()->company_id != null ? route('company', ['company_slug' => backpack_user()->company->slug]) : "" }}" title="{{ config('backpack.base.project_name') }}">
    {!! config('backpack.base.project_logo') !!}
  </a>
  <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show" aria-label="{{ trans('backpack::base.toggle_navigation')}}">
    <span class="navbar-toggler-icon"></span>
  </button>

  @include(backpack_view('inc.menu'))
</header>
