@extends('layout.index')
@section('content')
    @include('common.preloader')
    <div class="dashboard-area">
        @include('admin.headerbar')

        <div class="dashboard-background question-area">
            <div class="container admin-question">
                <div class="col-10">
                    <div class="card question-create">
                        <div class="card-body">
                            <h4 class="card-title" style="margin-bottom: 25px;">Detalles de la pregunta</h4>

                            <div class="row">
                                <div class="col-sm-6">
                                    {{-- category --}}
                                    <div class="form-group">
                                        <label>Categoría</label>
                                        <select id="select-category" class="form-control" disabled>
                                            <option value="0"></option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    @if ($category->id == $question->sc_id) selected="selected" @endif>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    {{-- score --}}
                                    <div class="form-group">
                                        <label>Puntaje</label>
                                        <input type="number" class="form-control" placeholder="Puntaje"
                                            id="question-score" step="0.1" min="0" max="10"
                                            value="{{ $question->score / 10 }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- title --}}
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input class="form-control" placeholder="Título" id="question-title"
                                            value="{{ $question->title }}" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- text area --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- content --}}
                                    <div class="form-group">
                                        <label>Contenido</label>
                                        <textarea id="contents" class="form-control" rows="auto" readonly>{{ $question->contents }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- attached table --}}
                            @if (strlen($question->attached_files))
                                <div class="row attached-row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive m-t-10 m-b-25">
                                            <table id="table-attached" class="table table-bordered table-striped" style="margin-bottom: 0px">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nombre</th>
                                                        <th style="display: none">Nombre</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach (explode(':-:', $question->attached_files) as $key => $file)
                                                        <tr id={{ 'file' . $key }}>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td><a href={{ '/attached/admin/question/' . $question->id . '/' . $file }} target="_blank" class="attach-file-link limit-line-1">{{ $file }}</a></td>
                                                            <td style="display: none"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- answer --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    @for ($i = 1; $i <= 5; ++$i)
                                        <div class="answer-group">
                                            <label>{{ 'Responder' . $i }}</label>
                                            <input class="form-control" style="margin-left: 16px; margin-right: 16px;"
                                                value="{{ $question->{'sanswer' . $i} }}" placeholder="Responder"
                                                id={{ 'input-answer' . $i }} readonly>
                                            <input type="checkbox" class="form-control" id={{ 'chk-answer' . $i }}
                                                {{ $i == $question->right_answer ? 'checked' : '' }}
                                                onclick="return false;">
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="row" style="margin-top: 25px;">
                                <a class="btn btn-secondary" style="margin: auto;" href="/admin/question"><i
                                        class="fas fa-arrow-left"></i> Pregunta </a>
                                <a class="btn btn-info" style="margin: auto;"
                                    href={{ '/admin/question/' . $question->id . '/edit' }}><i class="fas fa-edit"></i>
                                    Editar pregunta</a>
                                <a class="btn btn-danger" style="margin: auto;"
                                    onclick="deleteQuestion({{ $question->id }})"><i class="fas fa-trash"></i> Quitar
                                    pregunta</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="/assets/node_modules/sweetalert/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            // $('.textarea_editor').wysihtml5();
            var lineHei = 21;
            var hei = contents.scrollHeight;
            contents.style.height = hei; 
            var rows = Math.floor(hei / lineHei);
            $("#contents").attr("rows", rows);
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
                                window.location.href = "/admin/question";
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
