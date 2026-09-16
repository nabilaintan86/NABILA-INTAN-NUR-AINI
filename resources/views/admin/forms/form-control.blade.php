@extends('layouts.admin')
@section('title', 'Form controls - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Form controls
                                </h1>
                                <p>
                                    Give textual form controls like <code>input</code>s and <code>textarea</code>s an upgrade with custom styles, sizing, focus states, and more.
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
                                    <div class="row g-3 gx-gs">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlInputText1" class="form-label">Input text</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="exampleFormControlInputText1" placeholder="Input text placeholder">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlInputText2" class="form-label">Input with hint</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-control-hint">
                                                        <span>hint</span>
                                                    </div>
                                                    <input type="text" class="form-control" id="exampleFormControlInputText2" placeholder="Input text placeholder">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlInputText3" class="form-label">Input with start icon</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-control-icon start">
                                                        <em class="icon ni ni-eye"></em>
                                                    </div>
                                                    <input type="text" class="form-control" id="exampleFormControlInputText3" placeholder="Input text placeholder">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlInputText4" class="form-label">Input with end icon</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-control-icon end">
                                                        <em class="icon ni ni-eye"></em>
                                                    </div>
                                                    <input type="text" class="form-control" id="exampleFormControlInputText4" placeholder="Input text placeholder">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlInputText5" class="form-label">Default Select</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="exampleFormControlInputText5" aria-label="Default select example">
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
                                                <label for="exampleFormControlInputText6" class="form-label">Default file input example</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="file" id="exampleFormControlInputText6">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlInputText7" class="form-label">Multiple Select</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="exampleFormControlInputText7" multiple aria-label="multiple select example">
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
                                                <label for="exampleFormControlTextarea8" class="form-label">Example textarea</label>
                                                <div class="form-control-wrap">
                                                    <textarea placeholder="Textarea Placeholder" class="form-control" id="exampleFormControlTextarea8" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Input - Basic
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlInputText1&quot; class=&quot;form-label&quot;&gt;Input text&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;exampleFormControlInputText1&quot; placeholder=&quot;Input text placeholder&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Input - With hint
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlInputText2&quot; class=&quot;form-label&quot;&gt;Input text&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;form-control-hint&quot;&gt;&lt;span&gt;hint&lt;/span&gt;&lt;/div&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;exampleFormControlInputText2&quot; placeholder=&quot;Input text placeholder&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Input - With start icon
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlInputText3&quot; class=&quot;form-label&quot;&gt;Input with start icon&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;form-control-icon start&quot;&gt;&lt;em class=&quot;icon ni ni-eye&quot;&gt;&lt;/em&gt;&lt;/div&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;exampleFormControlInputText3&quot; placeholder=&quot;Input text placeholder&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Input - With end icon
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-4" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlInputText4&quot; class=&quot;form-label&quot;&gt;Input with end icon&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;form-control-icon end&quot;&gt;&lt;em class=&quot;icon ni ni-eye&quot;&gt;&lt;/em&gt;&lt;/div&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;exampleFormControlInputText4&quot; placeholder=&quot;Input text placeholder&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Default Select
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-5" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlInputText5&quot; class=&quot;form-label&quot;&gt;Default Select&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;select class=&quot;form-select&quot; id=&quot;exampleFormControlInputText5&quot; aria-label=&quot;Default select example&quot;&gt;            &lt;option selected&gt;Open this select menu&lt;/option&gt;            &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;            &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;            &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;        &lt;/select&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Default file input example
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-6" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlInputText6&quot; class=&quot;form-label&quot;&gt;Default file input example&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input class=&quot;form-control&quot; type=&quot;file&quot; id=&quot;exampleFormControlInputText6&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Multiple Select
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlInputText7&quot; class=&quot;form-label&quot;&gt;Multiple Select&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;select class=&quot;form-select&quot; id=&quot;exampleFormControlInputText7&quot; multiple aria-label=&quot;multiple select example&quot;&gt;            &lt;option selected&gt;Open this select menu&lt;/option&gt;            &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;            &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;            &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;            &lt;/select&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Example textarea
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-8" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-8">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlTextarea8&quot; class=&quot;form-label&quot;&gt;Example textarea&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;textarea placeholder=&quot;Textarea Placeholder&quot; class=&quot;form-control&quot; id=&quot;exampleFormControlTextarea8&quot; rows=&quot;3&quot;&gt;&lt;/textarea&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
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
                                        Set heights using classes like <code>.form-{control|select}-lg</code> and <code>.form-{control|select}-sm</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg" aria-label=".form-control-lg example">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <input class="form-control" type="text" placeholder="Default input" aria-label="default input example">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm" aria-label=".form-control-sm example">
                                            </div>
                                        </div>
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
                                            form control
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-9" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-9">&lt;div class=&quot;form-control-wrap&quot;&gt;    &lt;input class=&quot;form-control&quot; type=&quot;text&quot; placeholder=&quot;.form-control&quot; aria-label=&quot;.form-control example&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            form select
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-10" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-10">&lt;div class=&quot;form-control-wrap&quot;&gt;    &lt;select class=&quot;form-select&quot;        &lt;option selected&gt;Open this select menu&lt;/option&gt;        &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;        &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;        &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;    &lt;/select&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Readonly and Disabled
                                    </h3>
                                    <p>
                                        Add the <code>disabled</code> ro <code>readonly</code> boolean attribute on an input to give it a grayed out appearance and remove pointer events.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="exampleFormControlInput9" class="form-label">Readonly</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="text" value="Readonly input here..." aria-label="readonly input example" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="exampleFormControlInput10" class="form-label">Disabled</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="text" placeholder="Disabled input" aria-label="Disabled input example" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="exampleFormControlInput11" class="form-label">Disabled Readonly</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="text" value="Disabled readonly input" aria-label="Disabled input example" disabled readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-4">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput12" class="form-label">Readonly plain text</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="email@example.com">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use the <code>.form-control-plaintext</code> class to remove the default form field styling and preserve the correct margin and padding.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            Readonly
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-11" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-11">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;exampleFormControlInput9&quot; class=&quot;form-label&quot;&gt;Readonly&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input class=&quot;form-control&quot; type=&quot;text&quot; value=&quot;Readonly input here...&quot; aria-label=&quot;readonly input example&quot; readonly&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
