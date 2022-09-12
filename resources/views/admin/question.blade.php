@extends('layout.index')
@section('content')
    @include('common.preloader')
    <div class="dashboard-area">
        @include('admin.headerbar')

        <div class="dashboard-background question-area">
            <div class="admin-question" style="margin: auto 25px;">
                <div class="col-sm-12">
                    <div class="card question-list">
                        <div class="card-body">

                            <div class="row question-header">
                                <h4 class="card-title">Pregunta</h4>

                                <a type="button" class="btn btn-info" href="/admin/question/create"><i class="fas fa-plus-circle"></i> Crear pregunta</a>
                            </div>

                            <div class="table-responsive">
                                <table id="table-questions" class="table table-bordered table-striped"
                                    style="margin-bottom: 0px">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>CategoríaId</th>
                                            <th>Categoría</th>
                                            <th>Título</th>
                                            <th>Contenido</th>
                                            <th>Puntaje</th>
                                            {{-- <th>Adjunto</th> --}}
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($questions as $question)
                                            <tr id={{ 'question' . $question->id }}>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $question->sCategory->id }}</td>
                                                <td>
                                                    <div class="restricted-v-content">
                                                        {{ $question->sCategory->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="restricted-v-content">
                                                        {{ $question->title }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="restricted-v-content">
                                                        {{ $question->contents }}
                                                    </div>
                                                </td>
                                                <td>{{ $question->score / 10 }}</td>
                                                    {{-- <td>
                                                    @if (strlen($question->attached_files))
                                                        <div class="restricted-v-content">
                                                            @foreach (explode(',', $question->attached_files) as $file)
                                                                <a href={{"/attached/admin/question/" . $question->id . "/" . $file}} target="_blank" class="attach-file-link limit-line-1">{{$file}}</a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <a href={{ '/admin/question/' . $question->id }}><i
                                                                    class="fas fa-info-circle"></i></a>
                                                        </div>

                                                        <div class="col-4">
                                                            <a href={{ '/admin/question/' . $question->id . '/edit' }}><i
                                                                    class="fas fa-edit"></i></a>
                                                        </div>

                                                        <div class="col-4">
                                                            <a onclick="deleteQuestion({{ $question->id }})"><i
                                                                    class="fas fa-trash"></i></a>
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
            $('#table-questions').DataTable();
        });

        function deleteQuestion(id) {
            Swal.fire({
                title: 'Advertencia',
                text: '¿Estás seguro de eliminar la pregunta?',
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
                        url: '/admin/question/' + id,
                        method: 'DELETE',
                        dataType: false,
                        success: function(data) {
                            if (data.status == "ok") {
                                window.location.reload(true);
                            } else if (data.status == "fail") {
                                Swal.fire({
                                    title: 'Información',
                                    text: 'Existe usuario respondido.',
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
