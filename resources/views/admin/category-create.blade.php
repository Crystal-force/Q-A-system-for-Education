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
                            <h4 class="card-title" style="margin-bottom: 25px;">Crear categoría</h4>
                            <div class="create-category">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label for="example-email">Nombre</label>
                                            <input type="text" id="category-name" class="form-control" placeholder="Nombre">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Color</label>
                                            <select id="category-color" class="form-control">
                                                <option selected disabled></option>
                                                <option class="btn-primary" value="btn-primary">Primary</option>
                                                <option class="btn-secondary" value="btn-secondary">secondary</option>
                                                <option class="btn-success" value="btn-success">success</option>
                                                <option class="btn-info" value="btn-info">info</option>
                                                <option class="btn-warning" value="btn-warning">warning</option>
                                                <option class="btn-danger" value="btn-danger">danger</option>
                                                <option class="btn-light" value="btn-light">light</option>
                                                <option class="btn-dark" value="btn-dark">dark</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <a class="btn btn-secondary" style="margin: auto;" href="/admin/category"><i class="fas fa-arrow-left"></i> Categoría </a>
                                    <button type="button" class="btn btn-success" style="margin: auto;" id="btn-create-category"><i class="fas fa-check"></i> Crear</button>
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
        $("#btn-create-category").click(function(){
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/admin/category',
                method: 'POST',
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
                                heading: 'Creación fallida',
                                text: 'Existe la misma categoría.',
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
        });
    </script>
@endsection