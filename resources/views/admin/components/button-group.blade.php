@extends('layouts.admin')
@section('title', 'Button group - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Button group
                                </h1>
                                <p>
                                    Group a series of buttons together on a single line or stack them in a vertical column.
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
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-primary">Left</button><button type="button" class="btn btn-primary">Middle</button><button type="button" class="btn btn-primary">Right</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;btn-group&quot; role=&quot;group&quot; aria-label=&quot;Basic example&quot;&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Left&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Middle&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Right&lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Mixed styles
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <button type="button" class="btn btn-danger">Left</button><button type="button" class="btn btn-warning">Middle</button><button type="button" class="btn btn-success">Right</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;btn-group&quot; role=&quot;group&quot; aria-label=&quot;Basic mixed styles example&quot;&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-danger&quot;&gt;Left&lt;/button&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot;&gt;Middle&lt;/button&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot;&gt;Right&lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Outlined styles
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                                        <button type="button" class="btn btn-outline-primary">Left</button><button type="button" class="btn btn-outline-primary">Middle</button><button type="button" class="btn btn-outline-primary">Right</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;btn-group&quot; role=&quot;group&quot; aria-label=&quot;Basic outlined example&quot;&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary&quot;&gt;Left&lt;/button&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary&quot;&gt;Middle&lt;/button&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary&quot;&gt;Right&lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Checkbox and radio button groups
                                    </h3>
                                    <p>
                                        Combine button-like checkbox and radio toggle buttons into a seamless looking button group.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                                                <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off"><label class="btn btn-outline-primary" for="btncheck1">Checkbox 1</label><input type="checkbox" class="btn-check" id="btncheck2" autocomplete="off"><label class="btn btn-outline-primary" for="btncheck2">Checkbox 2</label><input type="checkbox" class="btn-check" id="btncheck3" autocomplete="off"><label class="btn btn-outline-primary" for="btncheck3">Checkbox 3</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked><label class="btn btn-outline-primary" for="btnradio1">Radio 1</label><input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"><label class="btn btn-outline-primary" for="btnradio2">Radio 2</label><input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"><label class="btn btn-outline-primary" for="btnradio3">Radio 3</label>
                                            </div>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;btn-group&quot; role=&quot;group&quot; aria-label=&quot;Basic checkbox toggle button group&quot;&gt;  &lt;input type=&quot;checkbox&quot; class=&quot;btn-check&quot; id=&quot;btncheck1&quot; autocomplete=&quot;off&quot;&gt;  &lt;label class=&quot;btn btn-outline-primary&quot; for=&quot;btncheck1&quot;&gt;Checkbox 1&lt;/label&gt;  &lt;input type=&quot;checkbox&quot; class=&quot;btn-check&quot; id=&quot;btncheck2&quot; autocomplete=&quot;off&quot;&gt;  &lt;label class=&quot;btn btn-outline-primary&quot; for=&quot;btncheck2&quot;&gt;Checkbox 2&lt;/label&gt;  &lt;input type=&quot;checkbox&quot; class=&quot;btn-check&quot; id=&quot;btncheck3&quot; autocomplete=&quot;off&quot;&gt;  &lt;label class=&quot;btn btn-outline-primary&quot; for=&quot;btncheck3&quot;&gt;Checkbox 3&lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Button toolbar
                                    </h3>
                                    <p>
                                        Combine sets of button groups into button toolbars for more complex components. Use utility classes as needed to space out groups, buttons, and more.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group me-2 mb-2" role="group" aria-label="First group">
                                            <button type="button" class="btn btn-primary">1</button><button type="button" class="btn btn-primary">2</button><button type="button" class="btn btn-primary">3</button><button type="button" class="btn btn-primary">4</button>
                                        </div>
                                        <div class="btn-group me-2 mb-2" role="group" aria-label="Second group">
                                            <button type="button" class="btn btn-secondary">5</button><button type="button" class="btn btn-secondary">6</button><button type="button" class="btn btn-secondary">7</button>
                                        </div>
                                        <div class="btn-group mb-2" role="group" aria-label="Third group">
                                            <button type="button" class="btn btn-info">8</button>
                                        </div>
                                    </div>
                                    <p class="mt-4 small">
                                        Feel free to mix input groups with button groups in your toolbars. Similar to the example above, you’ll likely need some utilities though to space things properly.
                                    </p>
                                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group me-2 mb-3" role="group" aria-label="First group">
                                            <button type="button" class="btn btn-outline-secondary">1</button><button type="button" class="btn btn-outline-secondary">2</button><button type="button" class="btn btn-outline-secondary">3</button><button type="button" class="btn btn-outline-secondary">4</button>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-text" id="btnGroupAddon">
                                                @
                                            </div>
                                            <input type="text" class="form-control" placeholder="Input group example" aria-label="Input group example" aria-describedby="btnGroupAddon">
                                        </div>
                                    </div>
                                    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group me-2 mb-3" role="group" aria-label="First group">
                                            <button type="button" class="btn btn-outline-secondary">1</button><button type="button" class="btn btn-outline-secondary">2</button><button type="button" class="btn btn-outline-secondary">3</button><button type="button" class="btn btn-outline-secondary">4</button>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-text" id="btnGroupAddon2">
                                                @
                                            </div>
                                            <input type="text" class="form-control" placeholder="Input group example" aria-label="Input group example" aria-describedby="btnGroupAddon2">
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;btn-toolbar&quot; role=&quot;toolbar&quot; aria-label=&quot;Toolbar with button groups&quot;&gt;  &lt;div class=&quot;btn-group me-2&quot; role=&quot;group&quot; aria-label=&quot;First group&quot;&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;1&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;2&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;3&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;4&lt;/button&gt;  &lt;/div&gt;  &lt;div class=&quot;btn-group me-2&quot; role=&quot;group&quot; aria-label=&quot;Second group&quot;&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;5&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;6&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;7&lt;/button&gt;  &lt;/div&gt;  &lt;div class=&quot;btn-group&quot; role=&quot;group&quot; aria-label=&quot;Third group&quot;&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot;&gt;8&lt;/button&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
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
                                        Instead of applying button sizing classes to every button in a group, just add <code>.btn-group-*</code> to each <code>.btn-group</code>, including each one when nesting multiple groups.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul>
                                        <li class="block mb-3">
                                            <div class="btn-group btn-group-lg" role="group" aria-label="Large button group">
                                                <button type="button" class="btn btn-outline-secondary">Left</button><button type="button" class="btn btn-outline-secondary">Middle</button><button type="button" class="btn btn-outline-secondary">Right</button>
                                            </div>
                                        </li>
                                        <li class="block mb-3">
                                            <div class="btn-group" role="group" aria-label="Default button group">
                                                <button type="button" class="btn btn-outline-secondary">Left</button><button type="button" class="btn btn-outline-secondary">Middle</button><button type="button" class="btn btn-outline-secondary">Right</button>
                                            </div>
                                        </li>
                                        <li class="block">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                <button type="button" class="btn btn-outline-secondary">Left</button><button type="button" class="btn btn-outline-secondary">Middle</button><button type="button" class="btn btn-outline-secondary">Right</button>
                                            </div>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div class=&quot;btn-group btn-group-lg&quot; role=&quot;group&quot; aria-label=&quot;Large button group&quot;&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-secondary&quot;&gt;Left&lt;/button&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-secondary&quot;&gt;Middle&lt;/button&gt;  &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-secondary&quot;&gt;Right&lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Nesting
                                    </h3>
                                    <p>
                                        Place a <code>.btn-group</code> within another <code>.btn-group</code> when you want dropdown menus mixed with a series of buttons.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <button type="button" class="btn btn-primary">1</button><button type="button" class="btn btn-primary">2</button>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div class=&quot;btn-group&quot; role=&quot;group&quot; aria-label=&quot;Button group with nested dropdown&quot;&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;1&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;2&lt;/button&gt;      &lt;div class=&quot;btn-group&quot; role=&quot;group&quot;&gt;      &lt;button id=&quot;btnGroupDrop1&quot; type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        Dropdown      &lt;/button&gt;      &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;btnGroupDrop1&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Vertical variation
                                    </h3>
                                    <p>
                                        Make a set of buttons appear vertically stacked rather than horizontally. Split button dropdowns are not supported here.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-4">
                                        <li>
                                            <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                                                <button type="button" class="btn btn-primary">Button</button><button type="button" class="btn btn-primary">Button</button>
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                                    <ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <button type="button" class="btn btn-primary">Button</button><button type="button" class="btn btn-primary">Button</button>
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupVerticalDrop2" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                                    <ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupVerticalDrop3" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                                    <ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop3">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupVerticalDrop4" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                                    <ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop4">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/admin/components/button-group') }}#">Dropdown link</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                                                <button type="button" class="btn btn-dark">Button</button><button type="button" class="btn btn-dark">Button</button><button type="button" class="btn btn-dark">Button</button><button type="button" class="btn btn-dark">Button</button><button type="button" class="btn btn-dark">Button</button><button type="button" class="btn btn-dark">Button</button>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group-vertical" role="group" aria-label="Vertical radio toggle button group">
                                                <input type="radio" class="btn-check" name="vbtn-radio" id="vbtn-radio1" autocomplete="off" checked=""><label class="btn btn-outline-danger" for="vbtn-radio1">Radio 1</label><input type="radio" class="btn-check" name="vbtn-radio" id="vbtn-radio2" autocomplete="off"><label class="btn btn-outline-danger" for="vbtn-radio2">Radio 2</label><input type="radio" class="btn-check" name="vbtn-radio" id="vbtn-radio3" autocomplete="off"><label class="btn btn-outline-danger" for="vbtn-radio3">Radio 3</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="form-note mt-4">
                                        Use <code>.btn-group-vertical</code> class to make a set of buttons appear vertically stacked.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-8">&lt;div class=&quot;btn-group-vertical&quot; role=&quot;group&quot; aria-label=&quot;Vertical button group&quot;&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Button&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Button&lt;/button&gt;    &lt;div class=&quot;btn-group&quot; role=&quot;group&quot;&gt;      &lt;button id=&quot;btnGroupVerticalDrop1&quot; type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        Dropdown      &lt;/button&gt;      &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;btnGroupVerticalDrop1&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;    &lt;/div&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Button&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Button&lt;/button&gt;    &lt;div class=&quot;btn-group&quot; role=&quot;group&quot;&gt;      &lt;button id=&quot;btnGroupVerticalDrop2&quot; type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        Dropdown      &lt;/button&gt;      &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;btnGroupVerticalDrop2&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;    &lt;/div&gt;    &lt;div class=&quot;btn-group&quot; role=&quot;group&quot;&gt;      &lt;button id=&quot;btnGroupVerticalDrop3&quot; type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        Dropdown      &lt;/button&gt;      &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;btnGroupVerticalDrop3&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;    &lt;/div&gt;    &lt;div class=&quot;btn-group&quot; role=&quot;group&quot;&gt;      &lt;button id=&quot;btnGroupVerticalDrop4&quot; type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        Dropdown      &lt;/button&gt;      &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;btnGroupVerticalDrop4&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
