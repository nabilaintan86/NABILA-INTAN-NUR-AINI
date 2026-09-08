@extends('layouts.admin')
@section('title', 'Spinners - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Spinners
                                </h1>
                                <p>
                                    Indicate the loading state of a component or page with Bootstrap spinners, built entirely with HTML, CSS, and no JavaScript.
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
                                        The border spinner uses <code>currentColor</code> for its <code>border-color</code>, meaning you can customize the color with text color utilities. You can use any of our text color utilities on the standard spinner.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <div class="spinner-border text-primary">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-border text-secondary">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-border text-success">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-border text-danger">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-border text-warning">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-border text-info">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-border text-light">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-border text-dark">
                                                <span class="visually-hidden">Loading...</span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;spinner-border text-primary&quot;&gt;  &lt;span class=&quot;visually-hidden&quot;&gt;Loading...&lt;/span&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Growing spinner
                                    </h3>
                                    <p>
                                        If you don’t fancy a border spinner, switch to the grow spinner. While it doesn’t technically spin, it does repeatedly grow!
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <div class="spinner-grow text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow text-secondary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow text-success" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow text-danger" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow text-warning" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow text-info" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow text-light" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow text-dark" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="form-note mt-4">
                                        Use <code>.spinner-grow</code> class for grow spinner.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;spinner-grow text-primary&quot; role=&quot;status&quot;&gt;  &lt;span class=&quot;visually-hidden&quot;&gt;Loading...&lt;/span&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Size
                                    </h3>
                                    <p>
                                        Add <code>.spinner-border-sm</code> and <code>.spinner-grow-sm</code> to make a smaller spinner that can quickly be used within other components.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap align-items-center gap g-3">
                                        <li>
                                            <div class="spinner-border spinner-border-sm">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow spinner-grow-sm">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                    </ul>
                                    <p class="mt-5">
                                        Or, use custom CSS or inline styles to change the dimensions as needed.
                                    </p>
                                    <ul class="d-flex flex-wrap align-items-center gap g-3">
                                        <li>
                                            <div class="spinner-border" style="width: 3rem; height: 3rem;">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="spinner-grow" style="width: 3rem; height: 3rem;">
                                                <span class="visually-hidden">Loading...</span>
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
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;spinner-border spinner-border-sm&quot;&gt;    &lt;span class=&quot;visually-hidden&quot;&gt;Loading...&lt;/span&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Buttons
                                    </h3>
                                    <p>
                                        Use spinners within buttons to indicate an action is currently processing or taking place. You may also swap the text out of the spinner element and utilize button text as needed.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex align-items-center flex-wrap gap g-3">
                                        <li>
                                            <button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span></button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Loading... </button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" disabled><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span></button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" disabled><span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span> Loading... </button>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; disabled&gt;    &lt;span class=&quot;spinner-border spinner-border-sm&quot; role=&quot;status&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;    &lt;span class=&quot;visually-hidden&quot;&gt;Loading...&lt;/span&gt;&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
