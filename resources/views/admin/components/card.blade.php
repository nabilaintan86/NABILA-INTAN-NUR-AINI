@extends('layouts.admin')
@section('title', 'Card - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Cards
                                </h1>
                                <p>
                                    Bootstrap’s cards provide a flexible and extensible content container with multiple variants and options.
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
                                    <div class="row g-gs">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h3 class="card-title">
                                                        Card title
                                                    </h3>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h3 class="card-title">
                                                        Card title
                                                    </h3>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;card&quot;&gt;  &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h3 class=&quot;card-title&quot;&gt;Card title&lt;/h3&gt;    &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;    &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Go somewhere&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Body
                                    </h3>
                                    <p>
                                        The building block of a card is the <code>.card-body</code>. Use it whenever you need a padded section within a card.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-body">
                                            This is some text within a card body.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;card&quot;&gt;    &lt;div class=&quot;card-body&quot;&gt;      This is some text within a card body.    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Titles, text, and links
                                    </h3>
                                    <p>
                                        Card titles are used by adding <code>.card-title</code> to a <code>h*</code> tag. In the same way, links are added and placed next to each other by adding <code>.card-link</code> to an <code>a</code> tag.
                                    </p>
                                    <p>
                                        Subtitles are used by adding a <code>.card-subtitle</code> to a <code>h*</code> tag. If the <code>.card-title</code> and the <code>.card-subtitle</code> items are placed in a <code>.card-body</code> item, the card title and subtitle are aligned nicely.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">
                                                        Card subtitle
                                                    </h6>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="card-link">Card link</a><a href="{{ url('/admin/components/card') }}#" class="card-link">Another link</a>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;card&quot;&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;    &lt;h6 class=&quot;card-subtitle mb-2 text-muted&quot;&gt;Card subtitle&lt;/h6&gt;    &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;    &lt;a href=&quot;#&quot; class=&quot;card-link&quot;&gt;Card link&lt;/a&gt;    &lt;a href=&quot;#&quot; class=&quot;card-link&quot;&gt;Another link&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Images
                                    </h3>
                                    <p>
                                        <code>.card-img-top</code> places an image to the top of the card. With <code>.card-text</code>, text can be added to the card. Text within <code>.card-text</code> can also be styled with the standard HTML tags.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
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
                                            <button data-clipboard-target="#code-example-4" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;card&quot;&gt;&lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;&lt;div class=&quot;card-body&quot;&gt;  &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        List groups
                                    </h3>
                                    <p>
                                        Create lists of content in a card with a flush list group.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs align-items-center">
                                        <div class="col-md-4">
                                            <div class="card">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        An item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A second item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A third item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A third item
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    Featured
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        An item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A second item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A third item
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        An item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A second item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A third item
                                                    </li>
                                                </ul>
                                                <div class="card-footer">
                                                    Card footer
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
                                            <button data-clipboard-target="#code-example-5" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;card&quot;&gt;    &lt;ul class=&quot;list-group list-group-flush&quot;&gt;      &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;      &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;      &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;      &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;    &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Kitchen sink
                                    </h3>
                                    <p>
                                        Mix and match multiple content types to create the card you need, or throw everything in there. Shown below are image styles, blocks, text styles, and a list group—all wrapped in a fixed-width card.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        An item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A second item
                                                    </li>
                                                    <li class="list-group-item">
                                                        A third item
                                                    </li>
                                                </ul>
                                                <div class="card-body">
                                                    <a href="{{ url('/admin/components/card') }}#" class="card-link">Card link</a><a href="{{ url('/admin/components/card') }}#" class="card-link">Another link</a>
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
                                            <button data-clipboard-target="#code-example-6" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div class=&quot;card&quot;&gt;  &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;  &lt;/div&gt;  &lt;ul class=&quot;list-group list-group-flush&quot;&gt;    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;  &lt;/ul&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;a href=&quot;#&quot; class=&quot;card-link&quot;&gt;Card link&lt;/a&gt;    &lt;a href=&quot;#&quot; class=&quot;card-link&quot;&gt;Another link&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Header and footer
                                    </h3>
                                    <p>
                                        Add an optional header and/or footer within a card.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    Featured
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Special title treatment
                                                    </h5>
                                                    <p class="card-text">
                                                        With supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card text-center">
                                                <div class="card-header">
                                                    Featured
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Special title treatment
                                                    </h5>
                                                    <p class="card-text">
                                                        With supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
                                                </div>
                                                <div class="card-footer text-muted">
                                                    2 days ago
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
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div class=&quot;card&quot;&gt;    &lt;div class=&quot;card-header&quot;&gt;      Featured    &lt;/div&gt;    &lt;div class=&quot;card-body&quot;&gt;      &lt;h5 class=&quot;card-title&quot;&gt;Special title treatment&lt;/h5&gt;      &lt;p class=&quot;card-text&quot;&gt;With supporting text below as a natural lead-in to additional content.&lt;/p&gt;      &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Go somewhere&lt;/a&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
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
                                        Cards assume no specific width to start, so they’ll be 100% wide unless otherwise stated. You can change this as needed with custom CSS, grid classes, grid Sass mixins, or utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Special title treatment
                                                    </h5>
                                                    <p class="card-text">
                                                        With supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Special title treatment
                                                    </h5>
                                                    <p class="card-text">
                                                        With supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
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
                                            <button data-clipboard-target="#code-example-8" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-8">&lt;div class=&quot;row&quot;&gt;  &lt;div class=&quot;col-sm-6&quot;&gt;    &lt;div class=&quot;card&quot;&gt;      &lt;div class=&quot;card-body&quot;&gt;        &lt;h5 class=&quot;card-title&quot;&gt;Special title treatment&lt;/h5&gt;        &lt;p class=&quot;card-text&quot;&gt;With supporting text below as a natural lead-in to additional content.&lt;/p&gt;        &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Go somewhere&lt;/a&gt;      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-sm-6&quot;&gt;    &lt;div class=&quot;card&quot;&gt;      &lt;div class=&quot;card-body&quot;&gt;        &lt;h5 class=&quot;card-title&quot;&gt;Special title treatment&lt;/h5&gt;        &lt;p class=&quot;card-text&quot;&gt;With supporting text below as a natural lead-in to additional content.&lt;/p&gt;        &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Go somewhere&lt;/a&gt;      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Using utilities
                                    </h3>
                                    <p>
                                        Use our handful of available sizing utilities to quickly set a card’s width.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card w-75 mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                Card title
                                            </h5>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Button</a>
                                        </div>
                                    </div>
                                    <div class="card w-50">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                Card title
                                            </h5>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Button</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-9" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-9">&lt;div class=&quot;card w-75 mb-4&quot;&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;With supporting text below as a natural lead-in to additional content.&lt;/p&gt;    &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Button&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Text alignment
                                    </h3>
                                    <p>
                                        You can quickly change the text alignment of any card
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Special title treatment
                                                    </h5>
                                                    <p class="card-text">
                                                        With supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Special title treatment
                                                    </h5>
                                                    <p class="card-text">
                                                        With supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card text-end">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Special title treatment
                                                    </h5>
                                                    <p class="card-text">
                                                        With supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                    <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
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
                                            <button data-clipboard-target="#code-example-10" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-10">&lt;div class=&quot;card&quot;&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Special title treatment&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;With supporting text below as a natural lead-in to additional content.&lt;/p&gt;    &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Go somewhere&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Navigation
                                    </h3>
                                    <p>
                                        Add some navigation to a card’s header (or block) with Bootstrap’s nav components
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card text-center mb-4">
                                        <div class="card-header">
                                            <ul class="nav nav-tabs card-header-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" aria-current="true" href="{{ url('/admin/components/card') }}#">Active</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ url('/admin/components/card') }}#">Link</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link disabled">Disabled</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                Special title treatment
                                            </h5>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
                                        </div>
                                    </div>
                                    <div class="card text-center">
                                        <div class="card-header">
                                            <ul class="nav nav-pills card-header-pills">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="{{ url('/admin/components/card') }}#">Active</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ url('/admin/components/card') }}#">Link</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link disabled">Disabled</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                Special title treatment
                                            </h5>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="{{ url('/admin/components/card') }}#" class="btn btn-primary">Go somewhere</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-11" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-11">&lt;div class=&quot;card text-center mb-4&quot;&gt;  &lt;div class=&quot;card-header&quot;&gt;    &lt;ul class=&quot;nav nav-tabs card-header-tabs&quot;&gt;      &lt;li class=&quot;nav-item&quot;&gt;        &lt;a class=&quot;nav-link active&quot; aria-current=&quot;true&quot; href=&quot;#&quot;&gt;Active&lt;/a&gt;      &lt;/li&gt;      &lt;li class=&quot;nav-item&quot;&gt;        &lt;a class=&quot;nav-link&quot; href=&quot;#&quot;&gt;Link&lt;/a&gt;      &lt;/li&gt;      &lt;li class=&quot;nav-item&quot;&gt;        &lt;a class=&quot;nav-link disabled&quot;&gt;Disabled&lt;/a&gt;      &lt;/li&gt;    &lt;/ul&gt;  &lt;/div&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Special title treatment&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;With supporting text below as a natural lead-in to additional content.&lt;/p&gt;    &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Go somewhere&lt;/a&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Image caps
                                    </h3>
                                    <p>
                                        Similar to headers and footers, cards can include top and bottom “image caps”—images at the top or bottom of a card.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-lg-6 col-xl-3">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                    <p class="card-text">
                                                        <small class="text-muted">Last updated 3 mins ago</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xl-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                    <p class="card-text">
                                                        <small class="text-muted">Last updated 3 mins ago</small>
                                                    </p>
                                                </div>
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-bottom" alt="...">
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
                                            <button data-clipboard-target="#code-example-12" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-12">&lt;div class=&quot;card&quot;&gt;  &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.&lt;/p&gt;    &lt;p class=&quot;card-text&quot;&gt;&lt;small class=&quot;text-muted&quot;&gt;Last updated 3 mins ago&lt;/small&gt;&lt;/p&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Image overlays
                                    </h3>
                                    <p>
                                        Turn an image into a card background and overlay your card’s text. Depending on the image, you may or may not need additional styles or utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xxl-4">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img" alt="...">
                                                <div class="card-img-overlay">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                    <p class="card-text">
                                                        Last updated 3 mins ago
                                                    </p>
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
                                            <button data-clipboard-target="#code-example-13" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-13">&lt;div class=&quot;card&quot;&gt;  &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img&quot; alt=&quot;...&quot;&gt;  &lt;div class=&quot;card-img-overlay&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.&lt;/p&gt;    &lt;p class=&quot;card-text&quot;&gt;Last updated 3 mins ago&lt;/p&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
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
                                        Using a combination of grid and utility classes, cards can be made horizontal in a mobile-friendly and responsive way. In the example below, we remove the grid gutters with <code> .g-0 </code> and use <code>.col-md-*</code> classes to make the card horizontal at the md breakpoint. Further adjustments may be needed depending on your card content.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="h-100 rounded-start" alt="...">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                    <p class="card-text">
                                                        <small class="text-muted">Last updated 3 mins ago</small>
                                                    </p>
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
                                            <button data-clipboard-target="#code-example-14" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-14">&lt;div class=&quot;card&quot;&gt;    &lt;div class=&quot;row g-0&quot;&gt;      &lt;div class=&quot;col-md-4&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;h-100 rounded-start&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;col-md-8&quot;&gt;        &lt;div class=&quot;card-body&quot;&gt;          &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;          &lt;p class=&quot;card-text&quot;&gt;This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.&lt;/p&gt;          &lt;p class=&quot;card-text&quot;&gt;&lt;small class=&quot;text-muted&quot;&gt;Last updated 3 mins ago&lt;/small&gt;&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Card styles
                                    </h3>
                                    <p>
                                        Cards include various options for customizing their backgrounds, borders, and color.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card text-bg-primary">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">
                                                        Primary card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card text-bg-secondary">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">
                                                        Secondary card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card text-bg-success">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">
                                                        Success card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card text-bg-danger">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">
                                                        Danger card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card text-bg-warning">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Warning card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card text-bg-info">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">
                                                        Info card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card text-bg-light">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Light card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card text-bg-dark">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">
                                                        Dark card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
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
                                            <button data-clipboard-target="#code-example-15" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-15">&lt;div class=&quot;card text-bg-primary&quot;&gt;  &lt;div class=&quot;card-header&quot;&gt;Header&lt;/div&gt;  &lt;div class=&quot;card-body&quot;&gt;    &lt;h5 class=&quot;card-title text-white&quot;&gt;Primary card title&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Border
                                    </h3>
                                    <p>
                                        Use border utilities to change just the border-color of a card. Note that you can put <code>.text-{color}</code> classes on the parent <code>.card</code> or a subset of the card’s contents as shown below.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-primary">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body text-primary">
                                                    <h5 class="card-title">
                                                        Primary card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-secondary">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body text-secondary">
                                                    <h5 class="card-title">
                                                        Secondary card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-success">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body text-success">
                                                    <h5 class="card-title">
                                                        Success card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-danger">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body text-danger">
                                                    <h5 class="card-title">
                                                        Danger card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-warning">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Warning card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-info">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Info card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-light">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Light card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-dark">
                                                <div class="card-header">
                                                    Header
                                                </div>
                                                <div class="card-body text-dark">
                                                    <h5 class="card-title">
                                                        Dark card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
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
                                            <button data-clipboard-target="#code-example-16" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-16">&lt;div class=&quot;card border-primary&quot;&gt;  &lt;div class=&quot;card-header&quot;&gt;Header&lt;/div&gt;  &lt;div class=&quot;card-body text-primary&quot;&gt;    &lt;h5 class=&quot;card-title&quot;&gt;Primary card title&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Mixins utilities
                                    </h3>
                                    <p>
                                        You can also change the borders on the card header and footer as needed, and even remove their <code>background-color</code> with <code>.bg-transparent</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-xxl-3">
                                            <div class="card border-success">
                                                <div class="card-header bg-transparent border-success">
                                                    Header
                                                </div>
                                                <div class="card-body text-success">
                                                    <h5 class="card-title text-success">
                                                        Success card title
                                                    </h5>
                                                    <p class="card-text">
                                                        Some quick example text to build on the card title and make up the bulk of the card's content.
                                                    </p>
                                                </div>
                                                <div class="card-footer bg-transparent border-success">
                                                    Footer
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
                                            <button data-clipboard-target="#code-example-17" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-17">&lt;div class=&quot;card border-success&quot;&gt;  &lt;div class=&quot;card-header bg-transparent border-success&quot;&gt;Header&lt;/div&gt;  &lt;div class=&quot;card-body text-success&quot;&gt;    &lt;h5 class=&quot;card-title text-success&quot;&gt;Success card title&lt;/h5&gt;    &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;  &lt;/div&gt;  &lt;div class=&quot;card-footer bg-transparent border-success&quot;&gt;Footer&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Card layout
                                    </h3>
                                    <p>
                                        In addition to styling the content within cards, Bootstrap includes a few options for laying out series of cards. For the time being, <code>these layout options are not yet responsive</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-group">
                                        <div class="card">
                                            <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Card title
                                                </h5>
                                                <p class="card-text">
                                                    This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                </p>
                                                <p class="card-text">
                                                    <small class="text-muted">Last updated 3 mins ago</small>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Card title
                                                </h5>
                                                <p class="card-text">
                                                    This card has supporting text below as a natural lead-in to additional content.
                                                </p>
                                                <p class="card-text">
                                                    <small class="text-muted">Last updated 3 mins ago</small>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Card title
                                                </h5>
                                                <p class="card-text">
                                                    This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.
                                                </p>
                                                <p class="card-text">
                                                    <small class="text-muted">Last updated 3 mins ago</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="my-5">
                                        When using card groups with footers, their content will automatically line up.
                                    </p>
                                    <div class="card-group">
                                        <div class="card">
                                            <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Card title
                                                </h5>
                                                <p class="card-text">
                                                    This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <small class="text-muted">Last updated 3 mins ago</small>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Card title
                                                </h5>
                                                <p class="card-text">
                                                    This card has supporting text below as a natural lead-in to additional content.
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <small class="text-muted">Last updated 3 mins ago</small>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Card title
                                                </h5>
                                                <p class="card-text">
                                                    This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <small class="text-muted">Last updated 3 mins ago</small>
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
                                            <button data-clipboard-target="#code-example-18" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-18">&lt;div class=&quot;card-group&quot;&gt;    &lt;div class=&quot;card&quot;&gt;      &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;      &lt;div class=&quot;card-body&quot;&gt;        &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;        &lt;p class=&quot;card-text&quot;&gt;This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.&lt;/p&gt;        &lt;p class=&quot;card-text&quot;&gt;&lt;small class=&quot;text-muted&quot;&gt;Last updated 3 mins ago&lt;/small&gt;&lt;/p&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;card&quot;&gt;      &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;      &lt;div class=&quot;card-body&quot;&gt;        &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;        &lt;p class=&quot;card-text&quot;&gt;This card has supporting text below as a natural lead-in to additional content.&lt;/p&gt;        &lt;p class=&quot;card-text&quot;&gt;&lt;small class=&quot;text-muted&quot;&gt;Last updated 3 mins ago&lt;/small&gt;&lt;/p&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;card&quot;&gt;      &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;      &lt;div class=&quot;card-body&quot;&gt;        &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;        &lt;p class=&quot;card-text&quot;&gt;This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.&lt;/p&gt;        &lt;p class=&quot;card-text&quot;&gt;&lt;small class=&quot;text-muted&quot;&gt;Last updated 3 mins ago&lt;/small&gt;&lt;/p&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Grid cards
                                    </h3>
                                    <p>
                                        Use the Bootstrap grid system and its <code>.row-cols</code> classes to control how many grid columns
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        <div class="col">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-5">
                                        Change it to <code>.row-cols-3</code> and you’ll see the fourth card wrap.
                                    </p>
                                    <div class="row row-cols-1 row-cols-md-3 g-4">
                                        <div class="col">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-5">
                                        When you need equal height, add <code>.h-100</code> to the cards. If you want equal heights by default, you can set <code>$card-height: 100%</code> in Sass.
                                    </p>
                                    <div class="row row-cols-1 row-cols-md-3 g-4">
                                        <div class="col">
                                            <div class="card h-100">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card h-100">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a short card.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card h-100">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card h-100">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-5">
                                        Just like with card groups, card footers will automatically line up.
                                    </p>
                                    <div class="row row-cols-1 row-cols-md-3 g-4">
                                        <div class="col">
                                            <div class="card h-100">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                                                    </p>
                                                </div>
                                                <div class="card-footer">
                                                    <small class="text-muted">Last updated 3 mins ago</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card h-100">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This card has supporting text below as a natural lead-in to additional content.
                                                    </p>
                                                </div>
                                                <div class="card-footer">
                                                    <small class="text-muted">Last updated 3 mins ago</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card h-100">
                                                <img src="{{ asset('admin/images/preview/b.jpg') }}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        Card title
                                                    </h5>
                                                    <p class="card-text">
                                                        This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.
                                                    </p>
                                                </div>
                                                <div class="card-footer">
                                                    <small class="text-muted">Last updated 3 mins ago</small>
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
                                            <button data-clipboard-target="#code-example-19" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-19">&lt;div class=&quot;row row-cols-1 row-cols-md-2 g-4&quot;&gt;    &lt;div class=&quot;col&quot;&gt;      &lt;div class=&quot;card&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;card-body&quot;&gt;          &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;          &lt;p class=&quot;card-text&quot;&gt;This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col&quot;&gt;      &lt;div class=&quot;card&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;card-body&quot;&gt;          &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;          &lt;p class=&quot;card-text&quot;&gt;This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col&quot;&gt;      &lt;div class=&quot;card&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;card-body&quot;&gt;          &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;          &lt;p class=&quot;card-text&quot;&gt;This is a longer card with supporting text below as a natural lead-in to additional content.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col&quot;&gt;      &lt;div class=&quot;card&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/preview/b.jpg&quot; class=&quot;card-img-top&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;card-body&quot;&gt;          &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;          &lt;p class=&quot;card-text&quot;&gt;This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
