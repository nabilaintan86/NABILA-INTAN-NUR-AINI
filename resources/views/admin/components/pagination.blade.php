@extends('layouts.admin')
@section('title', 'Pagination - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Pagination
                                </h1>
                                <p>
                                    Documentation and examples for showing pagination to indicate a series of related content exists across multiple pages.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Custom Pagination
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <nav>
                                        <ul class="pagination pagination-s1">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#"><em class="ni ni-chevron-left icon"></em></a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">4</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#"><em class="ni ni-chevron-right icon"></em></a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="form-note mt-4">
                                        Use <code>.pagination-s1</code> class to <code>.pagination</code> for custom pagination style.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;nav&gt;  &lt;ul class=&quot;pagination pagination-s1 flex-wrap&quot;&gt;    &lt;li class=&quot;page-item disabled&quot;&gt;      &lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;        &lt;em class=&quot;ni ni-chevron-left icon&quot;&gt;&lt;/em&gt;      &lt;/a&gt;    &lt;/li&gt;    &lt;li class=&quot;page-item active&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;1&lt;/a&gt;&lt;/li&gt;    &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;2&lt;/a&gt;&lt;/li&gt;    &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;3&lt;/a&gt;&lt;/li&gt;    &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;4&lt;/a&gt;&lt;/li&gt;    &lt;li class=&quot;page-item&quot;&gt;      &lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;        &lt;em class=&quot;ni ni-chevron-right icon&quot;&gt;&lt;/em&gt;      &lt;/a&gt;    &lt;/li&gt;  &lt;/ul&gt;&lt;/nav&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Default
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">Previous</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;nav aria-label=&quot;Page navigation example&quot;&gt;    &lt;ul class=&quot;pagination&quot;&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;Previous&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;1&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;2&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;3&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;Next&lt;/a&gt;&lt;/li&gt;    &lt;/ul&gt;&lt;/nav&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Working with icons
                                    </h3>
                                    <p>
                                        Looking to use an icon or symbol in place of text for some pagination links? Be sure to provide proper screen reader support with <code>aria</code> attributes.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                                            </li>
                                        </ul>
                                    </nav>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;nav aria-label=&quot;Page navigation example&quot;&gt;    &lt;ul class=&quot;pagination&quot;&gt;      &lt;li class=&quot;page-item&quot;&gt;        &lt;a class=&quot;page-link&quot; href=&quot;#&quot; aria-label=&quot;Previous&quot;&gt;          &lt;span aria-hidden=&quot;true&quot;&gt;&amp;laquo;&lt;/span&gt;        &lt;/a&gt;      &lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;1&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;2&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;3&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;        &lt;a class=&quot;page-link&quot; href=&quot;#&quot; aria-label=&quot;Next&quot;&gt;          &lt;span aria-hidden=&quot;true&quot;&gt;&amp;raquo;&lt;/span&gt;        &lt;/a&gt;      &lt;/li&gt;    &lt;/ul&gt;&lt;/nav&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Disabled and active states
                                    </h3>
                                    <p>
                                        Pagination links are customizable for different circumstances. Use <code>.disabled</code> for links that appear un-clickable and <code>.active</code> to indicate the current page.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <nav aria-label="...">
                                        <ul class="pagination">
                                            <li class="page-item disabled">
                                                <a class="page-link">Previous</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">1</a>
                                            </li>
                                            <li class="page-item active" aria-current="page">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;nav aria-label=&quot;...&quot;&gt;    &lt;ul class=&quot;pagination&quot;&gt;      &lt;li class=&quot;page-item disabled&quot;&gt;        &lt;a class=&quot;page-link&quot;&gt;Previous&lt;/a&gt;      &lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;1&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item active&quot; aria-current=&quot;page&quot;&gt;        &lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;2&lt;/a&gt;      &lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;3&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;        &lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;Next&lt;/a&gt;      &lt;/li&gt;    &lt;/ul&gt;&lt;/nav&gt;</code></pre>
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
                                        Fancy larger or smaller pagination? Add <code>.pagination-lg</code> or <code>.pagination-sm</code> for additional sizes.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <nav aria-label="..." class="mb-4">
                                        <ul class="pagination pagination-lg">
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">1</span>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <nav aria-label="..." class="mb-4">
                                        <ul class="pagination">
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">1</span>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <nav aria-label="...">
                                        <ul class="pagination pagination-sm">
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">1</span>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                        </ul>
                                    </nav>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;nav aria-label=&quot;...&quot;&gt;    &lt;ul class=&quot;pagination&quot;&gt;      &lt;li class=&quot;page-item active&quot; aria-current=&quot;page&quot;&gt;        &lt;span class=&quot;page-link&quot;&gt;1&lt;/span&gt;      &lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;2&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;3&lt;/a&gt;&lt;/li&gt;    &lt;/ul&gt;&lt;/nav&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Alignment
                                    </h3>
                                    <p>
                                        Change the alignment of pagination components with flexbox utilities. For example, with <code>.justify-content-center</code>: <code>.justify-content-end</code>:
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-center">
                                        <code>.justify-content-center</code>
                                    </p>
                                    <nav class="mb-5">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item disabled">
                                                <a class="page-link">Previous</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <p class="text-end">
                                        <code>.justify-content-end</code>
                                    </p>
                                    <nav>
                                        <ul class="pagination justify-content-end">
                                            <li class="page-item disabled">
                                                <a class="page-link">Previous</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/components/pagination') }}#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;nav&gt;    &lt;ul class=&quot;pagination justify-content-center&quot;&gt;      &lt;li class=&quot;page-item disabled&quot;&gt;        &lt;a class=&quot;page-link&quot;&gt;Previous&lt;/a&gt;      &lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;1&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;2&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;3&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;page-item&quot;&gt;        &lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;Next&lt;/a&gt;      &lt;/li&gt;    &lt;/ul&gt;&lt;/nav&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
