@extends('layouts.admin')
@section('title', 'Gutters - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Gutters
                                </h1>
                                <p>
                                    Gutters are the padding between your columns, used to responsively space and align content in the Bootstrap grid system.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Horizontal gutters
                                    </h3>
                                    <p>
                                        <code>.gx-*</code> classes can be used to control the horizontal gutter widths. The <code>.container</code> or <code>.container-fluid</code> parent may need to be adjusted if larger gutters are used too to avoid unwanted overflow, using a matching padding utility. For example, in the following example we’ve increased the padding with <code>.px-4</code>:
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-5">
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Custom column padding
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Custom column padding
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;row gx-5&quot;&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Vertical gutters
                                    </h3>
                                    <p>
                                        <code>.gy-*</code> classes can be used to control the vertical gutter widths. Like the horizontal gutters, the vertical gutters can cause some overflow below the <code>.row</code> at the end of a page. If this occurs, you add a wrapper around <code>.row</code> with the <code>.overflow-hidden</code> class:
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="overflow-hidden">
                                        <div class="row gy-5">
                                            <div class="col-6">
                                                <div class="p-3 border bg-light">
                                                    Custom column padding
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 border bg-light">
                                                    Custom column padding
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 border bg-light">
                                                    Custom column padding
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 border bg-light">
                                                    Custom column padding
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
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;overflow-hidden&quot;&gt;  &lt;div class=&quot;row gy-5&quot;&gt;    &lt;div class=&quot;col-6&quot;&gt;      &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col-6&quot;&gt;      &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col-6&quot;&gt;      &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col-6&quot;&gt;      &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Horizontal & vertical gutters
                                    </h3>
                                    <p>
                                        <code>.g-*</code> classes can be used to control the horizontal gutter widths, for the following example we use a smaller gutter width, so there won’t be a need to add the <code>.overflow-hidden</code> wrapper class.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="p-3 border bg-light">
                                                Custom column padding
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 border bg-light">
                                                Custom column padding
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 border bg-light">
                                                Custom column padding
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 border bg-light">
                                                Custom column padding
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;row g-2&quot;&gt;    &lt;div class=&quot;col-6&quot;&gt;      &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col-6&quot;&gt;      &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col-6&quot;&gt;      &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col-6&quot;&gt;      &lt;div class=&quot;p-3 border bg-light&quot;&gt;Custom column padding&lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Row columns gutters
                                    </h3>
                                    <p>
                                        Gutter classes can also be added to row columns. In the following example, we use responsive row columns and responsive gutter classes.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-3 border bg-light">
                                                Row column
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;row row-cols-2 row-cols-lg-5 g-2 g-lg-3&quot;&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col&quot;&gt;    &lt;div class=&quot;p-3 border bg-light&quot;&gt;Row column&lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        No gutters
                                    </h3>
                                    <p>
                                        The gutters between columns in our predefined grid classes can be removed with <code>.g-0</code>. This removes the negative margins from <code>.row</code> and the horizontal padding from all immediate children columns.
                                    </p>
                                    <p>
                                        Need an edge-to-edge design? Drop the parent <code>.container</code> or <code>.container-fluid</code> and add <code>.mx-0</code> to the <code>.row</code> to prevent overflow.
                                    </p>
                                    <p>
                                        In practice, here’s how it looks. Note you can continue to use this with all other predefined grid classes (including column widths, responsive tiers, reorders, and more).
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-0">
                                        <div class="col-sm-6 col-md-8 p-3 border bg-light">
                                            .col-sm-6 .col-md-8
                                        </div>
                                        <div class="col-6 col-md-4 p-3 border bg-light">
                                            .col-6 .col-md-4
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;row g-0&quot;&gt;    &lt;div class=&quot;col-sm-6 col-md-8 p-3 border bg-light&quot;&gt;.col-sm-6 .col-md-8&lt;/div&gt;    &lt;div class=&quot;col-6 col-md-4 p-3 border bg-light&quot;&gt;.col-6 .col-md-4&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
