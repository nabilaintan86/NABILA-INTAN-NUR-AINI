@extends('layouts.admin')
@section('title', 'List group - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    List group
                                </h1>
                                <p>
                                    List groups are a flexible and powerful component for displaying a series of content. Modify and extend them to support just about any content within
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
                                        The most basic list group is an unordered list with list items and the proper classes. Build upon it with the options that follow, or with your own CSS as needed.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group">
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
                                            A fourth item
                                        </li>
                                        <li class="list-group-item">
                                            And a fifth one
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;ul class=&quot;list-group&quot;&gt;  &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A fourth item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;And a fifth one&lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Active items
                                    </h3>
                                    <p>
                                        Add <code>.active</code> to a <code>.list-group-item </code> to indicate the current active selection.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item active" aria-current="true">
                                            An active item
                                        </li>
                                        <li class="list-group-item">
                                            A second item
                                        </li>
                                        <li class="list-group-item">
                                            A third item
                                        </li>
                                        <li class="list-group-item">
                                            A fourth item
                                        </li>
                                        <li class="list-group-item">
                                            And a fifth one
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;ul class=&quot;list-group&quot;&gt;  &lt;li class=&quot;list-group-item active&quot; aria-current=&quot;true&quot;&gt;An active item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A fourth item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;And a fifth one&lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Disabled items
                                    </h3>
                                    <p>
                                        Add <code> .disabled</code> to a <code>.list-group-item</code> to make it appear disabled. Note that some elements with <code>.disabled</code> will also require custom JavaScript to fully disable their click events (e.g., links).
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item disabled" aria-disabled="true">
                                            A disabled item
                                        </li>
                                        <li class="list-group-item">
                                            A second item
                                        </li>
                                        <li class="list-group-item">
                                            A third item
                                        </li>
                                        <li class="list-group-item">
                                            A fourth item
                                        </li>
                                        <li class="list-group-item">
                                            And a fifth one
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;ul class=&quot;list-group&quot;&gt;  &lt;li class=&quot;list-group-item disabled&quot; aria-disabled=&quot;true&quot;&gt;A disabled item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A fourth item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;And a fifth one&lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Links and buttons
                                    </h3>
                                    <p>
                                        Use <code>a</code> s or <code>button</code> s to create actionable list group items with hover, disabled, and active states by adding <code>.list-group-item-action</code>. We separate these pseudo-classes to ensure list groups made of non-interactive elements (like <code>li</code> s or <code>div</code> s) don’t provide a click or tap affordance.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action active" aria-current="true"> The current link item </a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action">A second link item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action">A third link item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action">A fourth link item</a><a class="list-group-item list-group-item-action disabled">A disabled link item</a>
                                    </div>
                                    <p class="mt-5">
                                        With <code>button</code> s, you can also make use of the disabled attribute instead of the .disabled class. Sadly, <code>a</code> s don’t support the disabled attribute.
                                    </p>
                                    <div class="list-group">
                                        <button type="button" class="list-group-item list-group-item-action active" aria-current="true"> The current button </button><button type="button" class="list-group-item list-group-item-action">A second button item</button><button type="button" class="list-group-item list-group-item-action">A third button item</button><button type="button" class="list-group-item list-group-item-action">A fourth button item</button><button type="button" class="list-group-item list-group-item-action" disabled>A disabled button item</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;list-group&quot;&gt;    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action active&quot; aria-current=&quot;true&quot;&gt;      The current link item    &lt;/a&gt;    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A second link item&lt;/a&gt;    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A third link item&lt;/a&gt;    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A fourth link item&lt;/a&gt;    &lt;a class=&quot;list-group-item list-group-item-action disabled&quot;&gt;A disabled link item&lt;/a&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Flush
                                    </h3>
                                    <p>
                                        Add <code>.list-group-flush</code> to remove some borders and rounded corners to render list group items edge-to-edge in a parent container (e.g., cards).
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
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
                                            A fourth item
                                        </li>
                                        <li class="list-group-item">
                                            And a fifth one
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;ul class=&quot;list-group list-group-flush&quot;&gt;  &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;A fourth item&lt;/li&gt;  &lt;li class=&quot;list-group-item&quot;&gt;And a fifth one&lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Numbered
                                    </h3>
                                    <p>
                                        Add the <code>.list-group-numbered</code> modifier class (and optionally use an <code>ol</code> element) to opt into numbered list group items. Numbers are generated via CSS (as opposed to a <code>ol</code> s default browser styling) for better placement inside list group items and to allow for better customization.
                                    </p>
                                    <p>
                                        Numbers are generated by counter-reset on the <code>ol</code>, and then styled and placed with a ::before pseudo-element on the <code>li</code> with counter-increment and content.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ol class="list-group list-group-numbered">
                                        <li class="list-group-item">
                                            A list item
                                        </li>
                                        <li class="list-group-item">
                                            A list item
                                        </li>
                                        <li class="list-group-item">
                                            A list item
                                        </li>
                                    </ol>
                                    <p class="mt-5">
                                        These work great with custom content as well.
                                    </p>
                                    <ol class="list-group list-group-numbered">
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    Subheading
                                                </div>
                                                Content for list item
                                            </div>
                                            <span class="badge bg-primary rounded-pill">14</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    Subheading
                                                </div>
                                                Content for list item
                                            </div>
                                            <span class="badge bg-primary rounded-pill">14</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    Subheading
                                                </div>
                                                Content for list item
                                            </div>
                                            <span class="badge bg-primary rounded-pill">14</span>
                                        </li>
                                    </ol>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;ol class=&quot;list-group list-group-numbered&quot;&gt;    &lt;li class=&quot;list-group-item&quot;&gt;A list item&lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;A list item&lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;A list item&lt;/li&gt;&lt;/ol&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            with custom content
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-custom-content" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-custom-content">&lt;ol class=&quot;list-group list-group-numbered&quot;&gt;    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-start&quot;&gt;      &lt;div class=&quot;ms-2 me-auto&quot;&gt;        &lt;div class=&quot;fw-bold&quot;&gt;Subheading&lt;/div&gt;        Content for list item      &lt;/div&gt;      &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;14&lt;/span&gt;    &lt;/li&gt;    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-start&quot;&gt;      &lt;div class=&quot;ms-2 me-auto&quot;&gt;        &lt;div class=&quot;fw-bold&quot;&gt;Subheading&lt;/div&gt;        Content for list item      &lt;/div&gt;      &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;14&lt;/span&gt;    &lt;/li&gt;    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-start&quot;&gt;      &lt;div class=&quot;ms-2 me-auto&quot;&gt;        &lt;div class=&quot;fw-bold&quot;&gt;Subheading&lt;/div&gt;        Content for list item      &lt;/div&gt;      &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;14&lt;/span&gt;    &lt;/li&gt;&lt;/ol&gt;</code></pre>
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
                                        Add <code>.list-group-horizontal</code> to change the layout of list group items from vertical to horizontal across all breakpoints. Alternatively, choose a responsive variant <code>.list-group-horizontal-{sm|md|lg|xl|xxl}</code> to make a list group horizontal starting at that breakpoint’s <code> min-width</code>. Currently horizontal list groups cannot be combined with flush list groups.
                                    </p>
                                    <p>
                                        ProTip: Want equal-width list group items when horizontal? Add <code>.flex-fill</code> to each list group item.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body gap g-3">
                                    <ul class="list-group list-group-horizontal">
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
                                    <ul class="list-group list-group-horizontal-sm">
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
                                    <ul class="list-group list-group-horizontal-md">
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
                                    <ul class="list-group list-group-horizontal-lg">
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
                                    <ul class="list-group list-group-horizontal-xl">
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
                                    <ul class="list-group list-group-horizontal-xxl">
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
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;ul class=&quot;list-group list-group-horizontal&quot;&gt;    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Contextual classes
                                    </h3>
                                    <p>
                                        Use contextual classes to style list items with a stateful background and color.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            A simple default list group item
                                        </li>
                                        <li class="list-group-item list-group-item-primary">
                                            A simple primary list group item
                                        </li>
                                        <li class="list-group-item list-group-item-secondary">
                                            A simple secondary list group item
                                        </li>
                                        <li class="list-group-item list-group-item-success">
                                            A simple success list group item
                                        </li>
                                        <li class="list-group-item list-group-item-danger">
                                            A simple danger list group item
                                        </li>
                                        <li class="list-group-item list-group-item-warning">
                                            A simple warning list group item
                                        </li>
                                        <li class="list-group-item list-group-item-info">
                                            A simple info list group item
                                        </li>
                                        <li class="list-group-item list-group-item-light">
                                            A simple light list group item
                                        </li>
                                        <li class="list-group-item list-group-item-dark">
                                            A simple dark list group item
                                        </li>
                                    </ul>
                                    <p class="mt-4 wide-md">
                                        Contextual classes also work with <code>.list-group-item-action</code>. Note the addition of the hover styles here not present in the previous example. Also supported is the <code>.active</code> state; apply it to indicate an active selection on a contextual list group item.
                                    </p>
                                    <div class="list-group">
                                        <a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action">A simple default list group item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action list-group-item-primary">A simple primary list group item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action list-group-item-secondary">A simple secondary list group item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action list-group-item-success">A simple success list group item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action list-group-item-danger">A simple danger list group item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action list-group-item-warning">A simple warning list group item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action list-group-item-info">A simple info list group item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action list-group-item-light">A simple light list group item</a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action list-group-item-dark">A simple dark list group item</a>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-8">&lt;ul class=&quot;list-group&quot;&gt;    &lt;li class=&quot;list-group-item&quot;&gt;A simple default list group item&lt;/li&gt;    &lt;li class=&quot;list-group-item list-group-item-primary&quot;&gt;A simple primary list group item&lt;/li&gt;    &lt;li class=&quot;list-group-item list-group-item-secondary&quot;&gt;A simple secondary list group item&lt;/li&gt;    &lt;li class=&quot;list-group-item list-group-item-success&quot;&gt;A simple success list group item&lt;/li&gt;    &lt;li class=&quot;list-group-item list-group-item-danger&quot;&gt;A simple danger list group item&lt;/li&gt;    &lt;li class=&quot;list-group-item list-group-item-warning&quot;&gt;A simple warning list group item&lt;/li&gt;    &lt;li class=&quot;list-group-item list-group-item-info&quot;&gt;A simple info list group item&lt;/li&gt;    &lt;li class=&quot;list-group-item list-group-item-light&quot;&gt;A simple light list group item&lt;/li&gt;    &lt;li class=&quot;list-group-item list-group-item-dark&quot;&gt;A simple dark list group item&lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        With badges
                                    </h3>
                                    <p>
                                        Add badges to any list group item to show unread counts, activity, and more with the help of some utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            A list item <span class="badge bg-primary rounded-pill">14</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            A second list item <span class="badge bg-primary rounded-pill">2</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            A third list item <span class="badge bg-primary rounded-pill">1</span>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-9">&lt;ul class=&quot;list-group&quot;&gt;    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-center&quot;&gt;      A list item      &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;14&lt;/span&gt;    &lt;/li&gt;    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-center&quot;&gt;      A second list item      &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;2&lt;/span&gt;    &lt;/li&gt;    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-center&quot;&gt;      A third list item      &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;1&lt;/span&gt;    &lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Custom content
                                    </h3>
                                    <p>
                                        Add nearly any HTML within, even for linked list groups like the one below, with the help of flexbox utilities.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action active py-3" aria-current="true">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">
                                                List group item heading
                                            </h5>
                                            <small>3 days ago</small>
                                        </div>
                                        <p class="mb-1">
                                            Some placeholder content in a paragraph.
                                        </p>
                                        <small>And some small print.</small></a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action py-3">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">
                                                List group item heading
                                            </h5>
                                            <small class="text-muted">3 days ago</small>
                                        </div>
                                        <p class="mb-1">
                                            Some placeholder content in a paragraph.
                                        </p>
                                        <small class="text-muted">And some muted small print.</small></a><a href="{{ url('/admin/components/list-group') }}#" class="list-group-item list-group-item-action py-3">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">
                                                List group item heading
                                            </h5>
                                            <small class="text-muted">3 days ago</small>
                                        </div>
                                        <p class="mb-1">
                                            Some placeholder content in a paragraph.
                                        </p>
                                        <small class="text-muted">And some muted small print.</small></a>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-10">&lt;div class=&quot;list-group&quot;&gt;    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action active py-3&quot; aria-current=&quot;true&quot;&gt;      &lt;div class=&quot;d-flex w-100 justify-content-between&quot;&gt;        &lt;h5 class=&quot;mb-1&quot;&gt;List group item heading&lt;/h5&gt;        &lt;small&gt;3 days ago&lt;/small&gt;      &lt;/div&gt;      &lt;p class=&quot;mb-1&quot;&gt;Some placeholder content in a paragraph.&lt;/p&gt;      &lt;small&gt;And some small print.&lt;/small&gt;    &lt;/a&gt;    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action py-3&quot;&gt;      &lt;div class=&quot;d-flex w-100 justify-content-between&quot;&gt;        &lt;h5 class=&quot;mb-1&quot;&gt;List group item heading&lt;/h5&gt;        &lt;small class=&quot;text-muted&quot;&gt;3 days ago&lt;/small&gt;      &lt;/div&gt;      &lt;p class=&quot;mb-1&quot;&gt;Some placeholder content in a paragraph.&lt;/p&gt;      &lt;small class=&quot;text-muted&quot;&gt;And some muted small print.&lt;/small&gt;    &lt;/a&gt;    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action py-3&quot;&gt;      &lt;div class=&quot;d-flex w-100 justify-content-between&quot;&gt;        &lt;h5 class=&quot;mb-1&quot;&gt;List group item heading&lt;/h5&gt;        &lt;small class=&quot;text-muted&quot;&gt;3 days ago&lt;/small&gt;      &lt;/div&gt;      &lt;p class=&quot;mb-1&quot;&gt;Some placeholder content in a paragraph.&lt;/p&gt;      &lt;small class=&quot;text-muted&quot;&gt;And some muted small print.&lt;/small&gt;    &lt;/a&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Checkboxes and radios
                                    </h3>
                                    <p>
                                        Place Bootstrap’s checkboxes and radios within list group items and customize as needed. You can use them without <code>label</code> s, but please remember to include an <code>aria-label</code> attribute and value for accessibility.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" value="" aria-label="..."> First checkbox
                                        </li>
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" value="" aria-label="..."> Second checkbox
                                        </li>
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" value="" aria-label="..."> Third checkbox
                                        </li>
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" value="" aria-label="..."> Fourth checkbox
                                        </li>
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" value="" aria-label="..."> Fifth checkbox
                                        </li>
                                    </ul>
                                    <p class="mt-4">
                                        And if you want <code>label</code> as the <code>.list-group-item</code> for large hit areas, you can do that, too.
                                    </p>
                                    <div class="list-group">
                                        <label class="list-group-item"><input class="form-check-input me-1" type="checkbox" value=""> First checkbox </label><label class="list-group-item"><input class="form-check-input me-1" type="checkbox" value=""> Second checkbox </label><label class="list-group-item"><input class="form-check-input me-1" type="checkbox" value=""> Third checkbox </label><label class="list-group-item"><input class="form-check-input me-1" type="checkbox" value=""> Fourth checkbox </label><label class="list-group-item"><input class="form-check-input me-1" type="checkbox" value=""> Fifth checkbox </label>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-11">&lt;ul class=&quot;list-group&quot;&gt;    &lt;li class=&quot;list-group-item&quot;&gt;      &lt;input class=&quot;form-check-input me-1&quot; type=&quot;checkbox&quot; value=&quot;&quot; aria-label=&quot;...&quot;&gt;      First checkbox    &lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;      &lt;input class=&quot;form-check-input me-1&quot; type=&quot;checkbox&quot; value=&quot;&quot; aria-label=&quot;...&quot;&gt;      Second checkbox    &lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;      &lt;input class=&quot;form-check-input me-1&quot; type=&quot;checkbox&quot; value=&quot;&quot; aria-label=&quot;...&quot;&gt;      Third checkbox    &lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;      &lt;input class=&quot;form-check-input me-1&quot; type=&quot;checkbox&quot; value=&quot;&quot; aria-label=&quot;...&quot;&gt;      Fourth checkbox    &lt;/li&gt;    &lt;li class=&quot;list-group-item&quot;&gt;      &lt;input class=&quot;form-check-input me-1&quot; type=&quot;checkbox&quot; value=&quot;&quot; aria-label=&quot;...&quot;&gt;      Fifth checkbox    &lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        JavaScript behavior
                                    </h3>
                                    <p>
                                        Use the tab JavaScript plugin—include it individually or through the compiled <code>bootstrap.js</code> file—to extend our list group to create tabbable panes of local content.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-md-4">
                                            <div class="list-group" id="list-tab" role="tablist">
                                                <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list" href="{{ url('/admin/components/list-group') }}#list-home" role="tab" aria-controls="list-home">Home</a><a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list" href="{{ url('/admin/components/list-group') }}#list-profile" role="tab" aria-controls="list-profile">Profile</a><a class="list-group-item list-group-item-action" id="list-messages-list" data-bs-toggle="list" href="{{ url('/admin/components/list-group') }}#list-messages" role="tab" aria-controls="list-messages">Messages</a><a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="{{ url('/admin/components/list-group') }}#list-settings" role="tab" aria-controls="list-settings">Settings</a>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                                                    Some placeholder content in a paragraph relating to <strong>"Home"</strong>. And some more content, used here just to pad out and fill this tab panel. In production, you would obviously have more real content here. And not just text. It could be anything, really. Text, images, forms.
                                                </div>
                                                <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                                                    Some placeholder content in a paragraph relating to <strong>"Profile"</strong>. And some more content, used here just to pad out and fill this tab panel. In production, you would obviously have more real content here. And not just text. It could be anything, really. Text, images, forms.
                                                </div>
                                                <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
                                                    Some placeholder content in a paragraph relating to <strong>"Messages"</strong>. And some more content, used here just to pad out and fill this tab panel. In production, you would obviously have more real content here. And not just text. It could be anything, really. Text, images, forms.
                                                </div>
                                                <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
                                                    Some placeholder content in a paragraph relating to <strong>"Settings"</strong>. And some more content, used here just to pad out and fill this tab panel. In production, you would obviously have more real content here. And not just text. It could be anything, really. Text, images, forms.
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
                                            <button data-clipboard-target="#code-example-12" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-12">&lt;div class=&quot;row&quot;&gt;    &lt;div class=&quot;col-4&quot;&gt;      &lt;div class=&quot;list-group&quot; id=&quot;list-tab&quot; role=&quot;tablist&quot;&gt;        &lt;a class=&quot;list-group-item list-group-item-action active&quot; id=&quot;list-home-list&quot; data-bs-toggle=&quot;list&quot; href=&quot;#list-home&quot; role=&quot;tab&quot; aria-controls=&quot;list-home&quot;&gt;Home&lt;/a&gt;        &lt;a class=&quot;list-group-item list-group-item-action&quot; id=&quot;list-profile-list&quot; data-bs-toggle=&quot;list&quot; href=&quot;#list-profile&quot; role=&quot;tab&quot; aria-controls=&quot;list-profile&quot;&gt;Profile&lt;/a&gt;        &lt;a class=&quot;list-group-item list-group-item-action&quot; id=&quot;list-messages-list&quot; data-bs-toggle=&quot;list&quot; href=&quot;#list-messages&quot; role=&quot;tab&quot; aria-controls=&quot;list-messages&quot;&gt;Messages&lt;/a&gt;        &lt;a class=&quot;list-group-item list-group-item-action&quot; id=&quot;list-settings-list&quot; data-bs-toggle=&quot;list&quot; href=&quot;#list-settings&quot; role=&quot;tab&quot; aria-controls=&quot;list-settings&quot;&gt;Settings&lt;/a&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col-8&quot;&gt;      &lt;div class=&quot;tab-content&quot; id=&quot;nav-tabContent&quot;&gt;        &lt;div class=&quot;tab-pane fade show active&quot; id=&quot;list-home&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;list-home-list&quot;&gt;            Some placeholder content in a paragraph relating to &lt;strong&gt;&quot;Home&quot;&lt;/strong&gt;. And some more content, used here just to pad out and fill this tab panel. In production, you would obviously have more real content here. And not just text. It could be anything, really. Text, images, forms.        &lt;/div&gt;        &lt;div class=&quot;tab-pane fade&quot; id=&quot;list-profile&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;list-profile-list&quot;&gt;            Some placeholder content in a paragraph relating to &lt;strong&gt;&quot;Profile&quot;&lt;/strong&gt;. And some more content, used here just to pad out and fill this tab panel. In production, you would obviously have more real content here. And not just text. It could be anything, really. Text, images, forms.        &lt;/div&gt;        &lt;div class=&quot;tab-pane fade&quot; id=&quot;list-messages&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;list-messages-list&quot;&gt;            Some placeholder content in a paragraph relating to &lt;strong&gt;&quot;Messages&quot;&lt;/strong&gt;. And some more content, used here just to pad out and fill this tab panel. In production, you would obviously have more real content here. And not just text. It could be anything, really. Text, images, forms.        &lt;/div&gt;        &lt;div class=&quot;tab-pane fade&quot; id=&quot;list-settings&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;list-settings-list&quot;&gt;            Some placeholder content in a paragraph relating to &lt;strong&gt;&quot;Settings&quot;&lt;/strong&gt;. And some more content, used here just to pad out and fill this tab panel. In production, you would obviously have more real content here. And not just text. It could be anything, really. Text, images, forms.        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Using data attributes
                                    </h3>
                                    <p>
                                        You can activate a list group navigation without writing any JavaScript by simply specifying <code>data-bs-toggle="list"</code> or on an element. Use these data attributes on <code>.list-group-item</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div role="tabpanel">
                                        <div class="list-group mb-4" id="myList" role="tablist">
                                            <a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="{{ url('/admin/components/list-group') }}#home" role="tab">Home</a><a class="list-group-item list-group-item-action" data-bs-toggle="list" href="{{ url('/admin/components/list-group') }}#profile" role="tab">Profile</a><a class="list-group-item list-group-item-action" data-bs-toggle="list" href="{{ url('/admin/components/list-group') }}#messages" role="tab">Messages</a><a class="list-group-item list-group-item-action" data-bs-toggle="list" href="{{ url('/admin/components/list-group') }}#settings" role="tab">Settings</a>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="home" role="tabpanel">
                                                <strong>Home</strong> dolor sit amet consectetur adipisicing elit. A unde voluptatem repellat tenetur quasi voluptas magnam sed numquam et aliquam possimus qui, nesciunt similique animi quas totam, ratione illo corporis.
                                            </div>
                                            <div class="tab-pane" id="profile" role="tabpanel">
                                                <strong>Profile</strong> dolor sit amet consectetur adipisicing elit. A unde voluptatem repellat tenetur quasi voluptas magnam sed numquam et aliquam possimus qui, nesciunt similique animi quas totam, ratione illo corporis.
                                            </div>
                                            <div class="tab-pane" id="messages" role="tabpanel">
                                                <strong>Message</strong> dolor sit amet consectetur adipisicing elit. A unde voluptatem repellat tenetur quasi voluptas magnam sed numquam et aliquam possimus qui, nesciunt similique animi quas totam, ratione illo corporis.
                                            </div>
                                            <div class="tab-pane" id="settings" role="tabpanel">
                                                <strong>Setting</strong> dolor sit amet consectetur adipisicing elit. A unde voluptatem repellat tenetur quasi voluptas magnam sed numquam et aliquam possimus qui, nesciunt similique animi quas totam, ratione illo corporis.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-13">&lt;div role=&quot;tabpanel&quot;&gt;    &lt;!-- List group --&gt;    &lt;div class=&quot;list-group mb-4&quot; id=&quot;myList&quot; role=&quot;tablist&quot;&gt;      &lt;a class=&quot;list-group-item list-group-item-action active&quot; data-bs-toggle=&quot;list&quot; href=&quot;#home&quot; role=&quot;tab&quot;&gt;Home&lt;/a&gt;      &lt;a class=&quot;list-group-item list-group-item-action&quot; data-bs-toggle=&quot;list&quot; href=&quot;#profile&quot; role=&quot;tab&quot;&gt;Profile&lt;/a&gt;      &lt;a class=&quot;list-group-item list-group-item-action&quot; data-bs-toggle=&quot;list&quot; href=&quot;#messages&quot; role=&quot;tab&quot;&gt;Messages&lt;/a&gt;      &lt;a class=&quot;list-group-item list-group-item-action&quot; data-bs-toggle=&quot;list&quot; href=&quot;#settings&quot; role=&quot;tab&quot;&gt;Settings&lt;/a&gt;    &lt;/div&gt;    &lt;!-- Tab panes --&gt;    &lt;div class=&quot;tab-content&quot;&gt;      &lt;div class=&quot;tab-pane active&quot; id=&quot;home&quot; role=&quot;tabpanel&quot;&gt;            &lt;strong&gt;Home&lt;/strong&gt; dolor sit amet consectetur adipisicing elit. A unde voluptatem repellat tenetur quasi voluptas magnam sed numquam et aliquam possimus qui, nesciunt similique animi quas totam, ratione illo corporis.      &lt;/div&gt;      &lt;div class=&quot;tab-pane&quot; id=&quot;profile&quot; role=&quot;tabpanel&quot;&gt;            &lt;strong&gt;Profile&lt;/strong&gt; dolor sit amet consectetur adipisicing elit. A unde voluptatem repellat tenetur quasi voluptas magnam sed numquam et aliquam possimus qui, nesciunt similique animi quas totam, ratione illo corporis.      &lt;/div&gt;      &lt;div class=&quot;tab-pane&quot; id=&quot;messages&quot; role=&quot;tabpanel&quot;&gt;            &lt;strong&gt;Message&lt;/strong&gt; dolor sit amet consectetur adipisicing elit. A unde voluptatem repellat tenetur quasi voluptas magnam sed numquam et aliquam possimus qui, nesciunt similique animi quas totam, ratione illo corporis.      &lt;/div&gt;      &lt;div class=&quot;tab-pane&quot; id=&quot;settings&quot; role=&quot;tabpanel&quot;&gt;            &lt;strong&gt;Setting&lt;/strong&gt; dolor sit amet consectetur adipisicing elit. A unde voluptatem repellat tenetur quasi voluptas magnam sed numquam et aliquam possimus qui, nesciunt similique animi quas totam, ratione illo corporis.      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt; </code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
