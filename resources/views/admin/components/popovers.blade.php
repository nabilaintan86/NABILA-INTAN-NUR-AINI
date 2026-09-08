@extends('layouts.admin')
@section('title', 'Popovers - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Popovers
                                </h1>
                                <p>
                                    Documentation and examples for adding Bootstrap popovers, like those found in iOS, to any element on your site.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5>
                                        Enable popovers
                                    </h5>
                                    <p class="wide-md">
                                        You must initialize popovers before they can be used. One way to initialize all popovers on a page would be to select them by their <code>data-bs-toggle</code> attribute, like so:
                                    </p>
                                    <button type="button" class="btn btn-lg btn-danger" data-bs-toggle="popover" title="Popover title" data-bs-content="And here's some amazing content. It's very engaging. Right?">Click to toggle popover</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;button type=&quot;button&quot; class=&quot;btn btn-lg btn-danger&quot; data-bs-toggle=&quot;popover&quot; title=&quot;Popover title&quot; data-bs-content=&quot;And here's some amazing content. It's very engaging. Right?&quot;&gt;Click to toggle popover&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Four directions
                                    </h3>
                                    <p>
                                        Four options are available: <code>top, right, bottom, left</code>. Directions are mirrored when using Bootstrap in RTL. Set to <code>data-bs-placement=""</code> to change the direction.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"> Popover on top </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="Right popover"> Popover on right </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Bottom popover"> Popover on bottom </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover"> Popover on left </button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot; data-bs-container=&quot;body&quot; data-bs-toggle=&quot;popover&quot; data-bs-placement=&quot;top&quot; data-bs-content=&quot;Top popover&quot;&gt;    Popover on top&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Custom popovers
                                    </h3>
                                    <p>
                                        We set a custom class with <code>data-bs-custom-class="custom-popover"</code> to scope our custom appearance
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="popover" data-bs-placement="right" data-bs-custom-class="custom-popover" title="Custom popover" data-bs-content="This popover is themed via CSS variables."> Custom popover </button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;    data-bs-toggle=&quot;popover&quot; data-bs-placement=&quot;right&quot;    data-bs-custom-class=&quot;custom-popover&quot;    title=&quot;Custom popover&quot;    data-bs-content=&quot;This popover is themed via CSS variables.&quot;&gt;    Custom popover&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dismiss on next click
                                    </h3>
                                    <p>
                                        Use the focus trigger to dismiss popovers on the user’s next click of a different element than the toggle element.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <a tabindex="0" class="btn btn-lg btn-danger" role="button" data-bs-toggle="popover" data-bs-trigger="focus" title="Dismissible popover" data-bs-content="And here's some amazing content. It's very engaging. Right?">Dismissible popover</a>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;a tabindex=&quot;0&quot; class=&quot;btn btn-lg btn-danger&quot; role=&quot;button&quot; data-bs-toggle=&quot;popover&quot; data-bs-trigger=&quot;focus&quot; title=&quot;Dismissible popover&quot; data-bs-content=&quot;And here's some amazing content. It's very engaging. Right?&quot;&gt;Dismissible popover&lt;/a&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Disabled elements
                                    </h3>
                                    <p>
                                        Elements with the disabled attribute aren’t interactive, meaning users cannot hover or click them to trigger a popover (or tooltip). As a workaround, you’ll want to trigger the popover from a wrapper <code>div</code> or <code>span</code>, ideally made keyboard-focusable using <code>tabindex="0"</code>.
                                    </p>
                                    <p>
                                        For disabled popover triggers, you may also prefer <code>data-bs-trigger="hover focus"</code> so that the popover appears as immediate visual feedback to your users as they may not expect to click on a disabled element.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Disabled popover"><button class="btn btn-primary" type="button" disabled>Disabled button</button></span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;span class=&quot;d-inline-block&quot; tabindex=&quot;0&quot; data-bs-toggle=&quot;popover&quot; data-bs-trigger=&quot;hover focus&quot; data-bs-content=&quot;Disabled popover&quot;&gt;    &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; disabled&gt;Disabled button&lt;/button&gt;&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
