@extends('layouts.admin')
@section('title', 'Borders - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Borders
                                </h1>
                                <p>
                                    Use border utilities to quickly style the border and border-radius of an element. Great for images, buttons, or any other element.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Additive
                                    </h3>
                                    <p>
                                        Add borders to custom elements:
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body border-utils">
                                    <span class="border"></span><span class="border-top"></span><span class="border-end"></span><span class="border-bottom"></span><span class="border-start"></span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;span class=&quot;border&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border-top&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border-end&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border-bottom&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border-start&quot;&gt;&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Subtractive
                                    </h3>
                                    <p>
                                        Or remove borders:
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body border-utils border-utils-0">
                                    <span class="border-0"></span><span class="border-top-0"></span><span class="border-end-0"></span><span class="border-bottom-0"></span><span class="border-start-0"></span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;span class=&quot;border-0&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border-top-0&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border-end-0&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border-bottom-0&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border-start-0&quot;&gt;&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Color
                                    </h3>
                                    <p>
                                        Change the border color using utilities built on our theme colors.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body border-utils border-utils-0">
                                    <span class="border border-primary"></span><span class="border border-secondary"></span><span class="border border-success"></span><span class="border border-danger"></span><span class="border border-warning"></span><span class="border border-info"></span><span class="border border-light"></span><span class="border border-dark"></span><span class="border border-white"></span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;span class=&quot;border border-primary&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-secondary&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-success&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-danger&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-warning&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-info&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-light&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-dark&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-white&quot;&gt;&lt;/span&gt;</code></pre>
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
                                        Use <code>.border-opacity-{value}</code> utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="border border-success p-2 mb-2">
                                        This is default success border
                                    </div>
                                    <div class="border border-success p-2 mb-2 border-opacity-75">
                                        This is 75% opacity success border
                                    </div>
                                    <div class="border border-success p-2 mb-2 border-opacity-50">
                                        This is 50% opacity success border
                                    </div>
                                    <div class="border border-success p-2 mb-2 border-opacity-25">
                                        This is 25% opacity success border
                                    </div>
                                    <div class="border border-success p-2 border-opacity-10">
                                        This is 10% opacity success border
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;border border-success p-2 mb-2&quot;&gt;This is default success border&lt;/div&gt;&lt;div class=&quot;border border-success p-2 mb-2 border-opacity-75&quot;&gt;This is 75% opacity success border&lt;/div&gt;&lt;div class=&quot;border border-success p-2 mb-2 border-opacity-50&quot;&gt;This is 50% opacity success border&lt;/div&gt;&lt;div class=&quot;border border-success p-2 mb-2 border-opacity-25&quot;&gt;This is 25% opacity success border&lt;/div&gt;&lt;div class=&quot;border border-success p-2 border-opacity-10&quot;&gt;This is 10% opacity success border&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Width
                                    </h3>
                                    <p>
                                        Use <code>.border-{value}</code> utilities for aditional sizes.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body border-utils">
                                    <span class="border border-1"></span><span class="border border-2"></span><span class="border border-3"></span><span class="border border-4"></span><span class="border border-5"></span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;span class=&quot;border border-1&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-2&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-3&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-4&quot;&gt;&lt;/span&gt;&lt;span class=&quot;border border-5&quot;&gt;&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Radius
                                    </h3>
                                    <p>
                                        Add classes to an element to easily round its corners.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body rounded-utils">
                                    <span class="bg-primary-soft rounded"></span><span class="bg-primary-soft rounded-top"></span><span class="bg-primary-soft rounded-end"></span><span class="bg-primary-soft rounded-bottom"></span><span class="bg-primary-soft rounded-start"></span><span class="bg-primary-soft rounded-circle"></span><span class="bg-primary-soft rounded-pill"></span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;span class=&quot;bg-primary-soft rounded&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-top&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-end&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-bottom&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-start&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-circle&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-pill&quot;&gt;&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Sizes
                                    </h3>
                                    <p>
                                        Use the <code>.rounded-{value}</code> classes for larger or smaller rounded corners. Sizes range from <code>0</code> to <code>5</code>, and can be configured by modifying the utilities API.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body rounded-utils">
                                    <span class="bg-primary-soft rounded-0"></span><span class="bg-primary-soft rounded-1"></span><span class="bg-primary-soft rounded-2"></span><span class="bg-primary-soft rounded-3"></span><span class="bg-primary-soft rounded-4"></span><span class="bg-primary-soft rounded-5"></span>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;span class=&quot;bg-primary-soft rounded-0&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-1&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-2&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-3&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-4&quot;&gt;&lt;/span&gt;&lt;span class=&quot;bg-primary-soft rounded-5&quot;&gt;&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
