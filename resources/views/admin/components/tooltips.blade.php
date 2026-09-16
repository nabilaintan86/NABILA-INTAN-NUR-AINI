@extends('layouts.admin')
@section('title', 'Tooltips - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Tooltips
                                </h1>
                                <p>
                                    Documentation and examples for adding custom Bootstrap tooltips with CSS and JavaScript using CSS3 for animations and <code>data-bs-attributes</code> for local title storage.
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
                                        One way to initialize all tooltips on a page would be to select them by their <code>data-bs-toggle</code> attribute:
                                    </p>
                                    <p>
                                        Hover over the links below to see tooltips:
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="muted">
                                        Placeholder text to demonstrate some <a href="{{ url('/admin/components/tooltips') }}#" data-bs-toggle="tooltip" title="Default tooltip">inline links</a> with tooltips. This is now just filler, no killer. Content placed here just to mimic the presence of <a href="{{ url('/admin/components/tooltips') }}#" data-bs-toggle="tooltip" title="Another tooltip">real text</a>. And all that just to give you an idea of how tooltips would look when used in real-world situations. So hopefully you've now seen how <a href="{{ url('/admin/components/tooltips') }}#" data-bs-toggle="tooltip" title="Another one here too">these tooltips on links</a> can work in practice, once you use them on <a href="{{ url('/admin/components/tooltips') }}#" data-bs-toggle="tooltip" title="The last tip!">your own</a> site or project.
                                    </p>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">Code Example gose Here</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Custom tooltips
                                    </h3>
                                    <p>
                                        We set a custom class with <code>data-bs-custom-class="custom-tooltip"</code> to scope our custom appearance and use it to override a local CSS variable.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" title="This top tooltip is themed via CSS variables."> Custom tooltip </button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;    data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;top&quot;    data-bs-custom-class=&quot;custom-tooltip&quot;    title=&quot;This top tooltip is themed via CSS variables.&quot;&gt;    Custom tooltip&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Directions
                                    </h3>
                                    <p>
                                        Hover over the buttons below to see the four tooltips directions: <code>top, right, bottom, left</code>. Directions are mirrored when using Bootstrap in RTL.
                                    </p>
                                    <p>
                                        Use these directions <code>top, right, bottom, left</code> on <code>data-bs-placement=""</code> for tooltip placement directions.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top"> Tooltip on top </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="Tooltip on right"> Tooltip on right </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tooltip on bottom"> Tooltip on bottom </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" title="Tooltip on left"> Tooltip on left </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-html="true" title="<em>Tooltip</em> <u>with</u> <b>HTML</b>"> Tooltip with HTML </button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;top&quot; title=&quot;Tooltip on top&quot;&gt;    Tooltip on top&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
