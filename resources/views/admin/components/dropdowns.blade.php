@extends('layouts.admin')
@section('title', 'Dropdowns - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Dropdowns
                                </h1>
                                <p>
                                    Toggle contextual overlays for displaying lists of links and more with the Bootstrap dropdown plugin.
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
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown button </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown">
                                                <a class="btn btn-secondary dropdown-toggle" href="{{ url('/admin/components/dropdowns') }}#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown link </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                    <p class="mt-4">
                                        The best part is you can do this with any button variant, too:
                                    </p>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Action </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Separated link</a>
                                            </li>
                                        </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;dropdown&quot;&gt;  &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuButton1&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;    Dropdown button  &lt;/button&gt;  &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuButton1&quot;&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;  &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Split button
                                    </h3>
                                    <p>
                                        Similarly, create split button dropdowns with virtually the same markup as single button dropdowns, but with the addition of <code>.dropdown-toggle-split</code> for proper spacing around the dropdown caret.
                                    </p>
                                    <p>
                                        We use this extra class to reduce the horizontal padding on either side of the caret by 25% and remove the margin-left that’s added for regular button dropdowns. Those extra changes keep the caret centered in the split button and provide a more appropriately sized hit area next to the main button.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger">Action</button><button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"><span class="visually-hidden">Toggle Dropdown</span></button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Separated link</a>
                                            </li>
                                        </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;btn-group&quot;&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-danger&quot;&gt;Action&lt;/button&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-danger dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;    &lt;span class=&quot;visually-hidden&quot;&gt;Toggle Dropdown&lt;/span&gt;    &lt;/button&gt;    &lt;ul class=&quot;dropdown-menu&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;hr class=&quot;dropdown-divider&quot;&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;    &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dark dropdowns
                                    </h3>
                                    <p>
                                        Opt into darker dropdowns to match a dark navbar or custom style by adding <code>.dropdown-menu-dark</code> onto an existing <code>.dropdown-menu</code>. No changes are required to the dropdown items.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown button </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <a class="dropdown-item active" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="pt-4">
                                        And putting it to use in a navbar:
                                    </p>
                                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                                        <div class="container-fluid">
                                            <a class="navbar-brand" href="{{ url('/admin/components/dropdowns') }}#">Navbar</a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                                            <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                                                <ul class="navbar-nav">
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" href="{{ url('/admin/components/dropdowns') }}#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </a>
                                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </nav>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;dropdown&quot;&gt;  &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuButton2&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;    Dropdown button  &lt;/button&gt;  &lt;ul class=&quot;dropdown-menu dropdown-menu-dark&quot; aria-labelledby=&quot;dropdownMenuButton2&quot;&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item active&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;hr class=&quot;dropdown-divider&quot;&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;  &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Directions
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="dropdown-center">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownCenterBtn" data-bs-toggle="dropdown" aria-expanded="false"> Centered dropdown </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownCenterBtn">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action two</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action three</a>
                                            </li>
                                        </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;dropdown-center&quot;&gt;    &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownCenterBtn&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;      Centered dropdown    &lt;/button&gt;    &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownCenterBtn&quot;&gt;      &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;      &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action two&lt;/a&gt;&lt;/li&gt;      &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action three&lt;/a&gt;&lt;/li&gt;    &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dropup
                                    </h3>
                                    <p>
                                        Trigger dropdown menus above elements by adding <code>.dropup</code> to the parent element.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropup </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action two</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action three</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-secondary"> Split dropup </button><button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"><span class="visually-hidden">Toggle Dropdown</span></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action two</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action three</a>
                                                    </li>
                                                </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;ul class=&quot;d-flex gap g-3&quot;&gt;  &lt;li&gt;      &lt;!-- Default dropup button --&gt;      &lt;div class=&quot;btn-group dropup&quot;&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        Dropup        &lt;/button&gt;        &lt;ul class=&quot;dropdown-menu&quot;&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action two&lt;/a&gt;&lt;/li&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action three&lt;/a&gt;&lt;/li&gt;        &lt;/ul&gt;    &lt;/div&gt;  &lt;/li&gt;  &lt;li&gt;    &lt;!-- Split dropup button --&gt;      &lt;div class=&quot;btn-group dropup&quot;&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;        Split dropup        &lt;/button&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        &lt;span class=&quot;visually-hidden&quot;&gt;Toggle Dropdown&lt;/span&gt;        &lt;/button&gt;        &lt;ul class=&quot;dropdown-menu&quot;&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action two&lt;/a&gt;&lt;/li&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action three&lt;/a&gt;&lt;/li&gt;        &lt;/ul&gt;    &lt;/div&gt;  &lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dropup centered
                                    </h3>
                                    <p>
                                        Make the dropup menu centered above the toggle with <code>.dropup-center</code> on the parent element.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="dropup-center dropup">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropupCenterBtn" data-bs-toggle="dropdown" aria-expanded="false"> Centered dropup </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropupCenterBtn">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action two</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action three</a>
                                            </li>
                                        </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div class=&quot;dropup-center dropup&quot;&gt;    &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropupCenterBtn&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;      Centered dropup    &lt;/button&gt;    &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropupCenterBtn&quot;&gt;      &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;      &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action two&lt;/a&gt;&lt;/li&gt;      &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action three&lt;/a&gt;&lt;/li&gt;    &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dropend
                                    </h3>
                                    <p>
                                        Trigger dropdown menus at the right of the elements by adding <code>.dropend</code> to the parent element.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex gap g-3 flex-wrap">
                                        <li>
                                            <div class="btn-group dropend">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropend </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action two</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action three</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group dropend">
                                                <button type="button" class="btn btn-secondary"> Split dropend </button><button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"><span class="visually-hidden">Toggle Dropend</span></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action two</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action three</a>
                                                    </li>
                                                </ul>
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
                                            <button data-clipboard-target="#code-example-8" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-8">&lt;ul class=&quot;d-flex gap g-3&quot;&gt;  &lt;li&gt;      &lt;!-- Default dropend button --&gt;      &lt;div class=&quot;btn-group dropend&quot;&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        Dropend        &lt;/button&gt;        &lt;ul class=&quot;dropdown-menu&quot;&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action two&lt;/a&gt;&lt;/li&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action three&lt;/a&gt;&lt;/li&gt;        &lt;/ul&gt;      &lt;/div&gt;  &lt;/li&gt;  &lt;li&gt;      &lt;!-- Split dropend button --&gt;      &lt;div class=&quot;btn-group dropend&quot;&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;        Split dropend        &lt;/button&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;        &lt;span class=&quot;visually-hidden&quot;&gt;Toggle Dropend&lt;/span&gt;        &lt;/button&gt;        &lt;ul class=&quot;dropdown-menu&quot;&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action two&lt;/a&gt;&lt;/li&gt;            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action three&lt;/a&gt;&lt;/li&gt;        &lt;/ul&gt;    &lt;/div&gt;  &lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dropstart
                                    </h3>
                                    <p>
                                        Trigger dropdown menus at the left of the elements by adding <code>.dropstart</code> to the parent element.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex gap g-3 flex-wrap">
                                        <li>
                                            <div class="btn-group dropstart">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropstart </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action two</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action three</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group dropstart">
                                                <button type="button" class="btn btn-secondary"> Split dropstart </button><button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"><span class="visually-hidden">Toggle Dropstart</span></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action two</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action three</a>
                                                    </li>
                                                </ul>
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
                                            <button data-clipboard-target="#code-example-9" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-9">&lt;ul class=&quot;d-flex gap g-3&quot;&gt;    &lt;li&gt;      &lt;!-- Default dropend button --&gt;        &lt;div class=&quot;btn-group dropstart&quot;&gt;          &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;          Dropstart          &lt;/button&gt;          &lt;ul class=&quot;dropdown-menu&quot;&gt;              &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;              &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action two&lt;/a&gt;&lt;/li&gt;              &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action three&lt;/a&gt;&lt;/li&gt;          &lt;/ul&gt;      &lt;/div&gt;    &lt;/li&gt;    &lt;li&gt;      &lt;!-- Split dropstart button --&gt;          &lt;div class=&quot;btn-group dropstart&quot;&gt;            &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;            Split dropstart            &lt;/button&gt;            &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;            &lt;span class=&quot;visually-hidden&quot;&gt;Toggle Dropstart&lt;/span&gt;            &lt;/button&gt;            &lt;ul class=&quot;dropdown-menu&quot;&gt;                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action two&lt;/a&gt;&lt;/li&gt;                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action three&lt;/a&gt;&lt;/li&gt;            &lt;/ul&gt;        &lt;/div&gt;    &lt;/li&gt;  &lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Active
                                    </h3>
                                    <p>
                                        Add <code>.active</code> to items in the dropdown to style them as active.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <li>
                                                <button class="dropdown-item" type="button">Action</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item active" href="#" aria-current="true">Another action</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" type="button">Something else here</button>
                                            </li>
                                        </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-10">&lt;div class=&quot;dropdown&quot;&gt;    &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenu2&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;      Dropdown    &lt;/button&gt;    &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenu2&quot;&gt;      &lt;li&gt;&lt;button class=&quot;dropdown-item&quot; type=&quot;button&quot;&gt;Action&lt;/button&gt;&lt;/li&gt;      &lt;li&gt;&lt;button class=&quot;dropdown-item active&quot; href=&quot;#&quot; aria-current=&quot;true&quot;&gt;Another action&lt;/button&gt;&lt;/li&gt;      &lt;li&gt;&lt;button class=&quot;dropdown-item&quot; type=&quot;button&quot;&gt;Something else here&lt;/button&gt;&lt;/li&gt;    &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Menu alignment
                                    </h3>
                                    <p>
                                        Add <code>.dropdown-menu-end</code> to a <code>.dropdown-menu</code> to right align the dropdown menu.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Right-aligned menu example </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item" type="button">Action</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" type="button">Another action</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" type="button">Something else here</button>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mt-4 small">
                                        To align left the dropdown menu with the given breakpoint or larger, add <code>.dropdown-menu-end</code> and <code>.dropdown-menu{-sm|-md|-lg|-xl|-xxl}-start</code>.
                                    </p>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"> Dropdown </button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                            <li>
                                                <button class="dropdown-item" type="button">Action</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" type="button">Another action</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" type="button">Something else here</button>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="small mt-2">
                                        Right-aligned but left aligned when large screen
                                    </p>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-11">&lt;div class=&quot;btn-group&quot;&gt;    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;      Right-aligned menu example    &lt;/button&gt;    &lt;ul class=&quot;dropdown-menu dropdown-menu-end&quot;&gt;      &lt;li&gt;&lt;button class=&quot;dropdown-item&quot; type=&quot;button&quot;&gt;Action&lt;/button&gt;&lt;/li&gt;      &lt;li&gt;&lt;button class=&quot;dropdown-item&quot; type=&quot;button&quot;&gt;Another action&lt;/button&gt;&lt;/li&gt;      &lt;li&gt;&lt;button class=&quot;dropdown-item&quot; type=&quot;button&quot;&gt;Something else here&lt;/button&gt;&lt;/li&gt;    &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Alignment options
                                    </h3>
                                    <p>
                                        Taking most of the options shown above, here’s a small kitchen sink demo of various dropdown alignment options in one place.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Right-aligned menu </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"> Left-aligned, right-aligned lg </button>
                                                <ul class="dropdown-menu dropdown-menu-lg-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"> Right-aligned, left-aligned lg </button>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group dropstart">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropstart </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group dropend">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropend </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropup </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
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
                                            <button data-clipboard-target="#code-example-12" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-12">&lt;div class=&quot;btn-group&quot;&gt;  &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;    Dropdown  &lt;/button&gt;  &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuButton&quot;&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;    &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;  &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Menu content
                                    </h3>
                                    <p>
                                        Add a header to label sections of actions in any dropdown menu.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-gs">
                                        <div class="col-md-3">
                                            <h6 class="mb-3">
                                                Headers
                                            </h6>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <h6 class="dropdown-header fw-semibold">
                                                            Dropdown header
                                                        </h6>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="mb-3">
                                                Dividers
                                            </h6>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Separated link</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="mb-3">
                                                Text
                                            </h6>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </button>
                                                <div class="dropdown-menu p-4 text-muted dropdown-menu-md">
                                                    <p>
                                                        Some example text that's free-flowing within the dropdown menu.
                                                    </p>
                                                    <p class="mb-0">
                                                        And this is more example text.
                                                    </p>
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
                                            <button data-clipboard-target="#code-example-13" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-13">&lt;div class=&quot;btn-group&quot;&gt;    &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;      Dropdown    &lt;/button&gt;    &lt;ul class=&quot;dropdown-menu&quot;&gt;        &lt;li&gt;&lt;h6 class=&quot;dropdown-header fw-semibold&quot;&gt;Dropdown header&lt;/h6&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Forms
                                    </h3>
                                    <p>
                                        Put a form within a dropdown menu, or make it into a dropdown menu, and use margin or padding utilities to give it the negative space you require.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="dropdown-menu position-static d-block">
                                        <form class="px-4 py-3">
                                            <div class="mb-3">
                                                <label for="exampleDropdownFormEmail1" class="form-label">Email address</label><input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleDropdownFormPassword1" class="form-label">Password</label><input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check form-check-sm">
                                                    <input type="checkbox" class="form-check-input" id="dropdownCheck"><label class="form-check-label" for="dropdownCheck"> Remember me </label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Sign in</button>
                                        </form>
                                        <div class="dropdown-divider">
                                        </div>
                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">New around here? Sign up</a><a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Forgot password?</a>
                                    </div>
                                    <div class="dropdown mt-4">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"> Dropdown form </button>
                                        <div class="dropdown-menu dropdown-menu-md">
                                            <form class="px-4 py-3">
                                                <div class="mb-3">
                                                    <label for="exampleDropdownFormEmail1" class="form-label">Email address</label><input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleDropdownFormPassword1" class="form-label">Password</label><input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check form-check-sm">
                                                        <input type="checkbox" class="form-check-input" id="dropdownCheck"><label class="form-check-label" for="dropdownCheck"> Remember me </label>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Sign in</button>
                                            </form>
                                            <div class="dropdown-divider">
                                            </div>
                                            <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">New around here? Sign up</a><a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Forgot password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-14" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-14">&lt;div class=&quot;dropdown-menu position-static d-block&quot;&gt;    &lt;form class=&quot;px-4 py-3&quot;&gt;      &lt;div class=&quot;mb-3&quot;&gt;        &lt;label for=&quot;exampleDropdownFormEmail1&quot; class=&quot;form-label&quot;&gt;Email address&lt;/label&gt;        &lt;input type=&quot;email&quot; class=&quot;form-control&quot; id=&quot;exampleDropdownFormEmail1&quot; placeholder=&quot;email@example.com&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;mb-3&quot;&gt;        &lt;label for=&quot;exampleDropdownFormPassword1&quot; class=&quot;form-label&quot;&gt;Password&lt;/label&gt;        &lt;input type=&quot;password&quot; class=&quot;form-control&quot; id=&quot;exampleDropdownFormPassword1&quot; placeholder=&quot;Password&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;mb-3&quot;&gt;        &lt;div class=&quot;form-check form-check-sm&quot;&gt;          &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;dropdownCheck&quot;&gt;          &lt;label class=&quot;form-check-label&quot; for=&quot;dropdownCheck&quot;&gt;            Remember me          &lt;/label&gt;        &lt;/div&gt;      &lt;/div&gt;      &lt;button type=&quot;submit&quot; class=&quot;btn btn-primary&quot;&gt;Sign in&lt;/button&gt;    &lt;/form&gt;    &lt;div class=&quot;dropdown-divider&quot;&gt;&lt;/div&gt;    &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;New around here? Sign up&lt;/a&gt;    &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Forgot password?&lt;/a&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dropdown options
                                    </h3>
                                    <p>
                                        Use <code>data-bs-offset</code> or <code>data-bs-reference</code> to change the location of the dropdown.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownMenuOffset" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20"> Offset </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary">Reference</button><button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent"><span class="visually-hidden">Toggle Dropdown</span></button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Something else here</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Separated link</a>
                                                    </li>
                                                </ul>
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
                                            <button data-clipboard-target="#code-example-15" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-15">&lt;ul class=&quot;d-flex flex-wrap gap g-3&quot;&gt;  &lt;li&gt;    &lt;div class=&quot;dropdown&quot;&gt;      &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; id=&quot;dropdownMenuOffset&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot; data-bs-offset=&quot;10,20&quot;&gt;        Offset      &lt;/button&gt;      &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuOffset&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;    &lt;/div&gt;  &lt;/li&gt;  &lt;li&gt;    &lt;div class=&quot;btn-group&quot;&gt;      &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;Reference&lt;/button&gt;      &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle dropdown-toggle-split&quot; id=&quot;dropdownMenuReference&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot; data-bs-reference=&quot;parent&quot;&gt;        &lt;span class=&quot;visually-hidden&quot;&gt;Toggle Dropdown&lt;/span&gt;      &lt;/button&gt;      &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuReference&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;hr class=&quot;dropdown-divider&quot;&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;    &lt;/div&gt;  &lt;/li&gt;&lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Auto close behavior
                                    </h3>
                                    <p>
                                        By default, the dropdown menu is closed when clicking inside or outside the dropdown menu. You can use the <code>autoClose</code> option to change this behavior of the dropdown.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false"> Default dropdown </button>
                                                <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickableOutside" data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-expanded="false"> Clickable outside </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickableOutside">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickableInside" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"> Clickable inside </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickableInside">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false"> Manual close </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickable">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/dropdowns') }}#">Menu item</a>
                                                    </li>
                                                </ul>
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
                                            <button data-clipboard-target="#code-example-16" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-16">&lt;ul class=&quot;d-flex flex-wrap gap g-3&quot;&gt;    &lt;li&gt;      &lt;div class=&quot;btn-group&quot;&gt;        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;defaultDropdown&quot; data-bs-toggle=&quot;dropdown&quot; data-bs-auto-close=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;          Default dropdown        &lt;/button&gt;        &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;defaultDropdown&quot;&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;        &lt;/ul&gt;      &lt;/div&gt;    &lt;/li&gt;    &lt;li&gt;      &lt;div class=&quot;btn-group&quot;&gt;        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuClickableOutside&quot; data-bs-toggle=&quot;dropdown&quot; data-bs-auto-close=&quot;inside&quot; aria-expanded=&quot;false&quot;&gt;          Clickable outside        &lt;/button&gt;        &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuClickableOutside&quot;&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;        &lt;/ul&gt;      &lt;/div&gt;    &lt;/li&gt;    &lt;li&gt;      &lt;div class=&quot;btn-group&quot;&gt;        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuClickableInside&quot; data-bs-toggle=&quot;dropdown&quot; data-bs-auto-close=&quot;outside&quot; aria-expanded=&quot;false&quot;&gt;          Clickable inside        &lt;/button&gt;        &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuClickableInside&quot;&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;        &lt;/ul&gt;      &lt;/div&gt;    &lt;/li&gt;    &lt;li&gt;      &lt;div class=&quot;btn-group&quot;&gt;        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuClickable&quot; data-bs-toggle=&quot;dropdown&quot; data-bs-auto-close=&quot;false&quot; aria-expanded=&quot;false&quot;&gt;          Manual close        &lt;/button&gt;        &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuClickable&quot;&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;          &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;        &lt;/ul&gt;      &lt;/div&gt;    &lt;/li&gt;  &lt;/ul&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
