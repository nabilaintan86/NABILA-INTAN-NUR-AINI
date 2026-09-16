@extends('layouts.admin')
@section('title', 'Date and time - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Date and Time Picker
                                </h1>
                                <p>
                                    Custom datepicker with <a target="_blank" href="https://mymth.github.io/vanillajs-datepicker">'vanillajs-datepicker</a>. and time picker from nioapp custom script
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="datePicker1" class="form-label">Datepicker</label>
                                                <div class="form-control-wrap">
                                                    <input placeholder="dd/mm/yyyy" type="text" class="form-control js-datepicker" data-title="Text" data-today-btn="true" data-today-btn-mode="1" data-clear-btn="true" autocomplete="off" id="datePicker1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Daterange Picker</label>
                                                <div class="input-group js-datepicker" data-range="init">
                                                    <input placeholder="dd/mm/yyyy" type="text" class="form-control" name="start"><span class="input-group-text">to</span><input placeholder="dd/mm/yyyy" type="text" class="form-control" name="end">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>data-range="init"</code> attribute to <code>.js-datepicker</code> to enable range date picker.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="datePicker2" class="form-label">Datepicker Month View</label>
                                                <div class="form-control-wrap">
                                                    <input placeholder="dd/mm/yyyy" type="text" class="form-control js-datepicker" data-start-view="1" autocomplete="off" id="datePicker2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="datePicker2" class="form-label">Datepicker Year View</label>
                                                <div class="form-control-wrap">
                                                    <input placeholder="dd/mm/yyyy" type="text" class="form-control js-datepicker" data-start-view="2" autocomplete="off" id="datePicker2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>data-start-view="1"</code> attribute to <code>input</code> to enable month view, <code>data-start-view="2"</code> attribute to <code>input</code> to enable Year view.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Datepicker
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;datePicker1&quot; class=&quot;form-label&quot;&gt;Datepicker&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input placeholder=&quot;dd/mm/yyyy&quot; type=&quot;text&quot; class=&quot;form-control js-datepicker&quot; data-title=&quot;Text&quot; data-today-btn=&quot;true&quot; data-clear-btn=&quot;true&quot; autocomplete=&quot;off&quot; id=&quot;datePicker1&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Daterange picker
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;form-group&quot;&gt;    &lt;label class=&quot;form-label&quot;&gt;Daterange Picker&lt;/label&gt;    &lt;div class=&quot;input-group js-datepicker&quot; data-range=&quot;init&quot; &gt;        &lt;input  placeholder=&quot;dd/mm/yyyy&quot; type=&quot;text&quot; class=&quot;form-control&quot; name=&quot;start&quot;&gt;        &lt;span class=&quot;input-group-text&quot;&gt;to&lt;/span&gt;        &lt;input  placeholder=&quot;dd/mm/yyyy&quot; type=&quot;text&quot; class=&quot;form-control&quot; name=&quot;end&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="timePicker1" class="form-label">Time Picker</label>
                                                <div class="form-control-wrap">
                                                    <input placeholder="hh:mm" type="text" class="form-control js-timepicker" autocomplete="off" id="timePicker1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="timePicker2" class="form-label">Time Picker (24hour)</label>
                                                <div class="form-control-wrap">
                                                    <input placeholder="HH:MM" type="text" class="form-control js-timepicker" data-format="24" autocomplete="off" id="timePicker2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>data-format="24"</code> attribute to <code>input</code> to enable 24hour time formate.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;timePicker1&quot; class=&quot;form-label&quot;&gt;Time Picker&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input placeholder=&quot;hh:mm&quot; type=&quot;text&quot; class=&quot;form-control js-timepicker&quot; autocomplete=&quot;off&quot; id=&quot;timePicker1&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
