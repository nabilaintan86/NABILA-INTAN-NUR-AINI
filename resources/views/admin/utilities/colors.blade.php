@extends('layouts.admin')
@section('title', 'Colors - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Colors
                                </h1>
                                <p>
                                    Convey meaning through color with a handful of color utility classes. Includes support for styling links with hover states, too.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Colors
                                    </h3>
                                    <p>
                                        Use <code>.text-{name}</code> colorize text.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-primary">
                                        .text-primary
                                    </p>
                                    <p class="text-secondary">
                                        .text-secondary
                                    </p>
                                    <p class="text-success">
                                        .text-success
                                    </p>
                                    <p class="text-danger">
                                        .text-danger
                                    </p>
                                    <p class="text-warning bg-dark">
                                        .text-warning
                                    </p>
                                    <p class="text-info bg-dark">
                                        .text-info
                                    </p>
                                    <p class="text-light bg-dark">
                                        .text-light
                                    </p>
                                    <p class="text-dark">
                                        .text-dark
                                    </p>
                                    <p class="text-body">
                                        .text-body
                                    </p>
                                    <p class="text-muted">
                                        .text-muted
                                    </p>
                                    <p class="text-white bg-dark">
                                        .text-white
                                    </p>
                                    <p class="text-black-50">
                                        .text-black-50
                                    </p>
                                    <p class="text-white-50 bg-dark">
                                        .text-white-50
                                    </p>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;p class=&quot;text-primary&quot;&gt;.text-primary&lt;/p&gt;&lt;p class=&quot;text-secondary&quot;&gt;.text-secondary&lt;/p&gt;&lt;p class=&quot;text-success&quot;&gt;.text-success&lt;/p&gt;&lt;p class=&quot;text-danger&quot;&gt;.text-danger&lt;/p&gt;&lt;p class=&quot;text-warning bg-dark&quot;&gt;.text-warning&lt;/p&gt;&lt;p class=&quot;text-info bg-dark&quot;&gt;.text-info&lt;/p&gt;&lt;p class=&quot;text-light bg-dark&quot;&gt;.text-light&lt;/p&gt;&lt;p class=&quot;text-dark&quot;&gt;.text-dark&lt;/p&gt;&lt;p class=&quot;text-body&quot;&gt;.text-body&lt;/p&gt;&lt;p class=&quot;text-muted&quot;&gt;.text-muted&lt;/p&gt;&lt;p class=&quot;text-white bg-dark&quot;&gt;.text-white&lt;/p&gt;&lt;p class=&quot;text-black-50&quot;&gt;.text-black-50&lt;/p&gt;&lt;p class=&quot;text-white-50 bg-dark&quot;&gt;.text-white-50&lt;/p&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Opacity
                                    </h3>
                                    <p>
                                        Use <code>.text-opacity-{value}</code> utilities
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-primary">
                                        This is default primary text
                                    </div>
                                    <div class="text-primary text-opacity-75">
                                        This is 75% opacity primary text
                                    </div>
                                    <div class="text-primary text-opacity-50">
                                        This is 50% opacity primary text
                                    </div>
                                    <div class="text-primary text-opacity-25">
                                        This is 25% opacity primary text
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;text-primary&quot;&gt;This is default primary text&lt;/div&gt;&lt;div class=&quot;text-primary text-opacity-75&quot;&gt;This is 75% opacity primary text&lt;/div&gt;&lt;div class=&quot;text-primary text-opacity-50&quot;&gt;This is 50% opacity primary text&lt;/div&gt;&lt;div class=&quot;text-primary text-opacity-25&quot;&gt;This is 25% opacity primary text&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
