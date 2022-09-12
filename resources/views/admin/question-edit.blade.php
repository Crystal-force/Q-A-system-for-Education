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
                            <h4 class="card-title" style="margin-bottom: 25px;">Editar pregunta</h4>

                            <div class="row">
                                <div class="col-sm-6">
                                    {{-- category --}}
                                    <div class="form-group">
                                        <label>Categoría</label>
                                        <select id="select-category" class="form-control">
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
                                            value="{{ $question->score / 10 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- title --}}
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input class="form-control" placeholder="Título" id="question-title"
                                            value="{{ $question->title }}">
                                    </div>
                                </div>
                            </div>

                            {{-- text area --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- content --}}
                                    <div class="form-group">
                                        <label>Contenido</label>
                                        <textarea id="mymce">{{ $question->contents }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- attached table --}}
                            <div class="row attached-row">
                                <div class="col-sm-12">
                                    @if (strlen($question->attached_files))
                                        <div class="table-responsive m-t-10 m-b-25">
                                            <table id="table-attached" class="table table-bordered table-striped"
                                                style="margin-bottom: 0px">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nombre</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach (explode(':-:', $question->attached_files) as $key => $file)
                                                        <tr id={{ 'file' . $key }}>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td><a href={{ '/attached/admin/question/' . $question->id . '/' . $file }}
                                                                    target="_blank"
                                                                    class="attach-file-link limit-line-1">{{ $file }}</a>
                                                            </td>
                                                            <td><a onclick="deleteAttachedFile({{ $key }})"><i
                                                                        class="fas fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- new attach --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- attach --}}
                                    <div class="form-group">
                                        <label><i class="ti-link"></i>Acessório</label>
                                        <form action="/admin/question/upload-attached" method="post" class="dropzone">
                                            <div class="fallback">
                                                <input name="file" type="file" id="file" />
                                            </div>
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- youtube url --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Vídeo</label>
                                        <input class="form-control" type="url" placeholder="Vídeo" id="video-url" value="{{ $question->video_url }}">
                                    </div>
                                </div>
                            </div>

                            {{-- answer --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    @for ($i = 1; $i <= 5; ++$i)
                                        <div class="answer-group">
                                            <label>{{ 'Responder' . $i }}</label>
                                            <input class="form-control" style="margin-left: 16px; margin-right: 16px;"
                                                placeholder="Responder" id={{ 'input-answer' . $i }}
                                                value="{{ $question->{'sanswer' . $i} }}">
                                            <input type="checkbox" class="form-control" id={{ 'chk-answer' . $i }}
                                                {{ $i == $question->right_answer ? 'checked' : '' }}
                                                onchange="toggleAnswers({{ $i }})">
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="row" style="margin-top: 25px;">
                                <a class="btn btn-secondary" style="margin: auto;" href="/admin/question"><i
                                        class="fas fa-arrow-left"></i> Pregunta </a>
                                <button type="button" class="btn btn-success" style="margin: auto;"
                                    id="btn-update-question"><i class="fas fa-check"></i> Actualizar</button>
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
        var ra_no = {{ $question->right_answer }};

        $(document).ready(function() {
            // $('.textarea_editor').wysihtml5();
        });

        // already uploaded file lists
        function deleteAttachedFile(rn) {
            var fn = $(`#file${rn} > td:nth-child(2)`).text();
            Swal.fire({
                title: 'Advertencia',
                text: `¿Estás seguro de que quieres eliminar '${fn}'?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.value) {
                    $(`#file${rn}`).remove();

                    if ($("#table-attached > tbody > tr").length > 0) { // reorder
                        $("#table-attached > tbody > tr").each(function(i, obj) {
                            $("td:first-child", this).text(i + 1)
                        });
                    } else { // remove table
                        $(".attached-row").remove();
                    }
                }
            });
        }

        function toggleAnswers(chk_id) {
            if ($(`#chk-answer${chk_id}`).prop('checked') == true) {
                ra_no = chk_id;
                for (var i = 1; i <= 5; ++i) {
                    if (i == chk_id) continue;
                    $(`#chk-answer${i}`).prop('checked', false);
                }
            } else {
                ra_no = 0;
            }
        }

        $("#btn-update-question").click(function() {
            // prev data
            var questionId = {{ $question->id }};
            // var prevSCId = {{ $question->sc_id }};
            // var prevScore = {{ $question->score }};
            // var prevTitle = "{{ $question->title }}";
            var prevAttached = [];
            var ans_arr = [];

            if ($("#table-attached > tbody > tr").length > 0) { // reorder
                $("#table-attached > tbody > tr").each(function(i, obj) {
                    prevAttached.push($("td:nth-child(2) a", this).text());
                });
            } else { // remove table
                $(".attached-row").remove();
            }

            var category = $("#select-category").val();
            var score = $("#question-score").val();
            var title = $("#question-title").val();
            var contents = tinymce.activeEditor.getContent({
                format: "text"
            });
            var videoUrl = $("#video-url").val();

            if (category <= 0) {
                $.toast({
                    heading: 'Actualización fallida',
                    text: 'Por favor seleccione categoría.',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });

                $("#select-category").focus();

                return;
            }

            if (score <= 0 || score > 10) {
                $.toast({
                    heading: 'Actualización fallida',
                    text: 'Por favor, introduzca la puntuación.',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });

                $("#question-score").focus();

                return;
            }

            if (!title) {
                $.toast({
                    heading: 'Actualización fallida',
                    text: 'Por favor ingrese el título.',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });

                $("#question-title").focus();

                return;
            }

            if (!contents) {
                $.toast({
                    heading: 'Actualización fallida',
                    text: 'Por favor ingrese el contenido.',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });

                tinymce.activeEditor.focus();

                return;
            }

            if (!videoUrl) {
                $.toast({
                    heading: 'Actualización fallida',
                    text: 'Por favor, insira url de vídeo.',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });

                $("#video-url").focus();

                return;
            }

            // right answer
            for (var i = 1; i <= 5; ++i) {
                var ans = $(`#input-answer${i}`).val();
                if (!ans) {
                    $.toast({
                        heading: 'Actualización fallida',
                        text: 'Por favor ingrese la respuesta.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000,
                        stack: 6
                    });

                    $(`#input-answer${i}`).focus();

                    return;
                }
                ans_arr.push(ans);
            }

            if (ra_no > 5 || ra_no < 1) {
                $.toast({
                    heading: 'Actualización fallida',
                    text: 'Por favor elija la respuesta correcta.',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });

                return;
            }

            score = score * 10;
            prevAttached = prevAttached.join(":-:");
            // check modification
            // if (prevSCId == category && prevScore == score && prevTitle == title && prevAttached ==
            // "{{ $question->attached_files }}" && $("form .dz-preview").length == 0) {
            // window.location.href = "/admin/question";
            // } else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/admin/question/' + questionId,
                method: 'PATCH',
                data: {
                    category: category,
                    score: score,
                    title: title,
                    contents: contents,
                    prevAttached: prevAttached,
                    videoUrl: videoUrl,
                    sanswer1: ans_arr[0],
                    sanswer2: ans_arr[1],
                    sanswer3: ans_arr[2],
                    sanswer4: ans_arr[3],
                    sanswer5: ans_arr[4],
                    right_answer: ra_no
                },
                dataType: false,
                success: function(data) {
                    if (data.status == "ok") {
                        // console.log(data);
                        window.location.href = "/admin/question";
                    } else {
                        if (data.result == "existed") {
                            $.toast({
                                heading: 'Actualizar fallida',
                                text: 'La misma pregunta existe.',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 3000,
                                stack: 6
                            });
                        }
                    }
                }
            });
            // }
        });
    </script>
@endsection
