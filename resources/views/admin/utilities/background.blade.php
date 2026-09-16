@extends('layouts.admin')
@section('title', 'Background - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Background
                                </h1>
                                <p>
                                    Convey meaning through <code>background-color</code> and add decoration with gradients.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                    <p>
                                        Similar to the contextual text color classes, set the background of an element to any contextual class. Background utilities do not set color, so in some cases you’ll want to use <code>.text-*</code> color utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap">
                                        <li>
                                            <div class="p-3 mb-2 bg-primary text-white">
                                                .bg-primary
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-secondary text-white">
                                                .bg-secondary
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-success text-white">
                                                .bg-success
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-danger text-white">
                                                .bg-danger
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-warning text-dark">
                                                .bg-warning
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-info text-white">
                                                .bg-info
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-light text-dark">
                                                .bg-light
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-dark text-white">
                                                .bg-dark
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-body text-dark">
                                                .bg-body
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-white text-dark">
                                                .bg-white
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-transparent text-dark">
                                                .bg-transparent
                                            </div>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;p-3 mb-2 bg-primary text-white&quot;&gt;.bg-primary&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Background gradient
                                    </h3>
                                    <p>
                                        By adding a <code>.bg-gradient</code> class, a linear gradient is added as background image to the backgrounds.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="gap g-3">
                                        <li>
                                            <div class="p-3 mb-2 bg-primary text-white bg-gradient">
                                                .bg-primary.bg-gradient
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-secondary text-white bg-gradient">
                                                .bg-secondary.bg-gradient
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-success text-white bg-gradient">
                                                .bg-success.bg-gradient
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-danger text-white bg-gradient">
                                                .bg-danger.bg-gradient
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-warning text-dark bg-gradient">
                                                .bg-warning.bg-gradient
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-info text-dark bg-gradient">
                                                .bg-info.bg-gradient
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-light text-dark bg-gradient">
                                                .bg-light.bg-gradient
                                            </div>
                                        </li>
                                        <li>
                                            <div class="p-3 mb-2 bg-dark text-white bg-gradient">
                                                .bg-dark.bg-gradient
                                            </div>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;p-3 mb-2 bg-primary text-white bg-gradient&quot;&gt;.bg-primary.bg-gradient&lt;/div&gt;</code></pre>
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
                                        Use <code>.bg-opacity-{value}</code> utilities
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="bg-success p-2 text-white">
                                        This is default success background
                                    </div>
                                    <div class="bg-success p-2 text-white bg-opacity-75">
                                        This is 75% opacity success background
                                    </div>
                                    <div class="bg-success p-2 text-dark bg-opacity-50">
                                        This is 50% opacity success background
                                    </div>
                                    <div class="bg-success p-2 text-dark bg-opacity-25">
                                        This is 25% opacity success background
                                    </div>
                                    <div class="bg-success p-2 text-dark bg-opacity-10">
                                        This is 10% opacity success background
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;bg-success p-2 text-white bg-opacity-75&quot;&gt;This is 75% opacity success background&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
