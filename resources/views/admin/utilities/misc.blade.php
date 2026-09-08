@extends('layouts.admin')
@section('title', 'Misc - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Reboot
                                </h1>
                                <p>
                                    Reboot, a collection of element-specific CSS changes in a single file, kickstart Bootstrap to provide an elegant, consistent, and simple baseline to build upon.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Horizontal rules
                                    </h3>
                                    <p>
                                        The <code>hr</code> element has been simplified. Similar to browser defaults, <code>hr</code> are styled via <code>border-top</code>, have a default <code>opacity: .25</code>, and automatically inherit their <code>border-color</code> via <code>color</code>, including when <code>color</code> is set via the parent. They can be modified with text, border, and opacity utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <hr>
                                    <div class="text-success">
                                        <hr>
                                    </div>
                                    <hr class="text-danger border-2 opacity-50">
                                    <hr class="border-primary border-3 opacity-75">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;hr&gt;&lt;div class=&quot;text-success&quot;&gt;    &lt;hr&gt;&lt;/div&gt;&lt;hr class=&quot;text-danger border-2 opacity-50&quot;&gt;&lt;hr class=&quot;border-primary border-3 opacity-75&quot;&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Display property
                                    </h3>
                                    <p>
                                        Quickly and responsively toggle the display value of components and more with our display utilities. Includes support for some of the more common values, as well as some extras for controlling display when printing.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex gap g-3 pb-4">
                                        <li>
                                            <div class="d-inline p-2 bg-primary text-white">
                                                d-inline
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-inline p-2 bg-dark text-white">
                                                d-inline
                                            </div>
                                        </li>
                                    </ul>
                                    <span class="d-block p-2 bg-primary text-white">d-block</span><span class="d-block p-2 bg-dark text-white">d-block</span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;d-inline p-2 bg-primary text-white&quot;&gt;d-inline&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Shadows
                                    </h3>
                                    <p>
                                        You can also quickly add or remove shadows to elements with box-shadow utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="shadow-none p-3 mb-5 bg-light rounded">
                                        No shadow
                                    </div>
                                    <div class="shadow-sm p-3 mb-5 bg-body rounded">
                                        Small shadow
                                    </div>
                                    <div class="shadow p-3 mb-5 bg-body rounded">
                                        Regular shadow
                                    </div>
                                    <div class="shadow-lg p-3 mb-5 bg-body rounded">
                                        Larger shadow
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;shadow-none p-3 mb-5 bg-light rounded&quot;&gt;No shadow&lt;/div&gt;&lt;div class=&quot;shadow-sm p-3 mb-5 bg-body rounded&quot;&gt;Small shadow&lt;/div&gt;&lt;div class=&quot;shadow p-3 mb-5 bg-body rounded&quot;&gt;Regular shadow&lt;/div&gt;&lt;div class=&quot;shadow-lg p-3 mb-5 bg-body rounded&quot;&gt;Larger shadow&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Opacity
                                    </h3>
                                    <p>
                                        Set the opacity of an element using <code>.opacity-{value}</code> utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body d-flex flex-wrap">
                                    <div class="opacity-100 p-3 m-2 bg-primary text-white fw-bold rounded">
                                        100%
                                    </div>
                                    <div class="opacity-75 p-3 m-2 bg-primary text-white fw-bold rounded">
                                        75%
                                    </div>
                                    <div class="opacity-50 p-3 m-2 bg-primary text-white fw-bold rounded">
                                        50%
                                    </div>
                                    <div class="opacity-25 p-3 m-2 bg-primary text-white fw-bold rounded">
                                        25%
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;opacity-100 p-3 m-2 bg-primary text-white fw-bold rounded&quot;&gt;100%&lt;/div&gt;&lt;div class=&quot;opacity-75 p-3 m-2 bg-primary text-white fw-bold rounded&quot;&gt;75%&lt;/div&gt;&lt;div class=&quot;opacity-50 p-3 m-2 bg-primary text-white fw-bold rounded&quot;&gt;50%&lt;/div&gt;&lt;div class=&quot;opacity-25 p-3 m-2 bg-primary text-white fw-bold rounded&quot;&gt;25%&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
