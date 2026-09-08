@extends('layouts.admin')
@section('title', 'Badge - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Badges
                                </h1>
                                <p>
                                    Documentation and examples for badges, our small count and labeling component.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Examples
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h1>
                                        Example heading <span class="badge bg-secondary">New</span>
                                    </h1>
                                    <h2>
                                        Example heading <span class="badge bg-secondary">New</span>
                                    </h2>
                                    <h3>
                                        Example heading <span class="badge bg-secondary">New</span>
                                    </h3>
                                    <h4>
                                        Example heading <span class="badge bg-secondary">New</span>
                                    </h4>
                                    <h5>
                                        Example heading <span class="badge bg-secondary">New</span>
                                    </h5>
                                    <h6>
                                        Example heading <span class="badge bg-secondary">New</span>
                                    </h6>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;span class=&quot;badge bg-secondary&quot;&gt;New&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Buttons
                                    </h3>
                                    <p>
                                        Badges can be used as part of links or buttons to provide a counter.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary"> Notifications <span class="badge text-bg-light ms-1">4</span></button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;    Notifications &lt;span class=&quot;badge text-bg-light ms-1&quot;&gt;4&lt;/span&gt;&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Positioned
                                    </h3>
                                    <p>
                                        Use utilities to modify a <code>.badge</code> and position it in the corner of a link or button.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex align-items-center gap g-5">
                                        <li>
                                            <button type="button" class="btn btn-primary position-relative"> Inbox <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"> 99+ <span class="visually-hidden">unread messages</span></span></button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary position-relative"> Profile <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"><span class="visually-hidden">New alerts</span></span></button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary position-relative&quot;&gt;    Inbox    &lt;span class=&quot;position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger&quot;&gt;        99+    &lt;span class=&quot;visually-hidden&quot;&gt;unread messages&lt;/span&gt;    &lt;/span&gt;&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Background colors
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-2">
                                        <li>
                                            <span class="badge text-bg-primary">Primary</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-secondary">Secondary</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-success">Success</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-danger">Danger</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-warning">Warning</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-info">Info</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-light">Light</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-dark">Dark</span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;span class=&quot;badge text-bg-primary&quot;&gt;Primary&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Background soft colors
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-2">
                                        <li>
                                            <span class="badge text-bg-primary-soft">Primary</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-secondary-soft">Secondary</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-success-soft">Success</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-danger-soft">Danger</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-warning-soft">Warning</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-info-soft">Info</span>
                                        </li>
                                        <li>
                                            <span class="badge text-bg-dark-soft">Dark</span>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;span class=&quot;badge text-bg-primary-soft&quot;&gt;Primary&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Pill badges
                                    </h3>
                                    <p>
                                        Use the <code>.rounded-pill</code> utility class to make badges more rounded with a larger <code>border-radius</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-2">
                                        <li>
                                            <span class="badge rounded-pill text-bg-primary">Primary</span>
                                        </li>
                                        <li>
                                            <span class="badge rounded-pill text-bg-secondary">Secondary</span>
                                        </li>
                                        <li>
                                            <span class="badge rounded-pill text-bg-success">Success</span>
                                        </li>
                                        <li>
                                            <span class="badge rounded-pill text-bg-danger">Danger</span>
                                        </li>
                                        <li>
                                            <span class="badge rounded-pill text-bg-warning">Warning</span>
                                        </li>
                                        <li>
                                            <span class="badge rounded-pill text-bg-info">Info</span>
                                        </li>
                                        <li>
                                            <span class="badge rounded-pill text-bg-light">Light</span>
                                        </li>
                                        <li>
                                            <span class="badge rounded-pill text-bg-dark">Dark</span>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;span class=&quot;badge rounded-pill text-bg-primary&quot;&gt;Primary&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
