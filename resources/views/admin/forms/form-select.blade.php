@extends('layouts.admin')
@section('title', 'Select - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Custom Select Box & Tag
                                </h1>
                                <p>
                                    Here is some custom styled select fields using choice.js
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Single select input</label>
                                                <div class="form-control-wrap">
                                                    <select class="js-select" data-search="true" data-sort="false">
                                                        <option value="">
                                                            Select Payment Method
                                                        </option>
                                                        <option value="1">
                                                            PayPal
                                                        </option>
                                                        <option value="2">
                                                            Bank Transfer
                                                        </option>
                                                        <option value="3">
                                                            Skrill
                                                        </option>
                                                        <option value="4">
                                                            Moneygram
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Multiple select input</label>
                                                <div class="form-control-wrap">
                                                    <select class="js-select" multiple data-search="true" data-sort="false">
                                                        <option value="">
                                                            Select Payment Method
                                                        </option>
                                                        <option value="1">
                                                            PayPal
                                                        </option>
                                                        <option value="2">
                                                            Bank Transfer
                                                        </option>
                                                        <option value="3">
                                                            Skrill
                                                        </option>
                                                        <option value="4">
                                                            Moneygram
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>multiple</code> attribute to enable mutiple select on your <code>select || .js-select</code> option, <code>data-search="false"</code> to disable search option in select dropdown.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Tag (with cross)</label>
                                                <div class="form-control-wrap">
                                                    <input class="js-tags" value="Tag1,Tag2" type="text" placeholder="Enter something">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Tag (without cross)</label>
                                                <div class="form-control-wrap">
                                                    <input class="js-tags" value="Tag1,Tag2" type="text" data-cross="false" placeholder="Enter something">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>data-cross="false"</code> attribute to <code>input</code> to hide cross icon.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Disabled</label>
                                                <div class="form-control-wrap">
                                                    <select class="js-select" disabled>
                                                        <option value="">
                                                            Select Payment Method
                                                        </option>
                                                        <option value="1">
                                                            PayPal
                                                        </option>
                                                        <option value="2">
                                                            Bank Transfer
                                                        </option>
                                                        <option value="3">
                                                            Skrill
                                                        </option>
                                                        <option value="4">
                                                            Moneygram
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Tag Disabled</label>
                                                <div class="form-control-wrap">
                                                    <input class="js-tags" value="Tag1,Tag2" type="text" placeholder="Enter something" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>disabled</code> attribute to <code>select</code> and tag <code>input</code> for disabled state.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Select input
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;form-group&quot;&gt;    &lt;label class=&quot;form-label&quot;&gt;Single select input&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;select class=&quot;js-select&quot; data-search=&quot;true&quot; data-sort=&quot;false&quot;&gt;            &lt;option value=&quot;&quot;&gt;Select Payment Method&lt;/option&gt;            &lt;option value=&quot;1&quot;&gt;PayPal&lt;/option&gt;            &lt;option value=&quot;2&quot;&gt;Bank Transfer&lt;/option&gt;            &lt;option value=&quot;3&quot;&gt;Skrill&lt;/option&gt;            &lt;option value=&quot;4&quot;&gt;Moneygram&lt;/option&gt;        &lt;/select&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Tag
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;form-group&quot;&gt;    &lt;label class=&quot;form-label&quot;&gt;Tag (with cross)&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input class=&quot;js-tags&quot; value=&quot;Tag1,Tag2&quot; type=&quot;text&quot; placeholder=&quot;Enter something&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
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
                                    <div class="row g-3 gx-gs">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormSelect1" class="form-label">Default Select</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="exampleFormSelect1" aria-label="Default select example">
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormSelectDisabled" class="form-label">Disabled Select</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="exampleFormSelectDisabled" aria-label="Default select example" disabled>
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormSelectMultiple" class="form-label">Multiple Select</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="exampleFormSelectMultiple" multiple aria-label="multiple select example">
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormSelectMultipleDisabled" class="form-label">Disabled Multiple Select</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="exampleFormSelectMultipleDisabled" multiple aria-label="multiple select example" disabled>
                                                        <option selected>
                                                            Open this select menu
                                                        </option>
                                                        <option value="1">
                                                            One
                                                        </option>
                                                        <option selected value="2">
                                                            Two
                                                        </option>
                                                        <option value="3">
                                                            Three
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>multiple</code> or <code>disabled</code> attribute to <code>select</code> to enable multiple, disabled state.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Select
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormSelect1&quot; class=&quot;form-label&quot;&gt;Default Select&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;select class=&quot;form-select&quot; id=&quot;exampleFormSelect1&quot; aria-label=&quot;Default select example&quot;&gt;            &lt;option selected&gt;Open this select menu&lt;/option&gt;            &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;            &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;            &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;        &lt;/select&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            multiple Select
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-4" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormSelectMultiple&quot; class=&quot;form-label&quot;&gt;Multiple Select&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;select class=&quot;form-select&quot; id=&quot;exampleFormSelectMultiple&quot; multiple aria-label=&quot;multiple select example&quot;&gt;            &lt;option selected&gt;Open this select menu&lt;/option&gt;            &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;            &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;            &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;        &lt;/select&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Sizing
                                    </h3>
                                    <p>
                                        Set heights using classes like <code>.form-select-lg</code> and <code>.form-select-sm</code> on <code>.form-control</code> for large and small size
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example">
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
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <select class="form-select">
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
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm example">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            form select
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-5" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;form-control-wrap&quot;&gt;    &lt;select class=&quot;form-select&quot;        &lt;option selected&gt;Open this select menu&lt;/option&gt;        &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;        &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;        &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;    &lt;/select&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
