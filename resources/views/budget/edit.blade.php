@extends('layouts.app')

@section('content')

        <div class="row">
           <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> Edit Budget</strong></h3>
                       <div class="ibox-tools col-md-2">
                        <a href="{{ url('/budget') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                    </div>
                  </div>
                    <div class="ibox-content" style="margin-bottom: 50px;">

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/budget/update') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}

                            @include ('budget.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
@endsection
