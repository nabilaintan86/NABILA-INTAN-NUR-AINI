@extends('layouts.admin')
@section('title', 'Sizing - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Sizing
                                </h1>
                                <p>
                                    Easily make an element as wide or as tall with our width and height utilities.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Width
                                    </h3>
                                    <p>
                                        Includes support for <code>25%, 50%, 75%, 100%</code>, and auto by default. Modify those values as you need to generate different utilities here.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="w-25 p-3" style="background-color: #eee;">
                                        Width 25%
                                    </div>
                                    <div class="w-50 p-3" style="background-color: #eee;">
                                        Width 50%
                                    </div>
                                    <div class="w-75 p-3" style="background-color: #eee;">
                                        Width 75%
                                    </div>
                                    <div class="w-100 p-3" style="background-color: #eee;">
                                        Width 100%
                                    </div>
                                    <div class="w-auto p-3" style="background-color: #eee;">
                                        Width auto
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;w-25 p-3&quot; style=&quot;background-color: #eee;&quot;&gt;Width 25%&lt;/div&gt;&lt;div class=&quot;w-50 p-3&quot; style=&quot;background-color: #eee;&quot;&gt;Width 50%&lt;/div&gt;&lt;div class=&quot;w-75 p-3&quot; style=&quot;background-color: #eee;&quot;&gt;Width 75%&lt;/div&gt;&lt;div class=&quot;w-100 p-3&quot; style=&quot;background-color: #eee;&quot;&gt;Width 100%&lt;/div&gt;&lt;div class=&quot;w-auto p-3&quot; style=&quot;background-color: #eee;&quot;&gt;Width auto&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Height
                                    </h3>
                                    <p>
                                        Includes support for <code>25%, 50%, 75%, 100%</code>, and auto by default. Modify those values as you need to generate different utilities here.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="overflow-auto">
                                        <div style="height: 300px; background-color: rgba(255,0,0,0.1);">
                                            <div class="h-25 d-inline-block" style="width: 120px; background-color: rgba(0,0,255,.1)">
                                                Height 25%
                                            </div>
                                            <div class="h-50 d-inline-block" style="width: 120px; background-color: rgba(0,0,255,.1)">
                                                Height 50%
                                            </div>
                                            <div class="h-75 d-inline-block" style="width: 120px; background-color: rgba(0,0,255,.1)">
                                                Height 75%
                                            </div>
                                            <div class="h-100 d-inline-block" style="width: 120px; background-color: rgba(0,0,255,.1)">
                                                Height 100%
                                            </div>
                                            <div class="h-auto d-inline-block" style="width: 120px; background-color: rgba(0,0,255,.1)">
                                                Height auto
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div style=&quot;height: 100px; background-color: rgba(255,0,0,0.1);&quot;&gt;&lt;div class=&quot;h-25 d-inline-block&quot; style=&quot;width: 120px; background-color: rgba(0,0,255,.1)&quot;&gt;Height 25%&lt;/div&gt;&lt;div class=&quot;h-50 d-inline-block&quot; style=&quot;width: 120px; background-color: rgba(0,0,255,.1)&quot;&gt;Height 50%&lt;/div&gt;&lt;div class=&quot;h-75 d-inline-block&quot; style=&quot;width: 120px; background-color: rgba(0,0,255,.1)&quot;&gt;Height 75%&lt;/div&gt;&lt;div class=&quot;h-100 d-inline-block&quot; style=&quot;width: 120px; background-color: rgba(0,0,255,.1)&quot;&gt;Height 100%&lt;/div&gt;&lt;div class=&quot;h-auto d-inline-block&quot; style=&quot;width: 120px; background-color: rgba(0,0,255,.1)&quot;&gt;Height auto&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
