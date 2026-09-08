@extends('layouts.admin')
@section('title', 'Layout - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Layout
                                </h1>
                                <p>
                                    Give your forms some structure—from inline to horizontal to custom grid implementations—with our form layout options.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Forms
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="formGroupExampleInput" class="form-label">Example label</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Example input placeholder">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="formGroupExampleInput2" class="form-label">Another label</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input placeholder">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;mb-3&quot;&gt;  &lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;formGroupExampleInput&quot; class=&quot;form-label&quot;&gt;Example label&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;      &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;formGroupExampleInput&quot; placeholder=&quot;Example input placeholder&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;&lt;div class=&quot;mb-3&quot;&gt;  &lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;formGroupExampleInput2&quot; class=&quot;form-label&quot;&gt;Another label&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;      &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;formGroupExampleInput2&quot; placeholder=&quot;Another input placeholder&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Form grid
                                    </h3>
                                    <p>
                                        More complex forms can be built using our grid classes.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-md-6">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="First name" aria-label="First name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="Last name" aria-label="Last name">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;row g-gs&quot;&gt;  &lt;div class=&quot;col-md-6&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;      &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;First name&quot; aria-label=&quot;First name&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-md-6&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;      &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Last name&quot; aria-label=&quot;Last name&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Gutters
                                    </h3>
                                    <p>
                                        <code>.g-*</code> classes can be used to control the horizontal gutter widths, for the following example we use a smaller gutter width.
                                        <br>
                                        <strong class="text-black">Note:</strong> <code>.g-*</code> classes only work with <code>.row</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="Custom column padding" aria-label="First name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="Custom column padding" aria-label="Last name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="Custom column padding" aria-label="First name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="Custom column padding" aria-label="Last name">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-5 mb-0">
                                        More complex layouts can also be created with the grid system.
                                    </p>
                                    <form class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputEmail4" class="form-label">Email</label>
                                                <div class="form-control-wrap">
                                                    <input type="email" class="form-control" id="inputEmail4">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputPassword4" class="form-label">Password</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" id="inputPassword4">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="inputAddress" class="form-label">Address</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="inputAddress2" class="form-label">Address 2</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputCity" class="form-label">City</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="inputCity">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="inputState" class="form-label">State</label>
                                                <div class="form-control-wrap">
                                                    <select id="inputState" class="form-select">
                                                        <option selected>
                                                            Choose...
                                                        </option>
                                                        <option>
                                                            ...
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputZip" class="form-label">Zip</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="inputZip">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gridCheck"><label class="form-check-label" for="gridCheck"> Check me out </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Sign in</button>
                                        </div>
                                    </form>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;row g-4&quot;&gt;  &lt;div class=&quot;col-md-6&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Custom column padding&quot; aria-label=&quot;First name&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-md-6&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Custom column padding&quot; aria-label=&quot;Last name&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-md-6&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Custom column padding&quot; aria-label=&quot;First name&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-md-6&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Custom column padding&quot; aria-label=&quot;Last name&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Horizontal form
                                    </h3>
                                    <p>
                                        Create horizontal forms with the grid by adding the <code>.row</code> class to form groups and using the <code>.col-*-*</code> classes to specify the width of your labels and controls. Be sure to add <code>.col-form-label</code> to your <code>label</code>s as well so they’re vertically centered with their associated form controls.
                                    </p>
                                    <p>
                                        At times, you maybe need to use margin or padding utilities to create that perfect alignment you need. For example, we’ve removed the <code>padding-top</code> on our stacked radio inputs label to better align the text baseline.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form>
                                        <div class="row mb-3">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <div class="form-control-wrap">
                                                    <input type="email" class="form-control" id="inputEmail3">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" id="inputPassword3">
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-2 pt-0">Radios</legend>
                                            <div class="col-sm-10">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked><label class="form-check-label" for="gridRadios1"> First radio </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2"><label class="form-check-label" for="gridRadios2"> Second radio </label>
                                                </div>
                                                <div class="form-check disabled">
                                                    <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="option3" disabled><label class="form-check-label" for="gridRadios3"> Third disabled radio </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="row mb-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="gridCheck1"><label class="form-check-label" for="gridCheck1"> Example checkbox </label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Sign in</button>
                                    </form>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;form&gt;    &lt;div class=&quot;row mb-3&quot;&gt;      &lt;label for=&quot;inputEmail3&quot; class=&quot;col-sm-2 col-form-label&quot;&gt;Email&lt;/label&gt;      &lt;div class=&quot;col-sm-10&quot;&gt;        &lt;div class=&quot;form-control-wrap&quot;&gt;          &lt;input type=&quot;email&quot; class=&quot;form-control&quot; id=&quot;inputEmail3&quot;&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;row mb-3&quot;&gt;      &lt;label for=&quot;inputPassword3&quot; class=&quot;col-sm-2 col-form-label&quot;&gt;Password&lt;/label&gt;      &lt;div class=&quot;col-sm-10&quot;&gt;        &lt;div class=&quot;form-control-wrap&quot;&gt;            &lt;input type=&quot;password&quot; class=&quot;form-control&quot; id=&quot;inputPassword3&quot;&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;fieldset class=&quot;row mb-3&quot;&gt;      &lt;legend class=&quot;col-form-label col-sm-2 pt-0&quot;&gt;Radios&lt;/legend&gt;      &lt;div class=&quot;col-sm-10&quot;&gt;        &lt;div class=&quot;form-check&quot;&gt;          &lt;input class=&quot;form-check-input&quot; type=&quot;radio&quot; name=&quot;gridRadios&quot; id=&quot;gridRadios1&quot; value=&quot;option1&quot; checked&gt;          &lt;label class=&quot;form-check-label&quot; for=&quot;gridRadios1&quot;&gt;            First radio          &lt;/label&gt;        &lt;/div&gt;        &lt;div class=&quot;form-check&quot;&gt;          &lt;input class=&quot;form-check-input&quot; type=&quot;radio&quot; name=&quot;gridRadios&quot; id=&quot;gridRadios2&quot; value=&quot;option2&quot;&gt;          &lt;label class=&quot;form-check-label&quot; for=&quot;gridRadios2&quot;&gt;            Second radio          &lt;/label&gt;        &lt;/div&gt;        &lt;div class=&quot;form-check disabled&quot;&gt;          &lt;input class=&quot;form-check-input&quot; type=&quot;radio&quot; name=&quot;gridRadios&quot; id=&quot;gridRadios3&quot; value=&quot;option3&quot; disabled&gt;          &lt;label class=&quot;form-check-label&quot; for=&quot;gridRadios3&quot;&gt;            Third disabled radio          &lt;/label&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/fieldset&gt;    &lt;div class=&quot;row mb-3&quot;&gt;      &lt;div class=&quot;col-sm-10 offset-sm-2&quot;&gt;        &lt;div class=&quot;form-check&quot;&gt;          &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; id=&quot;gridCheck1&quot;&gt;          &lt;label class=&quot;form-check-label&quot; for=&quot;gridCheck1&quot;&gt;            Example checkbox          &lt;/label&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;button type=&quot;submit&quot; class=&quot;btn btn-primary&quot;&gt;Sign in&lt;/button&gt;&lt;/form&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Horizontal form label sizing
                                    </h3>
                                    <p>
                                        Be sure to use <code>.col-form-label-sm</code> or <code> .col-form-label-lg</code> to your <code>label</label></code> or <code>legend</code> to correctly follow the size of <code>.form-control-lg</code> and <code>.form-control-sm</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Email</label>
                                        <div class="col-sm-10">
                                            <div class="form-control-wrap">
                                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" placeholder="col-form-label-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="colFormLabel" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <div class="form-control-wrap">
                                                <input type="email" class="form-control" id="colFormLabel" placeholder="col-form-label">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="colFormLabelLg" class="col-sm-2 col-form-label col-form-label-lg">Email</label>
                                        <div class="col-sm-10">
                                            <div class="form-control-wrap">
                                                <input type="email" class="form-control form-control-lg" id="colFormLabelLg" placeholder="col-form-label-lg">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;row mb-3&quot;&gt;  &lt;label for=&quot;colFormLabel&quot; class=&quot;col-sm-2 col-form-label&quot;&gt;Email&lt;/label&gt;  &lt;div class=&quot;col-sm-10&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;email&quot; class=&quot;form-control&quot; id=&quot;colFormLabel&quot; placeholder=&quot;col-form-label&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Column sizing
                                    </h3>
                                    <p>
                                        As shown in the previous examples, our grid system allows you to place any number of <code>.cols</code> within a <code>.row</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-7">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="City" aria-label="City">
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="State" aria-label="State">
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" placeholder="Zip" aria-label="Zip">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div class=&quot;row g-3&quot;&gt;  &lt;div class=&quot;col-sm-7&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;City&quot; aria-label=&quot;City&quot;&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-sm&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;State&quot; aria-label=&quot;State&quot;&gt;        &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-sm&quot;&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Zip&quot; aria-label=&quot;Zip&quot;&gt;     &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Auto-sizing
                                    </h3>
                                    <p>
                                        The example below uses a flexbox utility to vertically center the contents and changes <code>.col</code> to <code>.col-auto</code> so that your columns only take up as much space as needed. Put another way, the column sizes itself based on the contents.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form class="row gy-2 gx-3 align-items-center">
                                        <div class="col-auto">
                                            <div class="form-group">
                                                <label class="visually-hidden" for="autoSizingInput">Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="autoSizingInput" placeholder="Jane Doe">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="form-group">
                                                <label class="visually-hidden" for="autoSizingInputGroup">Username</label>
                                                <div class="form-control-wrap">
                                                    <div class="input-group">
                                                        <div class="input-group-text">
                                                            @
                                                        </div>
                                                        <input type="text" class="form-control" id="autoSizingInputGroup" placeholder="Username">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="form-group">
                                                <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="autoSizingSelect">
                                                        <option selected>
                                                            Choose...
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
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="autoSizingCheck"><label class="form-check-label" for="autoSizingCheck"> Remember me </label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;form class=&quot;row gy-2 gx-3 align-items-center&quot;&gt;  &lt;div class=&quot;col-auto&quot;&gt;    &lt;div class=&quot;form-group&quot;&gt;      &lt;label class=&quot;visually-hidden&quot; for=&quot;autoSizingInput&quot;&gt;Name&lt;/label&gt;      &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;autoSizingInput&quot; placeholder=&quot;Jane Doe&quot;&gt;      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-auto&quot;&gt;    &lt;div class=&quot;form-group&quot;&gt;      &lt;label class=&quot;visually-hidden&quot; for=&quot;autoSizingInputGroup&quot;&gt;Username&lt;/label&gt;      &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;input-group&quot;&gt;          &lt;div class=&quot;input-group-text&quot;&gt;@&lt;/div&gt;          &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;autoSizingInputGroup&quot; placeholder=&quot;Username&quot;&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-auto&quot;&gt;    &lt;div class=&quot;form-group&quot;&gt;      &lt;label class=&quot;visually-hidden&quot; for=&quot;autoSizingSelect&quot;&gt;Preference&lt;/label&gt;      &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;select class=&quot;form-select&quot; id=&quot;autoSizingSelect&quot;&gt;          &lt;option selected&gt;Choose...&lt;/option&gt;          &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;          &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;          &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;        &lt;/select&gt;      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-auto&quot;&gt;    &lt;div class=&quot;form-check&quot;&gt;      &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; id=&quot;autoSizingCheck&quot;&gt;      &lt;label class=&quot;form-check-label&quot; for=&quot;autoSizingCheck&quot;&gt;        Remember me      &lt;/label&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-auto&quot;&gt;    &lt;button type=&quot;submit&quot; class=&quot;btn btn-primary&quot;&gt;Submit&lt;/button&gt;  &lt;/div&gt;&lt;/form&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
