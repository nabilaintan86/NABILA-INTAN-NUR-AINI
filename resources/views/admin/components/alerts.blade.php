@extends('layouts.admin')
@section('title', 'Alert - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Alerts
                                </h1>
                                <p>
                                    Provide contextual feedback messages for typical user actions with the handful of available and flexible alert messages.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Examples
                                    </h3>
                                    <p>
                                        Alerts are available for any length of text, as well as an optional close button.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-primary" role="alert">
                                        A simple primary alert—check it out!
                                    </div>
                                    <div class="alert alert-secondary" role="alert">
                                        A simple secondary alert—check it out!
                                    </div>
                                    <div class="alert alert-success" role="alert">
                                        A simple success alert—check it out!
                                    </div>
                                    <div class="alert alert-danger" role="alert">
                                        A simple danger alert—check it out!
                                    </div>
                                    <div class="alert alert-warning" role="alert">
                                        A simple warning alert—check it out!
                                    </div>
                                    <div class="alert alert-info" role="alert">
                                        A simple info alert—check it out!
                                    </div>
                                    <div class="alert alert-light" role="alert">
                                        A simple light alert—check it out!
                                    </div>
                                    <div class="alert alert-dark" role="alert">
                                        A simple dark alert—check it out!
                                    </div>
                                    <div class="form-note">
                                        Use <code>.alert-{name}</code> for various alert options.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;alert alert-primary&quot; role=&quot;alert&quot;&gt;  A simple primary alert&mdash;check it out!&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Live example
                                    </h3>
                                    <p>
                                        Click the button below to show an alert (hidden with inline styles to start), then dismiss (and destroy) it with the built-in close button.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="liveAlertPlaceholder">
                                    </div>
                                    <div id="customAlertPlaceholder">
                                    </div>
                                    <ul class="d-flex flex-wrap gap g-2">
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="alert" data-bs-target="liveAlertPlaceholder" data-bs-variant="success" data-bs-content="Nice, you triggered this alert message!">Show alert from html</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary custom-alert-trigger">Show alert from js</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Link color
                                    </h3>
                                    <p>
                                        Use the <code>.alert-link</code> utility class to quickly provide matching colored links within any alert.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-primary" role="alert">
                                        A simple primary alert with <a href="{{ url('/admin/components/alerts') }}#" class="alert-link">an example link</a>. Give it a click if you like.
                                    </div>
                                    <div class="alert alert-secondary" role="alert">
                                        A simple secondary alert with <a href="{{ url('/admin/components/alerts') }}#" class="alert-link">an example link</a>. Give it a click if you like.
                                    </div>
                                    <div class="alert alert-success" role="alert">
                                        A simple success alert with <a href="{{ url('/admin/components/alerts') }}#" class="alert-link">an example link</a>. Give it a click if you like.
                                    </div>
                                    <div class="alert alert-danger" role="alert">
                                        A simple danger alert with <a href="{{ url('/admin/components/alerts') }}#" class="alert-link">an example link</a>. Give it a click if you like.
                                    </div>
                                    <div class="alert alert-warning" role="alert">
                                        A simple warning alert with <a href="{{ url('/admin/components/alerts') }}#" class="alert-link">an example link</a>. Give it a click if you like.
                                    </div>
                                    <div class="alert alert-info" role="alert">
                                        A simple info alert with <a href="{{ url('/admin/components/alerts') }}#" class="alert-link">an example link</a>. Give it a click if you like.
                                    </div>
                                    <div class="alert alert-light" role="alert">
                                        A simple light alert with <a href="{{ url('/admin/components/alerts') }}#" class="alert-link">an example link</a>. Give it a click if you like.
                                    </div>
                                    <div class="alert alert-dark" role="alert">
                                        A simple dark alert with <a href="{{ url('/admin/components/alerts') }}#" class="alert-link">an example link</a>. Give it a click if you like.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;alert alert-primary&quot; role=&quot;alert&quot;&gt;  A simple primary alert with &lt;a href=&quot;#&quot; class=&quot;alert-link&quot;&gt;an example link&lt;/a&gt;. Give it a click if you like.&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Additional content
                                    </h3>
                                    <p>
                                        Alerts can also contain additional HTML elements like headings, paragraphs and dividers.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-success p-4" role="alert">
                                        <h4 class="alert-heading">
                                            Well done!
                                        </h4>
                                        <p>
                                            Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.
                                        </p>
                                        <hr>
                                        <p class="mb-0">
                                            Whenever you need to, be sure to use margin utilities to keep things nice and tidy.
                                        </p>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;alert alert-success p-4&quot; role=&quot;alert&quot;&gt;  &lt;h4 class=&quot;alert-heading&quot;&gt;Well done!&lt;/h4&gt;  &lt;p&gt;Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.&lt;/p&gt;  &lt;hr&gt;  &lt;p class=&quot;mb-0&quot;&gt;Whenever you need to, be sure to use margin utilities to keep things nice and tidy.&lt;/p&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Icons
                                    </h3>
                                    <p>
                                        Similarly, you can use flexbox utilities to create alerts with icons. Depending on your icons and content, you may want to add more utilities or custom styles.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-primary d-flex" role="alert">
                                        <em class="ni ni-alert-fill icon me-1 mt-1"></em>
                                        <div>
                                            Example alert with an icon
                                        </div>
                                    </div>
                                    <div class="alert alert-success d-flex" role="alert">
                                        <em class="ni ni-alert-fill icon me-1 mt-1"></em>
                                        <div>
                                            Example success alert with an icon
                                        </div>
                                    </div>
                                    <div class="alert alert-warning d-flex" role="alert">
                                        <em class="ni ni-alert-fill icon me-1 mt-1"></em>
                                        <div>
                                            Example warning alert with an icon
                                        </div>
                                    </div>
                                    <div class="alert alert-danger d-flex" role="alert">
                                        <em class="ni ni-alert-fill icon me-1 mt-1"></em>
                                        <div>
                                            Example danger alert with an icon
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;alert alert-primary d-flex align-items-center&quot; role=&quot;alert&quot;&gt;  &lt;em class=&quot;ni ni-alert-fill icon me-1&quot;&gt;&lt;/em&gt;  &lt;div&gt;    An example alert with an icon  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dismissing
                                    </h3>
                                    <p>
                                        Using the alert JavaScript plugin, it’s possible to dismiss any alert inline. Here’s how:
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Holy guacamole!</strong> You should check in on some of those fields below. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <ul class="gap gy-2 small pt-3 wide-md">
                                        <li>
                                            Be sure you’ve loaded the alert plugin, or the compiled Bootstrap JavaScript.
                                        </li>
                                        <li>
                                            Add a close button and the <code>.alert-dismissible class</code>, which adds extra padding to the right of the alert and positions the close button.
                                        </li>
                                        <li>
                                            On the close button, add the <code>data-bs-dismiss="alert"</code> attribute, which triggers the JavaScript functionality. Be sure to use the <code>button</code> element with it for proper behavior across all devices.
                                        </li>
                                        <li>
                                            To animate alerts when dismissing them, be sure to add the <code>.fade</code> and <code>.show</code> classes.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;alert alert-warning alert-dismissible fade show&quot; role=&quot;alert&quot;&gt;    &lt;strong&gt;Holy guacamole!&lt;/strong&gt; You should check in on some of those fields below.    &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;alert&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
