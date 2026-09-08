@extends('layouts.admin')
@section('title', 'Offcanvas - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Offcanvas
                                </h1>
                                <p>
                                    Build hidden sidebars into your project for navigation, shopping carts, and more with a few classes and our JavaScript plugin.
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
                                            <a class="btn btn-primary" data-bs-toggle="offcanvas" href="{{ url('/admin/components/offcanvas') }}#offcanvasExample"> Link with href </a>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"> Button with data-bs-target </button>
                                        </li>
                                    </ul>
                                    <div class="form-note mt-4">
                                        You can use a link with the <code>href</code> attribute, or a button with the <code>data-bs-target</code> attribute. In both cases, the <code>data-bs-toggle="offcanvas"</code> is required.
                                    </div>
                                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample">
                                        <div class="offcanvas-header">
                                            <h3 class="offcanvas-title" id="offcanvasExampleLabel">
                                                Offcanvas
                                            </h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <div>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </div>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"> Dropdown button </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/offcanvas') }}#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/offcanvas') }}#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('/admin/components/offcanvas') }}#">Something else here</a>
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
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;a class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;offcanvas&quot; href=&quot;#offcanvasExample&quot; role=&quot;button&quot; aria-controls=&quot;offcanvasExample&quot;&gt;  Link with href&lt;/a&gt;&lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#offcanvasExample&quot; aria-controls=&quot;offcanvasExample&quot;&gt;  Button with data-bs-target&lt;/button&gt;&lt;div class=&quot;offcanvas offcanvas-start&quot; tabindex=&quot;-1&quot; id=&quot;offcanvasExample&quot; aria-labelledby=&quot;offcanvasExampleLabel&quot;&gt;  &lt;div class=&quot;offcanvas-header&quot;&gt;    &lt;h5 class=&quot;offcanvas-title&quot; id=&quot;offcanvasExampleLabel&quot;&gt;Offcanvas&lt;/h5&gt;    &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;  &lt;/div&gt;  &lt;div class=&quot;offcanvas-body&quot;&gt;    &lt;div&gt;      Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.    &lt;/div&gt;    &lt;div class=&quot;dropdown mt-3&quot;&gt;      &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;        Dropdown button      &lt;/button&gt;      &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuButton&quot;&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;      &lt;/ul&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Body scrolling
                                    </h3>
                                    <p>
                                        Scrolling the <code>body</code> element is disabled when an offcanvas and its backdrop are visible. Use the <code>data-bs-scroll</code> attribute to enable <code>body</code> scrolling.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling">Enable body scrolling</button>
                                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling">
                                        <div class="offcanvas-header">
                                            <h3 class="offcanvas-title" id="offcanvasScrollingLabel">
                                                Offcanvas with body scrolling
                                            </h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Try scrolling the rest of the page to see this option in action.
                                            </p>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#offcanvasScrolling&quot;&gt;Enable body scrolling&lt;/button&gt;&lt;div class=&quot;offcanvas offcanvas-start&quot; data-bs-scroll=&quot;true&quot; data-bs-backdrop=&quot;false&quot; tabindex=&quot;-1&quot; id=&quot;offcanvasScrolling&quot;&gt;    &lt;div class=&quot;offcanvas-header&quot;&gt;        &lt;h3 class=&quot;offcanvas-title&quot; id=&quot;offcanvasScrollingLabel&quot;&gt;Offcanvas with body scrolling&lt;/h3&gt;        &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;    &lt;/div&gt;    &lt;div class=&quot;offcanvas-body&quot;&gt;        &lt;p&gt;Try scrolling the rest of the page to see this option in action.&lt;/p&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Body scrolling and backdrop
                                    </h3>
                                    <p>
                                        You can also enable <code>body</code> scrolling with a visible backdrop.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions">Enable both scrolling & backdrop</button>
                                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions">
                                        <div class="offcanvas-header">
                                            <h3 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">
                                                Backdrop with scrolling
                                            </h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Try scrolling the rest of the page to see this option in action.
                                            </p>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#offcanvasWithBothOptions&quot;&gt;Enable both scrolling &amp; backdrop&lt;/button&gt;&lt;div class=&quot;offcanvas offcanvas-start&quot; data-bs-scroll=&quot;true&quot; tabindex=&quot;-1&quot; id=&quot;offcanvasWithBothOptions&quot;&gt;    &lt;div class=&quot;offcanvas-header&quot;&gt;        &lt;h3 class=&quot;offcanvas-title&quot; id=&quot;offcanvasWithBothOptionsLabel&quot;&gt;Backdrop with scrolling&lt;/h3&gt;        &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;    &lt;/div&gt;    &lt;div class=&quot;offcanvas-body&quot;&gt;        &lt;p&gt;Try scrolling the rest of the page to see this option in action.&lt;/p&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Static backdrop
                                    </h3>
                                    <p>
                                        When backdrop is set to static, the offcanvas will not close when clicking outside of it.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop"> Toggle static offcanvas </button>
                                    <div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
                                        <div class="offcanvas-header">
                                            <h3 class="offcanvas-title" id="staticBackdropLabel">
                                                Offcanvas
                                            </h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <div>
                                                I will not close if you click outside of me.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#staticBackdrop&quot; aria-controls=&quot;staticBackdrop&quot;&gt;    Toggle static offcanvas&lt;/button&gt;&lt;div class=&quot;offcanvas offcanvas-start&quot; data-bs-backdrop=&quot;static&quot; tabindex=&quot;-1&quot; id=&quot;staticBackdrop&quot; aria-labelledby=&quot;staticBackdropLabel&quot;&gt;    &lt;div class=&quot;offcanvas-header&quot;&gt;        &lt;h3 class=&quot;offcanvas-title&quot; id=&quot;staticBackdropLabel&quot;&gt;Offcanvas&lt;/h3&gt;        &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;    &lt;/div&gt;    &lt;div class=&quot;offcanvas-body&quot;&gt;        &lt;div&gt;        I will not close if you click outside of me.        &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Placement
                                    </h3>
                                    <p>
                                        There’s no default placement for offcanvas components, so you must add one of the modifier classes below.
                                    </p>
                                    <ul class="small">
                                        <li>
                                            <code>.offcanvas-start</code> places offcanvas on the left of the viewport (shown above)
                                        </li>
                                        <li>
                                            <code>.offcanvas-end</code> places offcanvas on the right of the viewport
                                        </li>
                                        <li>
                                            <code>.offcanvas-top</code> places offcanvas on the top of the viewport
                                        </li>
                                        <li>
                                            <code>.offcanvas-bottom</code> places offcanvas on the bottom of the viewport
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop">Toggle top offcanvas</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">Toggle right offcanvas</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom">Toggle bottom offcanvas</button>
                                        </li>
                                    </ul>
                                    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop">
                                        <div class="offcanvas-header">
                                            <h3 class="offcanvas-title" id="offcanvasTopLabel">
                                                Offcanvas top
                                            </h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            ...
                                        </div>
                                    </div>
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight">
                                        <div class="offcanvas-header">
                                            <h3 class="offcanvas-title" id="offcanvasRightLabel">
                                                Offcanvas right
                                            </h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            ...
                                        </div>
                                    </div>
                                    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom">
                                        <div class="offcanvas-header">
                                            <h3 class="offcanvas-title" id="offcanvasBottomLabel">
                                                Offcanvas bottom
                                            </h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body small">
                                            ...
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;offcanvas offcanvas-top&quot; tabindex=&quot;-1&quot; id=&quot;offcanvasTop&quot;&gt;    &lt;div class=&quot;offcanvas-header&quot;&gt;        &lt;h3 class=&quot;offcanvas-title&quot; id=&quot;offcanvasTopLabel&quot;&gt;Offcanvas top&lt;/h3&gt;        &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;    &lt;/div&gt;    &lt;div class=&quot;offcanvas-body&quot;&gt;        ...    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
