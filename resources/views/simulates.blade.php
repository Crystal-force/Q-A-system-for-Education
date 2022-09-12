@extends('layout.index')
@section('content')
    @include('common.preloader')
    <div class="dashboard-area">

        @include('common.top-header')

        <div class="dashboard-background">
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-12">
                        <h3 class="subject-title">Categoría</h3>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-4">
                <div class="row" style="margin: 0 32px;">
                    @foreach ($categories as $category)
                        <div class="col-sm-3" style="margin-bottom: 16px;">
                            <button type="button" class="btn btn-block btn-lg {{ $category->color }} limit-line-1" onclick="clickedCategory({{ $category->id }})">{{ $category->name }}</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @include('common.footer')
    </div>
    <script>
        function clickedCategory(id) {
            window.location.href = "/simulate/category/" + id + "/questions";
            // alert("/simulate/category/" + id);
        }
    </script>
@endsection
