@extends('layouts.admin')
@section('title', 'Breadcrumb - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Breadcrumb
                                </h1>
                                <p>
                                    Indicate the current page’s location within a navigational hierarchy that automatically adds separators via CSS.
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
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Home
                                            </li>
                                        </ol>
                                    </nav>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/components/breadcrumb') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Library
                                            </li>
                                        </ol>
                                    </nav>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/components/breadcrumb') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/components/breadcrumb') }}#">Library</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Data
                                            </li>
                                        </ol>
                                    </nav>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;nav aria-label=&quot;breadcrumb&quot;&gt;    &lt;ol class=&quot;breadcrumb&quot;&gt;      &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;#&quot;&gt;Home&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;#&quot;&gt;Library&lt;/a&gt;&lt;/li&gt;      &lt;li class=&quot;breadcrumb-item active&quot; aria-current=&quot;page&quot;&gt;Data&lt;/li&gt;    &lt;/ol&gt;&lt;/nav&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dividers
                                    </h3>
                                    <p>
                                        Add <code>.breadcrumb-arrow</code> class to <code>breadcrumb</code> for arrow styling
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Home
                                            </li>
                                        </ol>
                                    </nav>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/components/breadcrumb') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Library
                                            </li>
                                        </ol>
                                    </nav>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/components/breadcrumb') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/components/breadcrumb') }}#">Library</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Data
                                            </li>
                                        </ol>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;nav&gt;  &lt;ol class=&quot;breadcrumb breadcrumb-arrow&quot;&gt;    &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;#&quot;&gt;Home&lt;/a&gt;&lt;/li&gt;    &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;#&quot;&gt;Library&lt;/a&gt;&lt;/li&gt;    &lt;li class=&quot;breadcrumb-item active&quot; aria-current=&quot;page&quot;&gt;Data&lt;/li&gt;  &lt;/ol&gt;&lt;/nav&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
