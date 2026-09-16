@extends('layouts.admin')
@section('title', 'Range - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Range
                                </h1>
                                <p>
                                    Examples and usage guidelines for range slider with <a href="https://refreshless.com/nouislider/" target="_blank">noUislider</a> plugin.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gy-4 gx-gs">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label mb-3">Default Range Slider</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-start="30" id="DefaultRange">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label mb-3">Range Connect (upper)</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-start="20" data-connect="upper" id="RangeConnect">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label mb-3">Range Slider Step (10)</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-step="10" data-start="20" id="RangeStep">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label mb-3">Range Multiple</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-connect="true" data-start="30 70" id="RangeMultiple">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-10">
                                            <ul class="gap gy-2">
                                                <li>
                                                    <div class="form-note">
                                                        <code>data-start="20"</code> attribute to specify value for slider handle, this can take multiple value saparated with an space like this <code>[data-start="20 40"]</code>.
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-note">
                                                        <code>data-step="10"</code> attribute to enable slider step mode.
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-note">
                                                        <code>data-connect="upper"</code> attribute to specify if slider handles are connected with something. you can use <code>{lower, upper, true, false}</code> with this. <code>lower</code> connects with start point, <code>upper</code> connects with end point, <code>true</code> connects handles with each other. Also you need to make combination with handle count with connect points, this can take multiple value saparated with an space like this <code>[data-connect="lower false true upper"]</code>.
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;form-group&quot;&gt;    &lt;label class=&quot;form-label&quot;&gt;Default Range Slider&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;js-range&quot; data-start=&quot;30&quot; id=&quot;DefaultRange&quot;&gt;&lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gy-4 gx-gy">
                                        <div class="col-sm-3 col-6">
                                            <div class="form-group">
                                                <label class="form-label">Default Range Slider</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-orientation="vertical" data-start="30" id="vertical-Default-Range">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <div class="form-group">
                                                <label class="form-label">Range Connect (upper)</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-orientation="vertical" data-start="20" data-connect="upper" id="vertical-Range-Connect">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <div class="form-group">
                                                <label class="form-label">Range Slider Step (10)</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-orientation="vertical" data-step="10" data-start="20" id="vertical-Range-Step">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <div class="form-group">
                                                <label class="form-label">Range Multiple</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-orientation="vertical" data-connect="true" data-start="30 70" id="vertical-Range-Multiple">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>data-orientation="vertical"</code> attribute to enable vertical mode.
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
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;form-group&quot;&gt;    &lt;label class=&quot;form-label&quot;&gt;Default Range Slider&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;js-range&quot; data-orientation=&quot;vertical&quot; data-start=&quot;30&quot; id=&quot;vertical-Default-Range&quot;&gt;&lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gy-4 gx-gs">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label">Tooltip Slider</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-tooltip="true" data-start="40" id="Tooltip-Range">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label">Minimum Distance Slider</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-tooltip="true" data-connect="true" data-start="30 60" data-min-distance="20" id="Min-Distance-Range">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label">Maximum Distance Slider</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-range" data-tooltip="true" data-connect="true" data-start="30 60" data-max-distance="50" id="Max-Distance-Range">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>data-tooltip="true"</code> attribute to enable Tooltip mode.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;form-group&quot;&gt;    &lt;label class=&quot;form-label&quot;&gt;Tooltip Slider&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;js-range&quot; data-tooltip=&quot;true&quot; data-start=&quot;40&quot; id=&quot;Tooltip-Range&quot;&gt;&lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                    <p>
                                        <p>
                                            Create custom <code>&lt;input type="range"&gt;</code> controls with <code>.form-range</code>. The track (the background) and thumb (the value) are both styled to appear the same across browsers.
                                        </p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row gx-gs gy-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customRange1" class="form-label">Default range</label>
                                                    <div class="form-control-wrap">
                                                        <input type="range" class="form-range" id="customRange1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="disabledRange" class="form-label">Disabled range</label>
                                                    <div class="form-control-wrap">
                                                        <input type="range" class="form-range" id="disabledRange" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customRange2" class="form-label">Min and max range (0-5)</label>
                                                    <div class="form-control-wrap">
                                                        <input type="range" class="form-range" min="0" max="5" id="customRange2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customRange3" class="form-label">Steps range (10)</label>
                                                    <div class="form-control-wrap">
                                                        <input type="range" class="form-range" step="10" id="customRange3">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-note">
                                                    Use <code>type="range"</code> attribute to <code>input</code> to enable range mode.
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
                                                <button data-clipboard-target="#code-example-4" class="js-copy text-light" type="button"></button>
                                            </div>
                                        </div>
                                        <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;customRange1&quot; class=&quot;form-label&quot;&gt;Default range&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;range&quot; class=&quot;form-range&quot; id=&quot;customRange1&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
