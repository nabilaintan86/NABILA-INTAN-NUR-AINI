@extends('layouts.admin')
@section('title', 'Progress - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Progress
                                </h1>
                                <p>
                                    Documentation and examples for using Bootstrap custom progress bars featuring support for stacked bars, animated backgrounds, and text labels.
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
                                    <div class="progress mb-3">
                                        <div class="progress-bar">
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar" data-progress="25%">
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar" data-progress="50%">
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar" data-progress="75%">
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" data-progress="100%">
                                        </div>
                                    </div>
                                    <div class="form-note mt-4">
                                        Use <code>data-progress="{number}%"</code> attribute on <code>.progress-bar</code> to enable progress-bar width.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;progress&quot;&gt;    &lt;div class=&quot;progress-bar&quot;&gt;&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Labels
                                    </h3>
                                    <p>
                                        Add labels to your progress bars by placing text within the <code>.progress-bar</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="progress">
                                        <div class="progress-bar" data-progress="25%">
                                            25%
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;progress&quot;&gt;    &lt;div class=&quot;progress-bar&quot; data-progress=&quot;25%&quot;&gt;25%&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Backgrounds
                                    </h3>
                                    <p>
                                        Use background utility classes to change the appearance of individual progress bars.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" data-progress="25%">
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-info" data-progress="50%">
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-warning" data-progress="75%">
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" data-progress="100%">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;progress mb-3&quot;&gt;    &lt;div class=&quot;progress-bar bg-success&quot; data-progress=&quot;25%&quot;&gt;&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Multiple bars
                                    </h3>
                                    <p>
                                        Include multiple progress bars in a progress component if you need.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="progress">
                                        <div class="progress-bar" data-progress="15%">
                                        </div>
                                        <div class="progress-bar bg-success" data-progress="30%">
                                        </div>
                                        <div class="progress-bar bg-info" data-progress="20%">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;progress&quot;&gt;    &lt;div class=&quot;progress-bar&quot; data-progress=&quot;15%&quot;&gt;&lt;/div&gt;    &lt;div class=&quot;progress-bar bg-success&quot; data-progress=&quot;30%&quot;&gt;&lt;/div&gt;    &lt;div class=&quot;progress-bar bg-info&quot; data-progress=&quot;20%&quot;&gt;&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Striped
                                    </h3>
                                    <p>
                                        Add <code>.progress-bar-striped</code> to any <code>.progress-bar</code> to apply a stripe via CSS gradient over the progress bar’s background color.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="progress mb-3">
                                        <div class="progress-bar progress-bar-striped" data-progress="10%">
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar progress-bar-striped bg-success" data-progress="25%">
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar progress-bar-striped bg-info" data-progress="50%">
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar progress-bar-striped bg-warning" data-progress="75%">
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-danger" data-progress="100%">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-5" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;progress mb-3&quot;&gt;    &lt;div class=&quot;progress-bar progress-bar-striped&quot; data-progress=&quot;10%&quot;&gt;&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Animated stripes
                                    </h3>
                                    <p>
                                        The striped gradient can also be animated. Add <code>.progress-bar-animated</code> to <code>.progress-bar</code> to animate the stripes right to left via CSS3 animations.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" data-progress="75%">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-6" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div class=&quot;progress&quot;&gt;    &lt;div class=&quot;progress-bar progress-bar-striped progress-bar-animated&quot; data-progress=&quot;75%&quot;&gt;&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
