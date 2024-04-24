@extends('adminlte::page')

@section('title', 'Kelola Profile')
@section('content_header')
    <h1>Kelola Profile</h1>
@stop

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="row">

                        <div class="col-md-4">
                            <x-adminlte-card title="Foto Profile" theme="dark" icon="fas fa-lg fa-portrait">
                                <img src="{{ asset('storage/profiles/' . $profile->image) }}" alt="foto profile"
                                    class="rounded mx-auto d-block" width="200px">
                            </x-adminlte-card>
                        </div>
                        <div class="col-md-8 col-12">
                            <x-adminlte-card title="Data Profile" theme="dark" icon="fas fa-lg fa-user">

                                <div class="row p-1">
                                    <div class="col-3">
                                        <h6>Nama</h6>
                                    </div>
                                    <div class="col-1">
                                        <h6>:</h6>
                                    </div>
                                    <div class="col-8">
                                        <h6>{{ $profile->name }}</h6>
                                    </div>
                                </div>
                                <div class="row p-1">
                                    <div class="col-3">
                                        <h6>Email</h6>
                                    </div>
                                    <div class="col-1">
                                        <h6>:</h6>
                                    </div>
                                    <div class="col-8">
                                        <h6>{{ $profile->email }}</h6>
                                    </div>
                                </div>
                                <div class="row p-1">
                                    <div class="col-3">
                                        <h6>Jabatan</h6>
                                    </div>
                                    <div class="col-1">
                                        <h6>:</h6>
                                    </div>
                                    <div class="col-8">
                                        <h6>{{ $profile->jabatan }}</h6>
                                    </div>
                                </div>
                                <div class="row p-1">
                                    <div class="col-3">
                                        <h6>Kantor</h6>
                                    </div>
                                    <div class="col-1">
                                        <h6>:</h6>
                                    </div>
                                    <div class="col-8">
                                        <h6>{{ $profile->kantor }}</h6>
                                    </div>
                                </div>

                                <div class="row p-1 mt-4">

                                    <div class="col-2">
                                    </div>
                                    <div class="col-8 text-center">
                                        <a class="btn btn-primary" href="{{ route('profile.edit', $profile->id) }}">Edit
                                            Profile</a>
                                    </div>
                                    <div class="col-2">
                                    </div>
                                </div>
                            </x-adminlte-card>
                        </div>
                    </div>

                </div><!-- /.container-fluid -->
            </main>
        </div>
    </div>
    <!-- /.content -->
@stop
@include('footer')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)

@section('js')

@stop
