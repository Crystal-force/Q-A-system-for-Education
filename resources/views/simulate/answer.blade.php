@extends('layout.index')
@section('content')
    @include('common.preloader')
    <div class="dashboard-area">

        @include('common.top-header')

        <div class="dashboard-background">

            <div class="container-fluid mt-5">
                <div class="col-sm-10" style="margin: auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input class="form-control" placeholder="Título" id="question-title"
                                            value="{{ $question->title }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Contenido</label>
                                        <textarea id="contents" class="form-control" rows="auto" readonly>{{ $question->contents }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- attached table --}}
                            {{-- @if (strlen($question->attached_files))
                                <div class="row attached-row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive m-t-10 m-b-25">
                                            <table id="table-attached" class="table table-bordered table-striped"
                                                style="margin-bottom: 0px">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nombre</th>
                                                        <th style="display: none">Nombre</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach (explode(',', $question->attached_files) as $key => $file)
                                                        <tr id={{ 'file' . $key }}>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td><a href={{ '/attached/admin/question/' . $question->id . '/' . $file }}
                                                                    target="_blank"
                                                                    class="attach-file-link limit-line-1">{{ $file }}</a>
                                                            </td>
                                                            <td style="display: none"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}

                            <div class="row">
                                <div class="col-sm-12">
                                    @for ($i = 1; $i <= 5; ++$i)
                                        <div class="simulate-sanswer" id="answer{{ $i }}"
                                            onclick="toggleAnswers({{ $i }})">
                                            <div>
                                                {{ $question->{'sanswer' . $i} }}
                                            </div>
                                            <div><i class="far fa-square"></i></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="row" style="margin-top: 16px;">
                                <button class="btn btn-info" style="margin: auto;" id="btn-confirm"
                                    onclick="confirmAnswer()">Confirmar</button>
                                <button class="btn btn-primary" style="margin: auto; display: none;" id="btn-pdf"
                                    onclick="linkPDF()">Solução(PDF)</button>
                                <button class="btn btn-danger" style="margin: auto; display: none;" id="btn-video"
                                    onclick="linkVideo()">Solução(Vídeo)</button>
                                <button class="btn btn-warning" style="margin: auto; display: none;" id="btn-learn-video"
                                    onclick="linkLearnVideo()">Learn(Vídeo)</button>    
                                <button class="btn btn-info" style="margin: auto; display: none;" id="btn-more"
                                    onclick="goOneMore()">Mais uma!</button>
                                <button class="btn btn-success" style="margin: auto; display: none;" id="btn-next"
                                    onclick="goQuestions()">Voltar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('common.footer')
    </div>

    <script src="/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="/assets/node_modules/sweetalert/sweetalert.min.js"></script>
    <script>
        var a_no = 0;

        $(document).ready(function() {
            // $('.textarea_editor').wysihtml5();
            var lineHei = 21;
            var hei = contents.scrollHeight;
            contents.style.height = hei;
            var rows = Math.floor(hei / lineHei);
            $("#contents").attr("rows", rows);
        });

        function toggleAnswers(id) {
            var element = $(`#answer${id} div:last-child i`);
            if ($(element).hasClass("fa-square")) {
                $(`#answer${id}`).addClass("checked");

                $(element).removeClass("fa-square");
                $(element).addClass("fa-check-square");

                for (var i = 1; i <= 5; ++i) {
                    if (i == id) continue;
                    element = $(`#answer${i} div:last-child i`);
                    if ($(element).hasClass("fa-check-square")) {
                        $(`#answer${i}`).removeClass("checked");

                        $(element).removeClass("fa-check-square");
                        $(element).addClass("fa-square");
                    }
                }

                a_no = id;
            } else if ($(element).hasClass("fa-check-square")) {
                $(`#answer${id}`).removeClass("checked");

                $(element).removeClass("fa-check-square");
                $(element).addClass("fa-square");

                a_no = 0;
            }

            var toggle = $(`#answer${id}`).prop('checked');
            $(`#answer${id}`).prop('checked', !toggle);
        }

        function confirmAnswer() {
            if (a_no == 0) {
                Swal.fire({
                    title: 'Advertencia',
                    text: `Please choose the answer.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });

                return;
            } else {
                var ra_no = {{ $question->right_answer }};

                $(`#answer${a_no}`).removeClass("checked");
                $(`#answer${a_no}`).addClass(a_no == ra_no ? "success" : "fail");

                $("#btn-confirm").css("display", "none");
                $("#btn-pdf").css("display", "block");
                $("#btn-learn-video").css("display", "block");
                $("#btn-video").css("display", "block");
                $("#btn-more").css("display", "block");
                $("#btn-next").css("display", "block");
            }
        }

        function linkPDF() {
            var attached = "{{ $question->attached_files }}";
            var files = attached.split(":-:");
            var pdf = "";

            for (var i = 0; i < files.length; ++i) {
                var ext = files[i].split('.').pop();
                if (ext.toUpperCase() == "PDF") {
                    pdf = files[i];
                    break;
                }
            }

            if (pdf.length == 0) {
                Swal.fire({
                    title: 'Advertencia',
                    text: 'Não existe PDF ',
                    icon: 'warning',
                    confirmButtonText: 'Ok',
                })

                return;
            }

            window.open(`/attached/admin/question/${ {{ $question->id }} }/${pdf}`, '_blank');
        }

        function linkVideo() {
            var videoUrl = "{{ $question->video_url }}";
            window.open(videoUrl, '_blank');
        }

        function linkLearnVideo() {
            var videoUrl = "{{ $question->video_url }}";
            window.open(videoUrl, '_blank');
        }

        function goOneMore() {
            var sc_id = {{ $question->sc_id }};
            var next_id = {{ $next_qid }};
            if (next_id > 0) {
                var url = `/simulate/category/${sc_id}/question/${next_id}`;
                goNextQuestion(url);
            } else {
                Swal.fire({
                    title: 'Congratulação',
                    text: 'Você respondeu a todas as perguntas.',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    var url = `/simulate/category/${sc_id}/questions`;
                    goNextQuestion(url);
                });
            }
        }

        function goNextQuestion(url) {
            var q_id = {{ $question->id }};
            var ra_no = {{ $question->right_answer }};
            var status = a_no == ra_no ? 1 : 0;
            var score = status ? {{ $question->score }} : 0;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/simulate/category/question',
                method: 'POST',
                data: {
                    q_id: q_id,
                    a_no: a_no,
                    status: status,
                    score: score
                },
                dataType: false,
                success: function(data) {
                    if (data.status == "ok") {
                        window.location.href = url;
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
        }

        function goQuestions() {
            var q_id = {{ $question->id }};
            var ra_no = {{ $question->right_answer }};
            var status = a_no == ra_no ? 1 : 0;
            var score = status ? {{ $question->score }} : 0;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/simulate/category/question',
                method: 'POST',
                data: {
                    q_id: q_id,
                    a_no: a_no,
                    status: status,
                    score: score
                },
                dataType: false,
                success: function(data) {
                    if (data.status == "ok") {
                        window.location.href = "/simulate";
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
        }
    </script>
@endsection
