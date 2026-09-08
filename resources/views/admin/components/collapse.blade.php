@extends('layouts.admin')
@section('title', 'Collapse - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Collapse
                                </h1>
                                <p>
                                    Toggle the visibility of content across your project with a few classes and our JavaScript plugins.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3 pb-3">
                                        <li>
                                            <a class="btn btn-primary" data-bs-toggle="collapse" href="{{ url('/admin/components/collapse') }}#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"> Link with href </a>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"> Button with data-bs-target </button>
                                        </li>
                                    </ul>
                                    <div class="collapse show" id="collapseExample">
                                        <div class="card card-body">
                                            Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
                                        </div>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;ul class=&quot;d-flex flex-wrap gap g-3 pb-3&quot;&gt;  &lt;li&gt;    &lt;a class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;collapse&quot; href=&quot;#collapseExample&quot; role=&quot;button&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseExample&quot;&gt;      Link with href    &lt;/a&gt;  &lt;/li&gt;  &lt;li&gt;    &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseExample&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseExample&quot;&gt;      Button with data-bs-target    &lt;/button&gt;  &lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Horizontal
                                    </h3>
                                    <p>
                                        The collapse plugin also supports horizontal collapsing. Add the <code>.collapse-horizontal</code> modifier class to transition the width instead of height and set a width on the immediate child element.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="pb-3">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample"> Toggle width collapse </button>
                                    </div>
                                    <div style="min-height: 150px;">
                                        <div class="collapse collapse-horizontal" id="collapseWidthExample">
                                            <div class="card card-body" style="width: 300px;">
                                                This is some placeholder content for a horizontal collapse. It's hidden by default and shown when triggered.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;pb-3&quot;&gt;  &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseWidthExample&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseWidthExample&quot;&gt;    Toggle width collapse  &lt;/button&gt;&lt;/div&gt;&lt;div style=&quot;min-height: 150px;&quot;&gt;  &lt;div class=&quot;collapse collapse-horizontal&quot; id=&quot;collapseWidthExample&quot;&gt;    &lt;div class=&quot;card card-body&quot; style=&quot;width: 300px;&quot;&gt;      This is some placeholder content for a horizontal collapse. It's hidden by default and shown when triggered.    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Multiple targets
                                    </h3>
                                    <p>
                                        A <code>button</code> or <code>a</code> can show and hide multiple elements by referencing them with a selector in its href or <code>data-bs-target</code> attribute. Multiple <code>button</code> or <code>a</code> can show and hide an element if they each reference it with their href or <code>data-bs-target</code> attribute
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3 pb-3">
                                        <li>
                                            <a class="btn btn-primary" data-bs-toggle="collapse" href="{{ url('/admin/components/collapse') }}#multiCollapseExample1" role="button" aria-expanded="false">Toggle first element</a>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="false">Toggle second element</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false">Toggle both elements</button>
                                        </li>
                                    </ul>
                                    <div class="row">
                                        <div class="col">
                                            <div class="collapse multi-collapse show" id="multiCollapseExample1">
                                                <div class="card card-body">
                                                    Some placeholder content for the first collapse component of this multi-collapse example. This panel is hidden by default but revealed when the user activates the relevant trigger.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="collapse multi-collapse" id="multiCollapseExample2">
                                                <div class="card card-body">
                                                    Some placeholder content for the second collapse component of this multi-collapse example. This panel is hidden by default but revealed when the user activates the relevant trigger.
                                                </div>
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
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;ul class=&quot;d-flex flex-wrap gap g-3 pb-3&quot;&gt;  &lt;li&gt;&lt;a class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;collapse&quot; href=&quot;#multiCollapseExample1&quot; role=&quot;button&quot; aria-expanded=&quot;false&quot;&gt;Toggle first element&lt;/a&gt;&lt;/li&gt;  &lt;li&gt;&lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#multiCollapseExample2&quot; aria-expanded=&quot;false&quot;&gt;Toggle second element&lt;/button&gt;&lt;/li&gt;  &lt;li&gt;&lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;.multi-collapse&quot; aria-expanded=&quot;false&quot;&gt;Toggle both elements&lt;/button&gt;&lt;/li&gt;&lt;/ul&gt;&lt;div class=&quot;row&quot;&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;collapse multi-collapse show&quot; id=&quot;multiCollapseExample1&quot;&gt;      &lt;div class=&quot;card card-body&quot;&gt;        Some placeholder content for the first collapse component of this multi-collapse example. This panel is hidden by default but revealed when the user activates the relevant trigger.      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;collapse multi-collapse&quot; id=&quot;multiCollapseExample2&quot;&gt;      &lt;div class=&quot;card card-body&quot;&gt;        Some placeholder content for the second collapse component of this multi-collapse example. This panel is hidden by default but revealed when the user activates the relevant trigger.      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
