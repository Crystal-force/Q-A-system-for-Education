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
                            <h4 class="card-title" style="margin-bottom: 25px;">Crear pregunta</h4>

                            <div class="row">
                                <div class="col-sm-6">
                                    {{-- category --}}
                                    <div class="form-group">
                                        <label>Categoría</label>
                                        <select id="select-category" class="form-control">
                                            <option value="0"></option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    {{-- score --}}
                                    <div class="form-group">
                                        <label>Puntaje</label>
                                        <input type="number" class="form-control" placeholder="Puntaje"
                                            id="question-score" step="0.1" min="0" max="10">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- title --}}
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input class="form-control" placeholder="Título" id="question-title">
                                    </div>
                                </div>
                            </div>

                            {{-- text area --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- content --}}
                                    <div class="form-group">
                                        <label>Contenido</label>
                                        <textarea id="mymce"></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- attach --}}
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
                                        <input class="form-control" type="url" placeholder="Vídeo" id="video-url">
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
                                                placeholder="Responder" id={{'input-answer'. $i}}>
                                            <input type="checkbox" class="form-control" id={{'chk-answer'.$i}}
                                                onchange="toggleAnswers({{$i}})">
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="row" style="margin-top: 25px;">
                                <a class="btn btn-secondary" style="margin: auto;" href="/admin/question"><i
                                        class="fas fa-arrow-left"></i> Pregunta </a>
                                <button type="button" class="btn btn-success" style="margin: auto;"
                                    id="btn-create-question"><i class="fas fa-check"></i> Crear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="/assets/node_modules/icheck/icheck.min.js"></script>
    <script src="/assets/node_modules/icheck/icheck.init.js"></script>
    <script>
        var ra_no = 0;
        $(document).ready(function() {
            $('.textarea_editor').wysihtml5();
        });

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

        $("#btn-create-question").click(function() {
            var category = $("#select-category").val();
            var score = $("#question-score").val();
            var title = $("#question-title").val();
            var contents = tinymce.activeEditor.getContent({
                format: "text"
            });
            var videoUrl = $("#video-url").val();
            var ans_arr = [];

            if (category <= 0) {
                $.toast({
                    heading: 'Creación fallida',
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
                    heading: 'Creación fallida',
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
                    heading: 'Creación fallida',
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
                    heading: 'Creación fallida',
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
                    heading: 'Creación fallida',
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
                        heading: 'Creación fallida',
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
                    heading: 'Creación fallida',
                    text: 'Por favor elija la respuesta correcta.',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });

                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/admin/question',
                method: 'POST',
                data: {
                    category: category,
                    score: score * 10,
                    title: title,
                    contents: contents,
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
                        window.location.href = "/admin/question";
                    } else {
                        if (data.result == "existed") {
                            $.toast({
                                heading: 'Creación fallida',
                                text: 'Existe la misma categoría.',
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
        });
    </script>
@endsection
