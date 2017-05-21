@extends('layouts.backend')

@section('title', isset($heading) ? $heading : __('repositories.index'))

@push('prestyles')
{{ HTML::style('vendor/datatables-bs/css/dataTables.bootstrap.min.css') }}
@endpush

@push('prescripts')
{{ HTML::script("vendor/datatables/js/jquery.dataTables.min.js") }}
{{ HTML::script("vendor/datatables-bs/js/dataTables.bootstrap.min.js") }}
<script>
    var flash_message = '{!! session("flash_message") !!}';
    var crud = new CRUD("{{ $resource }}", {});
    crud.flash(flash_message);
    columns.splice(1, 0, {
        data: 'name',
        name: 'name',
        targets: "name",
        render: function(data, type, row) {
            return row.actions.show ? '<a href="' + row.actions.show.uri + '">'+ row.name +'</a>' : row.name;
        }
    });
    columns.push({
        data: 'locked',
        name: 'locked',
        render: function (data, type, row) {
            return row.locked == 0 ? '<span class="label label-primary">Actived</span>' : '<span class="label label-danger">Locked</span>';
        }
    });
    crud.setDatatables(columns, searches).index();
    
    $('#search').on('click', function (e) {
        e.preventDefault();
        crud.refresh();
    });
    
    $('#reset').on('click', function (e) {
        e.preventDefault();
        $('select').prop('selectedIndex', 0);
        $('input').val('');
        crud.refresh();
    });
</script>
@endpush

@section('page-content')
<section class="content-header">
    <h1>{{ $heading or __('repositories.index') }} </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('backend.dashboard') }}"><i class="fa fa-dashboard"></i> {{ __('repositories.dashboard') }}</a></li>
        <li class="active">{{ $heading or __('repositories.index') }}</li>
    </ol>
</section>
<div class="content animated fadeInUp">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    @if (Route::has("backend.{$resource}.create"))
                    <div class="pull-left">
                        <a href='{{ route("backend.{$resource}.create") }}' class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ __('repositories.create') }}</a>
                    </div>
                    @endif
                </div>
                <div class="box-body">
                    @stack('index-table-filter')
                    <div class="table-responsive">
                        <table id="table-index" class="table table-bordered table-hover" width="100%">
                            @stack('index-table-thead')
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
