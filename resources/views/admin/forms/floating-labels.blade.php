@extends('layouts.admin')
@section('title', 'Floating labels - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Floating labels
                                </h1>
                                <p>
                                    Create beautifully simple form labels that float over your input fields.
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
                                    <div class="row gx-gs gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com"><label for="floatingInput">Email address</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                    <option selected>
                                                        Open this select menu
                                                    </option>
                                                    <option value="1">
                                                        One
                                                    </option>
                                                    <option value="2">
                                                        Two
                                                    </option>
                                                    <option value="3">
                                                        Three
                                                    </option>
                                                </select>
                                                <label for="floatingSelect">Works with selects</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" id="floatingInputValue" placeholder="name@example.com" value="test@example.com"><label for="floatingInputValue">Input with value</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="email" class="form-control is-invalid" id="floatingInputInvalid" placeholder="name@example.com" value="test@example.com"><label for="floatingInputInvalid">Invalid input</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea><label for="floatingTextarea">Comments</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            input
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;form-floating&quot;&gt;  &lt;input type=&quot;email&quot; class=&quot;form-control&quot; id=&quot;floatingInput&quot; placeholder=&quot;name@example.com&quot;&gt;  &lt;label for=&quot;floatingInput&quot;&gt;Email address&lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            select
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;form-floating&quot;&gt;  &lt;select class=&quot;form-select&quot; id=&quot;floatingSelect&quot; aria-label=&quot;Floating label select example&quot;&gt;    &lt;option selected&gt;Open this select menu&lt;/option&gt;    &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;    &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;    &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;  &lt;/select&gt;  &lt;label for=&quot;floatingSelect&quot;&gt;Works with selects&lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Readonly plaintext
                                    </h3>
                                    <p>
                                        Floating labels also support <code>.form-control-plaintext</code>, which can be helpful for toggling from an editable <code>input</code> to a plaintext value without affecting the page layout.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-control-wrap">
                                        <div class="form-floating mb-3">
                                            <input type="email" readonly class="form-control-plaintext" id="floatingEmptyPlaintextInput" placeholder="name@example.com"><label for="floatingEmptyPlaintextInput">Empty input</label>
                                        </div>
                                    </div>
                                    <div class="form-control-wrap">
                                        <div class="form-floating mb-3">
                                            <input type="email" readonly class="form-control-plaintext" id="floatingPlaintextInput" placeholder="name@example.com" value="name@example.com"><label for="floatingPlaintextInput">Input with value</label>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;form-floating&quot;&gt;  &lt;input type=&quot;email&quot; readonly class=&quot;form-control-plaintext&quot; id=&quot;floatingEmptyPlaintextInput&quot; placeholder=&quot;name@example.com&quot;&gt;  &lt;label for=&quot;floatingEmptyPlaintextInput&quot;&gt;Empty input&lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Layout
                                    </h3>
                                    <p>
                                        When working with the Bootstrap grid system, be sure to place form elements within column classes.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md">
                                            <div class="form-control-wrap">
                                                <div class="form-floating">
                                                    <input type="email" class="form-control" id="floatingInputGrid" placeholder="name@example.com" value="mdo@example.com"><label for="floatingInputGrid">Email address</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-control-wrap">
                                                <div class="form-floating">
                                                    <select class="form-select" id="floatingSelectGrid">
                                                        <option selected>
                                                            Open this select menu
                                                        </option>
                                                        <option value="1">
                                                            One
                                                        </option>
                                                        <option value="2">
                                                            Two
                                                        </option>
                                                        <option value="3">
                                                            Three
                                                        </option>
                                                    </select>
                                                    <label for="floatingSelectGrid">Works with selects</label>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;row g-2&quot;&gt;    &lt;div class=&quot;col-md&quot;&gt;      &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;form-floating&quot;&gt;          &lt;input type=&quot;email&quot; class=&quot;form-control&quot; id=&quot;floatingInputGrid&quot; placeholder=&quot;name@example.com&quot; value=&quot;mdo@example.com&quot;&gt;          &lt;label for=&quot;floatingInputGrid&quot;&gt;Email address&lt;/label&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;col-md&quot;&gt;      &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;form-floating&quot;&gt;          &lt;select class=&quot;form-select&quot; id=&quot;floatingSelectGrid&quot;&gt;            &lt;option selected&gt;Open this select menu&lt;/option&gt;            &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;            &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;            &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;          &lt;/select&gt;          &lt;label for=&quot;floatingSelectGrid&quot;&gt;Works with selects&lt;/label&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
