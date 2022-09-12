@extends('layout.index')
@section('content')
    @include('common.preloader')
    <div class="dashboard-area">

        @include('common.top-header')

        <div class="dashboard-background">
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-12">
                        <h1 class="subject-title">{{ $category->name }}</h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="table-simulate-questions" class="table table-bordered table-striped"
                                            style="margin-bottom: 0px">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Assunto</th>
                                                    <th>Questão</th>
                                                    <th>Pontuação</th>
                                                    <th><i class="fas fa-smile"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($questions as $question)
                                                    <tr id={{ 'question' . $question->id }}>
                                                        <td>{{ $loop->index + 1 }}</td>
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
                                                        <td>
                                                            @if (count($question->answers) == 0)
                                                                <a
                                                                    href="/simulate/category/{{ $category->id }}/question/{{ $question->id }}"><i class="fas fa-eye"></i></a>
                                                            @else
                                                                <i
                                                                    class="fas {{ $question->answers[0]->status ? 'fa-thumbs-up answer-success' : 'fa-thumbs-down answer-fail' }} "></i>
                                                            @endif
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

                <div class="row"></div>
            </div>
        </div>
        @include('common.footer')
    </div>

    @include('script.datatable')

    <script>
        $(function() {
            $('#table-simulate-questions').DataTable();
        });

        function clickedCategory(id) {
            // window.location.href = "/simulate/category/" + id;
            alert("/simulate/category/" + id);
        }
    </script>
@endsection
