@extends('layouts.admin')
@section('title', 'Placeholders - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Placeholders
                                </h1>
                                <p>
                                    Use loading placeholders for your components or pages to indicate something may still be loading.
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
                                        In the example below, we take a typical card component and recreate it with placeholders applied to create a “loading card”. Size and proportions are the same between the two.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-sm-6 col-xxl-4">
                                            <div class="card">
                                                <img src="{{ asset('admin-assets/images/preview/a.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/placeholders') }}#" class="btn btn-primary">Go somewhere</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xxl-4">
                                            <div class="card" aria-hidden="true">
                                                <img src="{{ asset('admin-assets/images/preview/a.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title placeholder-glow">
                                                        <span class="placeholder col-6"></span>
                                                    </h5>
                                                    <p class="card-text placeholder-glow">
                                                        <span class="placeholder col-7"></span><span class="placeholder col-4"></span><span class="placeholder col-4"></span><span class="placeholder col-6"></span><span class="placeholder col-8"></span>
                                                    </p>
                                                    <a href="{{ url('/admin/components/placeholders') }}#" tabindex="-1" class="btn btn-primary disabled placeholder col-6 d-inline-block"></a>
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
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;card&quot;&gt;  &lt;img src=&quot;...&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;    &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Go somewhere&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;&lt;div class=&quot;card&quot; aria-hidden=&quot;true&quot;&gt;  &lt;img src=&quot;...&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title placeholder-glow&quot;&gt;      &lt;span class=&quot;placeholder col-6&quot;&gt;&lt;/span&gt;    &lt;/h5&gt;    &lt;p class=&quot;card-text placeholder-glow&quot;&gt;      &lt;span class=&quot;placeholder col-7&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;placeholder col-4&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;placeholder col-4&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;placeholder col-6&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;placeholder col-8&quot;&gt;&lt;/span&gt;    &lt;/p&gt;    &lt;a href=&quot;#&quot; tabindex=&quot;-1&quot; class=&quot;btn btn-primary disabled placeholder col-6&quot;&gt;&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Color
                                    </h3>
                                    <p>
                                        By default, the <code>placeholder</code> uses <code>currentColor</code>. This can be overridden with a custom color or utility class.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <span class="placeholder col-12"></span><span class="placeholder col-12 bg-primary"></span><span class="placeholder col-12 bg-secondary"></span><span class="placeholder col-12 bg-success"></span><span class="placeholder col-12 bg-danger"></span><span class="placeholder col-12 bg-warning"></span><span class="placeholder col-12 bg-info"></span><span class="placeholder col-12 bg-light"></span><span class="placeholder col-12 bg-dark"></span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;span class=&quot;placeholder col-12 bg-primary&quot;&gt;&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Sizing
                                    </h3>
                                    <p>
                                        The size of <code>.placeholders</code> are based on the typographic style of the parent element. Customize them with sizing modifiers: <code>.placeholder-lg</code>, <code>.placeholder-sm</code>, or <code>.placeholder-xs</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <span class="placeholder col-12 placeholder-lg"></span><span class="placeholder col-12"></span><span class="placeholder col-12 placeholder-sm"></span><span class="placeholder col-12 placeholder-xs"></span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;span class=&quot;placeholder col-12&quot;&gt;&lt;/span&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Animation
                                    </h3>
                                    <p>
                                        Animate placeholders with <code>.placeholder-glow</code> or <code>.placeholder-wave</code> to better convey the perception of something being actively loaded.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="placeholder-glow">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <p class="placeholder-wave">
                                        <span class="placeholder col-12"></span>
                                    </p>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;p class=&quot;placeholder-glow&quot;&gt;    &lt;span class=&quot;placeholder col-12&quot;&gt;&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;placeholder-wave&quot;&gt;    &lt;span class=&quot;placeholder col-12&quot;&gt;&lt;/span&gt;&lt;/p&gt;  </code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
