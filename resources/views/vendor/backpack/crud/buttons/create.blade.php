@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> {{ config('app.locale') == 'tr' ? $crud->entity_name . ' ' . trans('backpack::crud.add') : trans('backpack::crud.add') . ' ' . $crud->entity_name }}</span></a>
@endif
