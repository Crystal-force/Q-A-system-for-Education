@extends('layout.index')
@section('content')

    @include('common.preloader')
    <div class="dashboard-area">
        @include('admin.headerbar')

        <div class="dashboard-background category-area">
            <div class="container admin-category">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="row">
                                <h4 class="card-title" style="margin: auto; margin-left: 10px;">Categoría</h4>
                                <a type="button" class="btn btn-info" style="margin: auto; margin-right: 10px;" href="/admin/category/create"><i class="fas fa-plus-circle"></i> Crear categoría</a>
                            </div>

                            <div class="table-responsive m-t-10">
                                <table id="table-categories" class="table table-bordered table-striped" style="margin-bottom: 0px">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nombre</th>
                                            <th>Color</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr id={{"category" . $category->id}}>
                                                <td>{{$loop->index + 1}}</td>
                                                <td>{{$category->name}}</td>
                                                <td class={{$category->color}}></td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <a href={{"/admin/category/" . $category->id . "/edit"}}><i class="fas fa-edit"></i></a>
                                                        </div>
                                                        
                                                        <div class="col-6">
                                                            <a onclick="deleteCategory({{$category->id}})"><i class="fas fa-trash"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('script.datatable')
    <script src="/assets/node_modules/sweetalert/sweetalert.min.js"></script>

    <script>
        $(function() {
            $('#table-categories').DataTable();
        });

        function deleteCategory(id){
            Swal.fire({
                title: 'Advertencia',
                text: '¿Estás seguro de eliminar la categoría?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '/admin/category/' + id,
                        method: 'DELETE',
                        dataType: false,
                        success: function(data) {
                            if(data.status == "ok"){
                                window.location.reload(true);
                            }else if(data.status == "fail"){
                                Swal.fire({
                                    title: 'Información',
                                    text: 'Existe una respuesta.',
                                    icon: 'warning',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection