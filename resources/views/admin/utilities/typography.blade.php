@extends('layouts.admin')
@section('title', 'Typography - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Typography
                                </h1>
                                <p>
                                    Documentation and examples for Bootstrap typography, including global settings, headings, body text, lists, and more.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Headings
                                    </h3>
                                    <p>
                                        All HTML headings, <code>h1</code> through <code>h6</code>, are available.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Heading
                                                    </th>
                                                    <th>
                                                        Example
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <code>&lt;h1&gt;&lt;/h1&gt;</code>
                                                    </td>
                                                    <td>
                                                        <span class="h1">h1. Bootstrap heading</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>&lt;h2&gt;&lt;/h2&gt;</code>
                                                    </td>
                                                    <td>
                                                        <span class="h2">h2. Bootstrap heading</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>&lt;h3&gt;&lt;/h3&gt;</code>
                                                    </td>
                                                    <td>
                                                        <span class="h3">h3. Bootstrap heading</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>&lt;h4&gt;&lt;/h4&gt;</code>
                                                    </td>
                                                    <td>
                                                        <span class="h4">h4. Bootstrap heading</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>&lt;h5&gt;&lt;/h5&gt;</code>
                                                    </td>
                                                    <td>
                                                        <span class="h5">h5. Bootstrap heading</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>&lt;h6&gt;&lt;/h6&gt;</code>
                                                    </td>
                                                    <td>
                                                        <span class="h6">h6. Bootstrap heading</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;h1&gt;h1. Bootstrap heading&lt;/h1&gt;&lt;h2&gt;h2. Bootstrap heading&lt;/h2&gt;&lt;h3&gt;h3. Bootstrap heading&lt;/h3&gt;&lt;h4&gt;h4. Bootstrap heading&lt;/h4&gt;&lt;h5&gt;h5. Bootstrap heading&lt;/h5&gt;&lt;h6&gt;h6. Bootstrap heading&lt;/h6&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <p>
                                        <code>.h1</code> through <code>.h6</code> classes are also available, for when you want to match the font styling of a heading but cannot use the associated HTML element.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="h1">
                                        h1. Bootstrap heading
                                    </p>
                                    <p class="h2">
                                        h2. Bootstrap heading
                                    </p>
                                    <p class="h3">
                                        h3. Bootstrap heading
                                    </p>
                                    <p class="h4">
                                        h4. Bootstrap heading
                                    </p>
                                    <p class="h5">
                                        h5. Bootstrap heading
                                    </p>
                                    <p class="h6">
                                        h6. Bootstrap heading
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;p class=&quot;h1&quot;&gt;h1. Bootstrap heading&lt;/p&gt;&lt;p class=&quot;h2&quot;&gt;h2. Bootstrap heading&lt;/p&gt;&lt;p class=&quot;h3&quot;&gt;h3. Bootstrap heading&lt;/p&gt;&lt;p class=&quot;h4&quot;&gt;h4. Bootstrap heading&lt;/p&gt;&lt;p class=&quot;h5&quot;&gt;h5. Bootstrap heading&lt;/p&gt;&lt;p class=&quot;h6&quot;&gt;h6. Bootstrap heading&lt;/p&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Display headings
                                    </h3>
                                    <p>
                                        Traditional heading elements are designed to work best in the meat of your page content. When you need a heading to stand out, consider using a display heading— a larger, slightly more opinionated heading style.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h1 class="display-1">
                                        Display 1
                                    </h1>
                                    <h1 class="display-2">
                                        Display 2
                                    </h1>
                                    <h1 class="display-3">
                                        Display 3
                                    </h1>
                                    <h1 class="display-4">
                                        Display 4
                                    </h1>
                                    <h1 class="display-5">
                                        Display 5
                                    </h1>
                                    <h1 class="display-6">
                                        Display 6
                                    </h1>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;h1 class=&quot;display-1&quot;&gt;Display 1&lt;/h1&gt;&lt;h1 class=&quot;display-2&quot;&gt;Display 2&lt;/h1&gt;&lt;h1 class=&quot;display-3&quot;&gt;Display 3&lt;/h1&gt;&lt;h1 class=&quot;display-4&quot;&gt;Display 4&lt;/h1&gt;&lt;h1 class=&quot;display-5&quot;&gt;Display 5&lt;/h1&gt;&lt;h1 class=&quot;display-6&quot;&gt;Display 6&lt;/h1&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Lead
                                    </h3>
                                    <p>
                                        Make a paragraph stand out by adding <code>.lead</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p class="lead">
                                        This is a lead paragraph. It stands out from regular paragraphs.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;p&gt;Make a paragraph stand out by adding &lt;code&gt;.lead&lt;/code&gt;.&lt;/p&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Inline text elements
                                    </h3>
                                    <p>
                                        Styling for common inline HTML5 elements.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <p>
                                        You can use the mark tag to <mark>highlight</mark> text.
                                    </p>
                                    <p>
                                        <del>This line of text is meant to be treated as deleted text.</del>
                                    </p>
                                    <p>
                                        <s>This line of text is meant to be treated as no longer accurate.</s>
                                    </p>
                                    <p>
                                        <ins>This line of text is meant to be treated as an addition to the document.</ins>
                                    </p>
                                    <p>
                                        <u>This line of text will render as underlined.</u>
                                    </p>
                                    <p>
                                        <small>This line of text is meant to be treated as fine print.</small>
                                    </p>
                                    <p>
                                        <strong>This line rendered as bold text.</strong>
                                    </p>
                                    <p>
                                        <em>This line rendered as italicized text.</em>
                                    </p>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;p&gt;You can use the mark tag to &lt;mark&gt;highlight&lt;/mark&gt; text.&lt;/p&gt;&lt;p&gt;&lt;del&gt;This line of text is meant to be treated as deleted text.&lt;/del&gt;&lt;/p&gt;&lt;p&gt;&lt;s&gt;This line of text is meant to be treated as no longer accurate.&lt;/s&gt;&lt;/p&gt;&lt;p&gt;&lt;ins&gt;This line of text is meant to be treated as an addition to the document.&lt;/ins&gt;&lt;/p&gt;&lt;p&gt;&lt;u&gt;This line of text will render as underlined.&lt;/u&gt;&lt;/p&gt;&lt;p&gt;&lt;small&gt;This line of text is meant to be treated as fine print.&lt;/small&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;This line rendered as bold text.&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;&lt;em&gt;This line rendered as italicized text.&lt;/em&gt;&lt;/p&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Blockquotes
                                    </h3>
                                    <p>
                                        For quoting blocks of content from another source within your document. Wrap <code>blockquote class="blockquote"</code> around any HTML as the quote.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <figure>
                                    <blockquote class="blockquote">
                                        <p>
                                            A well-known quote, contained in a blockquote element.
                                        </p>
                                    </blockquote>
                                    <figcaption class="blockquote-footer"> Someone famous in <cite title="Source Title">Source Title</cite></figcaption></figure>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;figure&gt;    &lt;blockquote class=&quot;blockquote&quot;&gt;        &lt;p&gt;A well-known quote, contained in a blockquote element.&lt;/p&gt;    &lt;/blockquote&gt;    &lt;figcaption class=&quot;blockquote-footer&quot;&gt;        Someone famous in &lt;cite title=&quot;Source Title&quot;&gt;Source Title&lt;/cite&gt;    &lt;/figcaption&gt;&lt;/figure&gt;</code></pre>
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
                                        Use text utilities as needed to change the alignment of your blockquote
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-5">
                                        <p class="text-center">
                                            Use <code>.text-center</code> class for center alignment.
                                        </p>
                                        <figure class="text-center">
                                        <blockquote class="blockquote">
                                            <p>
                                                A well-known quote, contained in a blockquote element.
                                            </p>
                                        </blockquote>
                                        <figcaption class="blockquote-footer"> Someone famous in <cite title="Source Title">Source Title</cite></figcaption></figure>
                                    </div>
                                    <div>
                                        <p class="text-end">
                                            Use <code>.text-end</code> class for right alignment.
                                        </p>
                                        <figure class="text-end">
                                        <blockquote class="blockquote">
                                            <p>
                                                A well-known quote, contained in a blockquote element.
                                            </p>
                                        </blockquote>
                                        <figcaption class="blockquote-footer"> Someone famous in <cite title="Source Title">Source Title</cite></figcaption></figure>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;figure class=&quot;text-center&quot;&gt;    &lt;blockquote class=&quot;blockquote&quot;&gt;        &lt;p&gt;A well-known quote, contained in a blockquote element.&lt;/p&gt;    &lt;/blockquote&gt;    &lt;figcaption class=&quot;blockquote-footer&quot;&gt;        Someone famous in &lt;cite title=&quot;Source Title&quot;&gt;Source Title&lt;/cite&gt;    &lt;/figcaption&gt;&lt;/figure&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
