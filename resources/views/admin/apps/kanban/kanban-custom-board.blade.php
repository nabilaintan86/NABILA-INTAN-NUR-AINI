@extends('layouts.admin')
@section('title', 'Kanban custom board - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Kanban Board
                                </h1>
                                <p>
                                    jKanban is a pure agnostic Javascript plugin for Kanban boards. For more info see <a href="http://www.riccardotartaglia.it/jkanban/" target="_blank">the official site</a>.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div id="kanbanCustomBoard" class="js-kanban kanban-custom-board">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('admin/assets/js/kanban/kanban.js') }}"></script>
    @endpush
