@extends('layouts.admin')
@section('title', 'Spacing - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Spacing
                                </h1>
                                <p>
                                    Bootstrap includes a wide range of shorthand responsive margin, padding, and gap utility classes to modify an element’s appearance.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Horizontal centering
                                    </h3>
                                    <p>
                                        Additionally, Bootstrap also includes an <code>.mx-auto</code> class for horizontally centering fixed-width block level content—that is, content that has <code>display: block</code> and a width set—by setting the horizontal margins to auto.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="mx-auto bg-secondary text-white" style="width: 200px;">
                                        Centered element
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;mx-auto bg-secondary text-white&quot; style=&quot;width: 200px;&quot;&gt;    Centered element&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Gap
                                    </h3>
                                    <p>
                                        When using <code>display: grid</code>, you can make use of gap utilities on the parent grid container.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-grid gap-3">
                                        <div class="p-2 bg-light border">
                                            Grid item 1
                                        </div>
                                        <div class="p-2 bg-light border">
                                            Grid item 2
                                        </div>
                                        <div class="p-2 bg-light border">
                                            Grid item 3
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;d-grid gap-3&quot;&gt;&lt;div class=&quot;p-2 bg-light border&quot;&gt;Grid item 1&lt;/div&gt;&lt;div class=&quot;p-2 bg-light border&quot;&gt;Grid item 2&lt;/div&gt;&lt;div class=&quot;p-2 bg-light border&quot;&gt;Grid item 3&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
