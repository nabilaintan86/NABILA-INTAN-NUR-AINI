@extends('layouts.admin')
@section('title', 'Buttons - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Buttons
                                </h1>
                                <p>
                                    Use Bootstrap’s custom button styles for actions in forms, dialogs, and more with support for multiple sizes, states, and more.
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
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary">Primary</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-secondary">Secondary</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-success">Success</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-danger">Danger</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-warning">Warning</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-info">Info</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-light">Light</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-dark">Dark</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-link">Link</button>
                                        </li>
                                    </ul>
                                    <div class="form-note mt-4">
                                        Use <code>btn-{name}</code> for various btn options.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Primary&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Button tags
                                    </h3>
                                    <p>
                                        The <code>.btn</code> classes are designed to be used with the <code>button</button></code> element. However, you can also use these classes on <code>a</code> or <code>input</code> elements (though some browsers may apply a slightly different rendering).
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <a class="btn btn-primary" href="{{ url('/admin/components/buttons') }}#" role="button">Link</a>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="submit">Button</button>
                                        </li>
                                        <li>
                                            <input class="btn btn-primary" type="button" value="Input">
                                        </li>
                                        <li>
                                            <input class="btn btn-primary" type="submit" value="Submit">
                                        </li>
                                        <li>
                                            <input class="btn btn-primary" type="reset" value="Reset">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;ul class=&quot;d-flex flex-wrap gap g-3&quot;&gt;    &lt;li&gt;&lt;a class=&quot;btn btn-primary&quot; href=&quot;#&quot; role=&quot;button&quot;&gt;Link&lt;/a&gt;&lt;/li&gt;    &lt;li&gt; &lt;button class=&quot;btn btn-primary&quot; type=&quot;submit&quot;&gt;Button&lt;/button&gt;&lt;/li&gt;    &lt;li&gt;&lt;input class=&quot;btn btn-primary&quot; type=&quot;button&quot; value=&quot;Input&quot;&gt;&lt;/li&gt;    &lt;li&gt; &lt;input class=&quot;btn btn-primary&quot; type=&quot;submit&quot; value=&quot;Submit&quot;&gt;&lt;/li&gt;    &lt;li&gt;&lt;input class=&quot;btn btn-primary&quot; type=&quot;reset&quot; value=&quot;Reset&quot;&gt;&lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Outline buttons
                                    </h3>
                                    <p>
                                        In need of a button, but not the hefty background colors they bring? Replace the default modifier classes with the <code>btn-outline-*</code> ones to remove all background images and colors on any button.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-outline-primary">Primary</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-secondary">Secondary</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-success">Success</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-danger">Danger</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-warning">Warning</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-info">Info</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-light">Light</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-dark">Dark</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary&quot;&gt;Primary&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Soft buttons
                                    </h3>
                                    <p>
                                        In need of a button, but not the hefty background colors they bring? Add modifier classes with the <code>.btn-soft</code> ones to soften background colors on any button.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-soft btn-primary">Primary</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-secondary">Secondary</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-success">Success</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-danger">Danger</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-warning">Warning</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-info">Info</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-light">Light</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-dark">Dark</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;button type=&quot;button&quot; class=&quot;btn btn-soft btn-primary&quot;&gt;Primary&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Soft Outline buttons
                                    </h3>
                                    <p>
                                        In need of a button, but not the hefty background colors they bring? Add modifier classes with the <code>.btn-soft</code> ones to soften background colors on any button.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-soft btn-outline-primary">Primary</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-outline-secondary">Secondary</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-outline-success">Success</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-outline-danger">Danger</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-outline-warning">Warning</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-outline-info">Info</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-outline-light">Light</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-soft btn-outline-dark">Dark</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;button type=&quot;button&quot; class=&quot;btn btn-soft btn-outline-primary&quot;&gt;Primary&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Buttons with Icons
                                    </h3>
                                    <p>
                                        Try with flowing code examples to use icons in buttons
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary"><em class="icon ni ni-arrow-left"></em> <span>Left Icon</span></button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary"><span>Right Icon</span><em class="icon ni ni-arrow-right"></em></button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary btn-icon"><em class="icon ni ni-msg"></em></button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-color-primary btn-hover-primary btn-icon"><em class="icon ni ni-msg"></em></button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-color-dark btn-hover-primary btn-icon btn-soft"><em class="icon ni ni-msg"></em></button>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;&lt;em class=&quot;icon ni ni-arrow-left&quot;&gt;&lt;/em&gt; &lt;span&gt;Left Icon&lt;/span&gt;&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Sizes
                                    </h3>
                                    <p>
                                        Fancy larger or smaller buttons? Add <code>.btn-lg</code> or <code>.btn-sm</code> for additional sizes.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap align-items-center gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary btn-lg">Large button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary">Regular button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary btn-sm">Small button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"> Custom button </button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Regular button&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Rounded Buttons
                                    </h3>
                                    <p>
                                        Need to soften the buttons edges? Add <code>.rounded-pill</code> for rounded buttons.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap align-items-center gap g-3">
                                        <li>
                                            <button type="button" class="btn rounded-pill btn-primary btn-lg">Large button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn rounded-pill btn-primary">Regular button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn rounded-pill btn-primary btn-sm">Small button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn rounded-pill btn-primary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"> Custom button </button>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-8">&lt;button type=&quot;button&quot; class=&quot;btn rounded-pill btn-primary&quot;&gt;Regular button&lt;/button&gt;</code></pre>
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
                                        Make buttons look inactive by adding the disabled boolean attribute to <code>button</code>
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary" disabled>Primary button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-secondary" disabled>Button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-primary" disabled>Primary button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-outline-secondary" disabled>Button</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-9">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; disabled&gt;Primary button&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Block buttons
                                    </h3>
                                    <p>
                                        Create responsive stacks of full-width, “block buttons” like those in Bootstrap 4 with a mix of our display and gap utilities. By using utilities instead of button specific classes, we have much greater control over spacing, alignment, and responsive behaviors.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" type="button">Button</button><button class="btn btn-primary" type="button">Button</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-10">&lt;div class=&quot;d-grid gap-2&quot;&gt;    &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot;&gt;Button&lt;/button&gt;    &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot;&gt;Button&lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Toggle states
                                    </h3>
                                    <p>
                                        Add <code>data-bs-toggle="button"</code> to toggle a button’s active state. If you’re pre-toggling a button, you must manually add the <code>.active</code> class and <code>aria-pressed="true"</code> to ensure that it is conveyed appropriately to assistive technologies.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="button">Toggle button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary active" data-bs-toggle="button" aria-pressed="true">Active toggle button</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" disabled data-bs-toggle="button">Disabled toggle button</button>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-11">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;button&quot;&gt;Toggle button&lt;/button&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
