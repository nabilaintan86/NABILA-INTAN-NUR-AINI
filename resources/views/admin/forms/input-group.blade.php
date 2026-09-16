@extends('layouts.admin')
@section('title', 'Input group - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Input group
                                </h1>
                                <p>
                                    Easily extend form controls by adding text, buttons, or button groups on either side of textual inputs, custom selects, and custom file inputs.
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
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">@</span><input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2"><span class="input-group-text" id="basic-addon2">@example.com</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="basic-url" class="form-label">Your vanity URL</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon3">https://example.com/users/</span><input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text">$</span><input type="text" class="form-control" aria-label="Amount (to the nearest dollar)"><span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Username" aria-label="Username"><span class="input-group-text">@</span><input type="text" class="form-control" placeholder="Server" aria-label="Server">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text">With textarea</span><textarea class="form-control" aria-label="With textarea"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            example
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;input-group&quot;&gt;  &lt;span class=&quot;input-group-text&quot; id=&quot;basic-addon1&quot;&gt;@&lt;/span&gt;  &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Username&quot; aria-label=&quot;Username&quot; aria-describedby=&quot;basic-addon1&quot;&gt;&lt;/div&gt;</code></pre>
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
                                        Add <code>.input-group-{sm,lg}</code> classes to the <code>.input-group</code> for small, large size.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-12">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">Small</span><input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Default</span><input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text" id="inputGroup-sizing-lg">Large</span><input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;input-group&quot;&gt;  &lt;span class=&quot;input-group-text&quot; id=&quot;inputGroup-sizing-default&quot;&gt;Default&lt;/span&gt;  &lt;input type=&quot;text&quot; class=&quot;form-control&quot; aria-label=&quot;Sizing example input&quot; aria-describedby=&quot;inputGroup-sizing-default&quot;&gt;&lt;/div&gt;</code></pre>
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
                                        Place any checkbox or radio option within an input group’s addon instead of text. We recommend adding <code>.mt-0</code> to the <code>.form-check-input</code> when there’s no visible text next to the input.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <input class="form-check-input mt-0" type="checkbox" value="" aria-label="Checkbox for following text input">
                                                </div>
                                                <input type="text" class="form-control" aria-label="Text input with checkbox">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <input class="form-check-input mt-0" type="radio" value="" aria-label="Radio button for following text input">
                                                </div>
                                                <input type="text" class="form-control" aria-label="Text input with radio button">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;input-group&quot;&gt;  &lt;div class=&quot;input-group-text&quot;&gt;    &lt;input class=&quot;form-check-input mt-0&quot; type=&quot;checkbox&quot; value=&quot;&quot; aria-label=&quot;Checkbox for following text input&quot;&gt;  &lt;/div&gt;  &lt;input type=&quot;text&quot; class=&quot;form-control&quot; aria-label=&quot;Text input with checkbox&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Multiple inputs
                                    </h3>
                                    <p>
                                        While multiple <code>input</code>s are supported visually, validation styles are only available for input groups with a single <code>input</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text">First and last name</span><input type="text" aria-label="First name" class="form-control"><input type="text" aria-label="Last name" class="form-control">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;input-group&quot;&gt;  &lt;span class=&quot;input-group-text&quot;&gt;First and last name&lt;/span&gt;  &lt;input type=&quot;text&quot; aria-label=&quot;First name&quot; class=&quot;form-control&quot;&gt;  &lt;input type=&quot;text&quot; aria-label=&quot;Last name&quot; class=&quot;form-control&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Multiple addons
                                    </h3>
                                    <p>
                                        Multiple add-ons are supported and can be mixed with checkbox and radio input versions.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text">$</span><span class="input-group-text">0.00</span><input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)"><span class="input-group-text">$</span><span class="input-group-text">0.00</span>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;input-group&quot;&gt;  &lt;span class=&quot;input-group-text&quot;&gt;$&lt;/span&gt;  &lt;span class=&quot;input-group-text&quot;&gt;0.00&lt;/span&gt;  &lt;input type=&quot;text&quot; class=&quot;form-control&quot; aria-label=&quot;Dollar amount (with dot and two decimal places)&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Button addons and dropdowns
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <button class="btn btn-outline-primary" type="button" id="button-addon1">Button</button><input type="text" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2"><button class="btn btn-outline-primary" type="button" id="button-addon2">Button</button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <button class="btn btn-outline-primary" type="button">Button</button><button class="btn btn-outline-primary" type="button">Button</button><input type="text" class="form-control" placeholder="" aria-label="Example text with two button addons">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username with two button addons"><button class="btn btn-outline-primary" type="button">Button</button><button class="btn btn-outline-primary" type="button">Button</button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Something else here</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Separated link</a>
                                                    </li>
                                                </ul>
                                                <input type="text" class="form-control" aria-label="Text input with dropdown button">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Text input with dropdown button"><button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Something else here</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Separated link</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Action before</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Another action before</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Something else here</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Separated link</a>
                                                    </li>
                                                </ul>
                                                <input type="text" class="form-control" aria-label="Text input with 2 dropdown buttons"><button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Something else here</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Separated link</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            button
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-6" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div class=&quot;input-group&quot;&gt;  &lt;button class=&quot;btn btn-outline-primary&quot; type=&quot;button&quot; id=&quot;button-addon1&quot;&gt;Button&lt;/button&gt;  &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;&quot; aria-label=&quot;Example text with button addon&quot; aria-describedby=&quot;button-addon1&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            dropdown
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div class=&quot;input-group&quot;&gt;  &lt;button class=&quot;btn btn-outline-primary dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;Dropdown&lt;/button&gt;  &lt;ul class=&quot;dropdown-menu&quot;&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;hr class=&quot;dropdown-divider&quot;&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;  &lt;/ul&gt;  &lt;input type=&quot;text&quot; class=&quot;form-control&quot; aria-label=&quot;Text input with dropdown button&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Segmented buttons
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-primary">Action</button><button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"><span class="visually-hidden">Toggle Dropdown</span></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Something else here</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Separated link</a>
                                                    </li>
                                                </ul>
                                                <input type="text" class="form-control" aria-label="Text input with segmented dropdown button">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Text input with segmented dropdown button"><button type="button" class="btn btn-outline-primary">Action</button><button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"><span class="visually-hidden">Toggle Dropdown</span></button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Something else here</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/forms/input-group') }}#">Separated link</a>
                                                    </li>
                                                </ul>
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
                                            <button data-clipboard-target="#code-example-8" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-8">&lt;div class=&quot;input-group&quot;&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary&quot;&gt;Action&lt;/button&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;    &lt;span class=&quot;visually-hidden&quot;&gt;Toggle Dropdown&lt;/span&gt;  &lt;/button&gt;  &lt;ul class=&quot;dropdown-menu&quot;&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;hr class=&quot;dropdown-divider&quot;&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;  &lt;/ul&gt;  &lt;input type=&quot;text&quot; class=&quot;form-control&quot; aria-label=&quot;Text input with segmented dropdown button&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Custom forms
                                    </h3>
                                    <p>
                                        Input groups include support for custom selects and custom file inputs. Browser default versions of these are not supported.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <label class="input-group-text" for="inputGroupSelect01">Options</label>
                                                <select class="form-select" id="inputGroupSelect01">
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
                                        <div class="col-12">
                                            <div class="input-group">
                                                <select class="form-select" id="inputGroupSelect02">
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
                                                <label class="input-group-text" for="inputGroupSelect02">Options</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <button class="btn btn-outline-primary" type="button">Button</button>
                                                <select class="form-select" id="inputGroupSelect03" aria-label="Example select with button addon">
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
                                        <div class="col-12">
                                            <div class="input-group">
                                                <select class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon">
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
                                                <button class="btn btn-outline-primary" type="button">Button</button>
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
                                            <button data-clipboard-target="#code-example-9" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-9">&lt;div class=&quot;input-group&quot;&gt;  &lt;label class=&quot;input-group-text&quot; for=&quot;inputGroupSelect01&quot;&gt;Options&lt;/label&gt;  &lt;select class=&quot;form-select&quot; id=&quot;inputGroupSelect01&quot;&gt;    &lt;option selected&gt;Choose...&lt;/option&gt;    &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;    &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;    &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;  &lt;/select&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            button
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-10" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-10">&lt;div class=&quot;input-group&quot;&gt;  &lt;button class=&quot;btn btn-outline-primary&quot; type=&quot;button&quot;&gt;Button&lt;/button&gt;  &lt;select class=&quot;form-select&quot; id=&quot;inputGroupSelect03&quot; aria-label=&quot;Example select with button addon&quot;&gt;    &lt;option selected&gt;Choose...&lt;/option&gt;    &lt;option value=&quot;1&quot;&gt;One&lt;/option&gt;    &lt;option value=&quot;2&quot;&gt;Two&lt;/option&gt;    &lt;option value=&quot;3&quot;&gt;Three&lt;/option&gt;  &lt;/select&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Custom file input
                                    </h3>
                                    <p>
                                        Input groups include support for custom selects and custom file inputs. Browser default versions of these are not supported.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <label class="input-group-text" for="inputGroupFile01">Upload</label><input type="file" class="form-control" id="inputGroupFile01">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="inputGroupFile02"><label class="input-group-text" for="inputGroupFile02">Upload</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <button class="btn btn-outline-primary" type="button" id="inputGroupFileAddon03">Button</button><input type="file" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload"><button class="btn btn-outline-primary" type="button" id="inputGroupFileAddon04">Button</button>
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
                                            <button data-clipboard-target="#code-example-11" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-11">&lt;div class=&quot;input-group&quot;&gt;  &lt;label class=&quot;input-group-text&quot; for=&quot;inputGroupFile01&quot;&gt;Upload&lt;/label&gt;  &lt;input type=&quot;file&quot; class=&quot;form-control&quot; id=&quot;inputGroupFile01&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
