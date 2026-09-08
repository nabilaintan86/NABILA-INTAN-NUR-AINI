@extends('layouts.admin')
@section('title', 'Images - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Images
                                </h1>
                                <p>
                                    Documentation and examples for opting images into responsive behavior (so they never become wider than their parent) and add lightweight styles to them—all via classes.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Responsive images
                                    </h3>
                                    <p>
                                        Images in Bootstrap are made responsive with <code>.img-fluid</code>. This applies <code>max-width: 100%;</code> and <code>height: auto;</code> to the image so that it scales with the parent width.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{ asset('admin/images/preview/a.jpg') }}" class="img-fluid" alt="...">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;img src=&quot;...&quot; class=&quot;img-fluid&quot; alt=&quot;...&quot;&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Image thumbnails
                                    </h3>
                                    <p>
                                        You can use <code>.img-thumbnail</code> class to give an image a rounded <code>1px</code> border appearance.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{ asset('admin/images/preview/a.jpg') }}" class="img-thumbnail " alt="...">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;img src=&quot;...&quot; class=&quot;img-thumbnail&quot; alt=&quot;...&quot;&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Aligning images
                                    </h3>
                                    <p>
                                        Align images with the helper <code>float classes</code> or <code>text alignment classes</code>. block-level images can be centered using the .mx-auto margin utility class.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{ asset('admin/images/preview/a.jpg') }}" class="rounded mb-2 float-start" alt="..."><img src="{{ asset('admin/images/preview/a.jpg') }}" class="rounded mb-2 float-end" alt="...">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;img src=&quot;...&quot; class=&quot;rounded float-start&quot; alt=&quot;...&quot;&gt;&lt;img src=&quot;...&quot; class=&quot;rounded float-end&quot; alt=&quot;...&quot;&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{ asset('admin/images/preview/a.jpg') }}" class="rounded mx-auto d-block" alt="...">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;img src=&quot;...&quot; class=&quot;rounded mx-auto d-block&quot; alt=&quot;...&quot;&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center">
                                        <img src="{{ asset('admin/images/preview/a.jpg') }}" class="rounded" alt="...">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;text-center&quot;&gt;  &lt;img src=&quot;...&quot; class=&quot;rounded&quot; alt=&quot;...&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
