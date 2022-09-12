@extends('layout.index')
@section('content')

    @include('common.preloader')
    <div class="dashboard-area">
        @include('admin.headerbar')

        <div class="dashboard-background category-area">
            <div class="container">
                <div class="row col-sm-12">
                    <div class="col-sm-6">
                      <a href="/admin/category" class="soluction-category p-2">
                        <i class="fas fa-list solution-icon"></i>
                        <p class="solution-font">Categoría</p>
                      <a>
                    </div>
                    <div class="col-sm-6">
                      <a href="/admin/question" class="ask-category p-2">
                        <i class="fas fa-question question-icon"></i>
                        <p class="solution-font">Pregunta</p>
                      <a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
      function SelectOption(elem) {
        var id = $(elem).attr('data-id');
        if(id == '1') {
          window.location.href = "/ask-subject?id="+id;
        }
        else if(id == "2") {
          window.location.href = "/solution-subject?id="+id;
        }
        else if(id == "3") {
          window.location.href = "/simulate?id="+id;
        }
      }
    </script>
@endsection