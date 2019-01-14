@extends('layouts.app')

@section('title', 'Dashboard page')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    @if (session('success'))
                      <div class="alert alert-success">
                        {{ session('success') }}
                      </div>
                    @endif
                    @if (session('danger'))
                        <div class="alert alert-danger">
                            {{ session('danger') }}
                        </div>
                     @endif
                    <div class="col-lg-12">
                        <div class="text-center m-t-lg">
                            <h1>
                                Welcome in UIDAI 
                            </h1>
                            <small>
                                It is an Accounting application.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
@endsection
