@extends('layouts.admin')
@section('title', 'Toasts - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Toasts
                                </h1>
                                <p>
                                    Push notifications to your visitors with a toast, a lightweight and easily customizable alert message.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Live example
                                    </h3>
                                    <p>
                                        Click the button below to show a toast (positioned with our utilities in the lower right corner) that has been hidden by default.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="toast" data-bs-target="liveToast">Show live toast</button>
                                    <div class="toast-container position-fixed bottom-0 end-0 p-3">
                                        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="toast-header">
                                                <div class="media-group">
                                                    <div class="media media-xs">
                                                        <img src="{{ asset('admin-assets/images/favicon.png') }}" alt="logo-icon">
                                                    </div>
                                                    <div class="media-text">
                                                        <strong>RadneXt</strong>
                                                    </div>
                                                </div>
                                                <small>11 mins ago</small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                Hello, world! This is a toast message.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;toast&quot; data-bs-target=&quot;liveToast&quot; &gt;Show live toast&lt;/button&gt;&lt;div class=&quot;toast-container position-fixed bottom-0 end-0 p-3&quot;&gt;    &lt;div id=&quot;liveToast&quot; class=&quot;toast&quot; role=&quot;alert&quot; aria-live=&quot;assertive&quot; aria-atomic=&quot;true&quot;&gt;        &lt;div class=&quot;toast-header&quot;&gt;            &lt;div class=&quot;media-group&quot;&gt;                &lt;div class=&quot;media media-xs&quot;&gt;                    &lt;img src=&quot;/images/favicon.png&quot; alt=&quot;logo-icon&quot;&gt;                &lt;/div&gt;                &lt;div class=&quot;media-text&quot;&gt;                    &lt;strong&gt;RadneXt&lt;/strong&gt;                &lt;/div&gt;            &lt;/div&gt;            &lt;small&gt;11 mins ago&lt;/small&gt;            &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;toast&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;        &lt;/div&gt;        &lt;div class=&quot;toast-body&quot;&gt;            Hello, world! This is a toast message.        &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Translucent
                                    </h3>
                                    <p>
                                        Toasts are slightly translucent to blend in with what’s below them.
                                    </p>
                                </div>
                            </div>
                            <div class="card overflow-hidden">
                                <div class="card-body bg-dark">
                                    <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="toast-header">
                                            <div class="media-group">
                                                <div class="media media-xs">
                                                    <img src="{{ asset('admin-assets/images/favicon.png') }}" alt="logo-icon">
                                                </div>
                                                <div class="media-text">
                                                    <strong>RadneXt</strong>
                                                </div>
                                            </div>
                                            <small class="text-muted">11 mins ago</small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                        <div class="toast-body">
                                            Hello, world! This is a toast message.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;toast&quot; role=&quot;alert&quot; aria-live=&quot;assertive&quot; aria-atomic=&quot;true&quot;&gt;  &lt;div class=&quot;toast-header&quot;&gt;    &lt;div class=&quot;media-group&quot;&gt;        &lt;div class=&quot;media media-xs&quot;&gt;            &lt;img src=&quot;/images/favicon.png&quot; alt=&quot;logo-icon&quot;&gt;        &lt;/div&gt;        &lt;div class=&quot;media-text&quot;&gt;            &lt;strong&gt;RadneXt&lt;/strong&gt;        &lt;/div&gt;    &lt;/div&gt;    &lt;small class=&quot;text-muted&quot;&gt;11 mins ago&lt;/small&gt;    &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;toast&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;  &lt;/div&gt;  &lt;div class=&quot;toast-body&quot;&gt;    Hello, world! This is a toast message.  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Stacking
                                    </h3>
                                    <p>
                                        You can stack toasts by wrapping them in a toast container, which will vertically add some spacing.
                                    </p>
                                </div>
                            </div>
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="toast-container position-static">
                                        <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="toast-header">
                                                <div class="media-group">
                                                    <div class="media media-xs">
                                                        <img src="{{ asset('admin-assets/images/favicon.png') }}" alt="logo-icon">
                                                    </div>
                                                    <div class="media-text">
                                                        <strong>RadneXt</strong>
                                                    </div>
                                                </div>
                                                <small class="text-muted">just now</small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                See? Just like this.
                                            </div>
                                        </div>
                                        <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="toast-header">
                                                <div class="media-group">
                                                    <div class="media media-xs">
                                                        <img src="{{ asset('admin-assets/images/favicon.png') }}" alt="logo-icon">
                                                    </div>
                                                    <div class="media-text">
                                                        <strong>RadneXt</strong>
                                                    </div>
                                                </div>
                                                <small class="text-muted">2 seconds ago</small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                Heads up, toasts will stack automatically
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;toast-container position-static&quot;&gt;    &lt;div class=&quot;toast fade show&quot; role=&quot;alert&quot; aria-live=&quot;assertive&quot; aria-atomic=&quot;true&quot;&gt;        &lt;div class=&quot;toast-header&quot;&gt;            &lt;div class=&quot;media-group&quot;&gt;                &lt;div class=&quot;media media-xs&quot;&gt;                    &lt;img src=&quot;/images/favicon.png&quot; alt=&quot;logo-icon&quot;&gt;                &lt;/div&gt;                &lt;div class=&quot;media-text&quot;&gt;                    &lt;strong&gt;RadneXt&lt;/strong&gt;                &lt;/div&gt;            &lt;/div&gt;            &lt;small class=&quot;text-muted&quot;&gt;just now&lt;/small&gt;            &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;toast&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;        &lt;/div&gt;        &lt;div class=&quot;toast-body&quot;&gt;        See? Just like this.        &lt;/div&gt;    &lt;/div&gt;        &lt;div class=&quot;toast fade show&quot; role=&quot;alert&quot; aria-live=&quot;assertive&quot; aria-atomic=&quot;true&quot;&gt;        &lt;div class=&quot;toast-header&quot;&gt;            &lt;div class=&quot;media-group&quot;&gt;                &lt;div class=&quot;media media-xs&quot;&gt;                    &lt;img src=&quot;/images/favicon.png&quot; alt=&quot;logo-icon&quot;&gt;                &lt;/div&gt;                &lt;div class=&quot;media-text&quot;&gt;                    &lt;strong&gt;RadneXt&lt;/strong&gt;                &lt;/div&gt;            &lt;/div&gt;            &lt;small class=&quot;text-muted&quot;&gt;2 seconds ago&lt;/small&gt;            &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;toast&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;        &lt;/div&gt;        &lt;div class=&quot;toast-body&quot;&gt;        Heads up, toasts will stack automatically        &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Custom content
                                    </h3>
                                    <p>
                                        Customize your toasts by removing sub-components
                                    </p>
                                </div>
                            </div>
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="toast align-items-center fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="d-flex">
                                            <div class="toast-body">
                                                Hello, world! This is a toast message.
                                            </div>
                                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </div>
                                    <p class="mt-4">
                                        Alternatively, you can also add additional controls and components to toasts.
                                    </p>
                                    <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="toast-body">
                                            Hello, world! This is a toast message.
                                            <div class="d-flex gap-2 mt-2 pt-2 border-top">
                                                <button type="button" class="btn btn-primary btn-sm">Take action</button><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">Close</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;toast align-items-center fade show&quot; role=&quot;alert&quot; aria-live=&quot;assertive&quot; aria-atomic=&quot;true&quot;&gt;    &lt;div class=&quot;d-flex&quot;&gt;        &lt;div class=&quot;toast-body&quot;&gt;        Hello, world! This is a toast message.        &lt;/div&gt;        &lt;button type=&quot;button&quot; class=&quot;btn-close me-2 m-auto&quot; data-bs-dismiss=&quot;toast&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Color schemes
                                    </h3>
                                    <p>
                                        Building on the above example, you can create different toast color schemes with our color and background utilities. Here we’ve added <code>.text-bg-primary</code> to the <code>.toast</code>, and then added <code>.btn-close-white</code> to our close button. For a crisp edge, we remove the default border with <code>.border-0</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="toast align-items-center text-bg-primary border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="d-flex">
                                            <div class="toast-body">
                                                Hello, world! This is a toast message.
                                            </div>
                                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;toast align-items-center text-bg-primary border-0 fade show&quot; role=&quot;alert&quot; aria-live=&quot;assertive&quot; aria-atomic=&quot;true&quot;&gt;    &lt;div class=&quot;d-flex&quot;&gt;        &lt;div class=&quot;toast-body&quot;&gt;        Hello, world! This is a toast message.        &lt;/div&gt;        &lt;button type=&quot;button&quot; class=&quot;btn-close btn-close-white me-2 m-auto&quot; data-bs-dismiss=&quot;toast&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
