@extends('layout.index')
@section('content')

    @include('common.preloader')
    <div class="dashboard-area">
        @include('admin.headerbar')
        
        <div class="dashboard-background category-area">
            <div class="container admin-category">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title" style="margin-bottom: 25px;">Editar categoría</h4>
                            <div class="create-category">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label for="example-email">Categoría</label>
                                            <input type="text" id="category-name" value="{{$category->name}}" class="form-control" placeholder="Nombre">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Color</label>
                                            <select id="category-color" class="form-control" value="{{$category->color}}">
                                                <option class="btn-primary" value="btn-primary"
                                                @if ($category->color == "btn-primary")
                                                    selected="selected"
                                                @endif>Primary</option>

                                                <option class="btn-secondary" value="btn-secondary" @if ($category->color == "btn-secondary")
                                                    selected="selected"
                                                @endif>secondary</option>

                                                <option class="btn-success" value="btn-success" @if ($category->color == "btn-success")
                                                    selected="selected"
                                                @endif>success</option>

                                                <option class="btn-info" value="btn-info" @if ($category->color == "btn-info")
                                                    selected="selected"
                                                @endif>info</option>

                                                <option class="btn-warning" value="btn-warning" @if ($category->color == "btn-warning")
                                                    selected="selected"
                                                @endif>warning</option>

                                                <option class="btn-danger" value="btn-danger" @if ($category->color == "btn-danger")
                                                    selected="selected"
                                                @endif>danger</option>

                                                <option class="btn-light" value="btn-light" @if ($category->color == "btn-light")
                                                    selected="selected"
                                                @endif>light</option>
                                                
                                                <option class="btn-dark" value="btn-dark" @if ($category->color == "btn-dark")
                                                    selected="selected"
                                                @endif>dark</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <a class="btn btn-secondary" style="margin: auto;" href="/admin/category"><i class="fas fa-arrow-left"></i> Categoría </a>
                                    
                                    <button type="button" class="btn btn-success" style="margin: auto;" id="btn-update-category"><i class="fas fa-check"></i> Actualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script>
        $("#btn-update-category").click(function(){
            var categoryId = {{ $category->id }};
            var prevName = "{{ $category->name }}";
            var prevColor = "{{ $category->color }}";

            var name = $("#category-name").val();

            if(!name.length) {
                $.toast({
                    heading: 'Creación fallida',
                    text: 'Ingrese el nombre de la categoría.',
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'error',
                    hideAfter: 3000, 
                    stack: 6
                });

                $("#category-name").focus();

                return;
            }

            var color = $("#category-color :selected").val();

            if(!color.length) {
                $.toast({
                    heading: 'Creación fallida',
                    text: 'Por favor elige el nombre de la categoría.',
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'error',
                    hideAfter: 3000, 
                    stack: 6
                });

                $("#category-color").focus();

                return;
            }

            if(name == prevName && color == prevColor) {
                window.location.href = "/admin/category";
            }else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/admin/category/' + categoryId,
                    method: 'PATCH',
                    data: {
                        name: name,
                        color: color
                    },
                    dataType: false,
                    success: function(data) {
                        if(data.status == "ok"){
                            window.location.href = "/admin/category";
                        }else{
                            if(data.result == "existed"){
                                $.toast({
                                    heading: 'Actualizar fallida',
                                    text: 'La misma categoría existe.',
                                    position: 'top-right',
                                    loaderBg:'#ff6849',
                                    icon: 'error',
                                    hideAfter: 3000, 
                                    stack: 6
                                });
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection