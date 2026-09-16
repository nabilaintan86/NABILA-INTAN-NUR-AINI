@extends('layouts.admin')
@section('title', 'Close button - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Close button
                                </h1>
                                <p>
                                    A generic close button for dismissing content like modals and alerts.
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
                                    <button type="button" class="btn-close" aria-label="Close"></button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;button type=&quot;button&quot; class=&quot;btn-close&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Disabled state
                                    </h3>
                                    <p>
                                        Disabled close buttons change their opacity. We’ve also applied <code>pointer-events: none</code> and <code>user-select: none</code> to preventing hover and active states from triggering.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn-close" disabled aria-label="Close"></button>
                                    <div class="form-note mt-4">
                                        Use <code>disabled</code> attibute to enable disabled state.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;button type=&quot;button&quot; class=&quot;btn-close&quot; disabled aria-label=&quot;Close&quot;&gt;&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        White variant
                                    </h3>
                                    <p>
                                        Change the default <code>.btn-close</code> to be white with the <code>.btn-close-white</code> class. This class uses the <code>filter</code> property to invert the <code>background-image</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="bg-dark p-3 rounded-2">
                                        <ul class="d-flex gap g-2">
                                            <li>
                                                <button type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn-close btn-close-white" disabled aria-label="Close"></button>
                                            </li>
                                        </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;ul class=&quot;d-flex gap g-2&quot;&gt;    &lt;li&gt; &lt;button type=&quot;button&quot; class=&quot;btn-close btn-close-white&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;&lt;/li&gt;    &lt;li&gt;&lt;button type=&quot;button&quot; class=&quot;btn-close btn-close-white&quot; disabled aria-label=&quot;Close&quot;&gt;&lt;/button&gt;&lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
