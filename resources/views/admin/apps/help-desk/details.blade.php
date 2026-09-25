@extends('layouts.admin')
@section('title', 'Support Center - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Help Desk
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/apps/help-desk/details') }}#">Help Desk</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Support
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                        <li>
                                            <a href="{{ url('/admin/apps/help-desk/details') }}#" class="btn btn-primary btn-md d-md-none" data-bs-toggle="modal" data-bs-target="#createTicketModal"><em class="icon ni ni-plus"></em><span>Create</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/apps/help-desk/details') }}#" class="btn btn-primary d-none d-md-inline-flex" data-bs-toggle="modal" data-bs-target="#createTicketModal"><em class="icon ni ni-plus"></em><span>Create Ticket</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card nk-support-wrap overflow-hidden">
                                <div class="nk-support-header bg-lighter has-mask">
                                    <div class="mask mask-4">
                                    </div>
                                    <div class="nk-support-header-content text-center">
                                        <div class="h1 nk-support-title mb-3">
                                            How Can We Help You?
                                        </div>
                                        <p class="nk-support-desc">
                                            Welcome everyone! Search Articles, Public Tickets and watch Videos to find answers. Or, submit a Ticket if needed.
                                        </p>
                                        <div class="form-control-wrap">
                                            <div class="form-control-icon end px-0">
                                                <button type="button" class="btn"><em class="icon ni ni-search"></em></button>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Ask a question here...">
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-support-body">
                                    <div class="row g-gs">
                                        <div class="col-xl-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h3 class="mb-3">
                                                        Guide Navigation
                                                    </h3>
                                                    <ul class="nk-list-option-list nk-list-collapse">
                                                        <li>
                                                            <a class="justify-content-between fw-medium" data-bs-toggle="collapse" href="{{ url('/admin/apps/help-desk/details') }}#helpCollapse1" role="button" aria-expanded="true">
                                                            <div class="media-group">
                                                                <div class="media media-sm media-middle media-circle text-bg-primary-soft">
                                                                    <em class="icon ni ni-folder-list"></em>
                                                                </div>
                                                                <div class="media-text">
                                                                    <span class="title">Popular Tickets</span>
                                                                </div>
                                                            </div>
                                                            <span><em class="icon ni ni-chevron-down off"></em><em class="icon ni ni-chevron-up on"></em></span></a>
                                                            <div class="collapse show" id="helpCollapse1">
                                                                <div class="nk-support-side-content">
                                                                    <ul class="nk-list-option-list small">
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">How To Create A New Account?</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Before submitting the ticket</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Updating Your Theme</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">What admin theme does?</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">How to install on a local machine?</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <a class="justify-content-between fw-medium" data-bs-toggle="collapse" href="{{ url('/admin/apps/help-desk/details') }}#helpCollapse2" role="button" aria-expanded="false">
                                                            <div class="media-group">
                                                                <div class="media media-sm media-middle media-circle text-bg-primary-soft">
                                                                    <em class="icon ni ni-folder-list"></em>
                                                                </div>
                                                                <div class="media-text">
                                                                    <span class="title">Tutorials</span>
                                                                </div>
                                                            </div>
                                                            <span><em class="icon ni ni-chevron-down off"></em><em class="icon ni ni-chevron-up on"></em></span></a>
                                                            <div class="collapse" id="helpCollapse2">
                                                                <div class="nk-support-side-content">
                                                                    <ul class="nk-list-option-list small">
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">How To Create A New Account?</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Before submitting the ticket</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Updating Your Theme</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <a class="justify-content-between fw-medium" data-bs-toggle="collapse" href="{{ url('/admin/apps/help-desk/details') }}#helpCollapse3" role="button" aria-expanded="false">
                                                            <div class="media-group">
                                                                <div class="media media-sm media-middle media-circle text-bg-primary-soft">
                                                                    <em class="icon ni ni-folder-list"></em>
                                                                </div>
                                                                <div class="media-text">
                                                                    <span class="title">Account Settings</span>
                                                                </div>
                                                            </div>
                                                            <span><em class="icon ni ni-chevron-down off"></em><em class="icon ni ni-chevron-up on"></em></span></a>
                                                            <div class="collapse" id="helpCollapse3">
                                                                <div class="nk-support-side-content">
                                                                    <ul class="nk-list-option-list small">
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">How To Create A New Account?</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Before submitting the ticket</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Updating Your Theme</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <a class="justify-content-between fw-medium" data-bs-toggle="collapse" href="{{ url('/admin/apps/help-desk/details') }}#helpCollapse4" role="button" aria-expanded="false">
                                                            <div class="media-group">
                                                                <div class="media media-sm media-middle media-circle text-bg-primary-soft">
                                                                    <em class="icon ni ni-folder-list"></em>
                                                                </div>
                                                                <div class="media-text">
                                                                    <span class="title">Copyright & Legal</span>
                                                                </div>
                                                            </div>
                                                            <span><em class="icon ni ni-chevron-down off"></em><em class="icon ni ni-chevron-up on"></em></span></a>
                                                            <div class="collapse" id="helpCollapse4">
                                                                <div class="nk-support-side-content">
                                                                    <ul class="nk-list-option-list small">
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">How To Create A New Account?</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Before submitting the ticket</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Updating Your Theme</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <a class="justify-content-between fw-medium" data-bs-toggle="collapse" href="{{ url('/admin/apps/help-desk/details') }}#helpCollapse5" role="button" aria-expanded="false">
                                                            <div class="media-group">
                                                                <div class="media media-sm media-middle media-circle text-bg-primary-soft">
                                                                    <em class="icon ni ni-folder-list"></em>
                                                                </div>
                                                                <div class="media-text">
                                                                    <span class="title">FAQs</span>
                                                                </div>
                                                            </div>
                                                            <span><em class="icon ni ni-chevron-down off"></em><em class="icon ni ni-chevron-up on"></em></span></a>
                                                            <div class="collapse" id="helpCollapse5">
                                                                <div class="nk-support-side-content">
                                                                    <ul class="nk-list-option-list small">
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">How To Create A New Account?</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Before submitting the ticket</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Updating Your Theme</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <a class="justify-content-between fw-medium" data-bs-toggle="collapse" href="{{ url('/admin/apps/help-desk/details') }}#helpCollapse6" role="button" aria-expanded="false">
                                                            <div class="media-group">
                                                                <div class="media media-sm media-middle media-circle text-bg-primary-soft">
                                                                    <em class="icon ni ni-folder-list"></em>
                                                                </div>
                                                                <div class="media-text">
                                                                    <span class="title">Documentations</span>
                                                                </div>
                                                            </div>
                                                            <span><em class="icon ni ni-chevron-down off"></em><em class="icon ni ni-chevron-up on"></em></span></a>
                                                            <div class="collapse" id="helpCollapse6">
                                                                <div class="nk-support-side-content">
                                                                    <ul class="nk-list-option-list small">
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">How To Create A New Account?</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Before submitting the ticket</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/help-desk/details') }}#">Updating Your Theme</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="nk-block-head nk-block-head-sm">
                                                <div class="nk-block-head-content">
                                                    <h3 class="nk-block-title">
                                                        How To Create A New Account?
                                                    </h3>
                                                    <ul class="nk-list-meta">
                                                        <li>
                                                            Estimated reading: 1 minute
                                                        </li>
                                                        <li>
                                                            102 views
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="nk-entry">
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are anything embarrassing hidden in the middle of text.
                                                </p>
                                                <p>
                                                    Ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                                </p>
                                                <h3>
                                                    How to start
                                                </h3>
                                                <p>
                                                    If the theme is still not showing up, it’s possible the author has not made it available for auto install yet. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue.
                                                </p>
                                                <h3>
                                                    Check with screenshots here
                                                </h3>
                                                <img src="{{ asset('admin-assets/images/support/b.jpg') }}" alt="">
                                                <h3>
                                                    Check with some bullete points:
                                                </h3>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are anything embarrassing hidden in the middle of text.
                                                </p>
                                                <ul>
                                                    <li>
                                                        If the theme is still not showing up
                                                    </li>
                                                    <li>
                                                        Excepteur sint occaecat cupidatat non proident
                                                    </li>
                                                    <li>
                                                        We denounce with righteous indignation and dislike
                                                    </li>
                                                    <li>
                                                        It’s possible the author has not made it available for auto install yet
                                                    </li>
                                                    <li>
                                                        Excepteur sint occaecat cupidatat non proident
                                                    </li>
                                                </ul>
                                                <h3>
                                                    How to automatically generates HTML table by JavaScript?
                                                </h3>
                                                <p>
                                                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. I am using a basic grid layout:
                                                </p>
                                                <pre><code class="language-html">&lt;div class=&quot;accordion&quot; id=&quot;accordionExample&quot;&gt;    &lt;div class=&quot;accordion-item&quot;&gt;        &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseOne&quot;&gt;            Accordion Item #1        &lt;/button&gt;        &lt;/h2&gt;        &lt;div id=&quot;collapseOne&quot; class=&quot;accordion-collapse collapse show&quot; data-bs-parent=&quot;#accordionExample&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;            &lt;strong&gt;This is the first item's accordion body.&lt;/strong&gt; It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.        &lt;/div&gt;        &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;accordion-item&quot;&gt;        &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseTwo&quot;&gt;            Accordion Item #2        &lt;/button&gt;        &lt;/h2&gt;        &lt;div id=&quot;collapseTwo&quot; class=&quot;accordion-collapse collapse&quot; data-bs-parent=&quot;#accordionExample&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;            &lt;strong&gt;This is the second item's accordion body.&lt;/strong&gt; It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.        &lt;/div&gt;        &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                                <h3>
                                                    Theme Installation
                                                </h3>
                                                <p>
                                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.
                                                </p>
                                                <blockquote>
                                                    <p>
                                                        FTP: Extract rogan.zip file and upload the extracted folder to /wp-content/themes/ folder on your server.
                                                    </p>
                                                    <p>
                                                        WordPress: Navigate to Appearance -> Add New Themes -> Upload page. Select rogan.zip file. Press the Install Now button to upload and install the theme.
                                                    </p>
                                                </blockquote>
                                                <h3>
                                                    Check with some bullete points:
                                                </h3>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are anything embarrassing hidden in the middle of text.
                                                </p>
                                                <ol>
                                                    <li>
                                                        Set the menu to themes location at the ‘Appearance > Menu’ section.
                                                    </li>
                                                    <li>
                                                        Install it to different folder ( or just simply uploading via wordpress dashboard without removing the old
                                                    </li>
                                                    <li>
                                                        We denounce with righteous indignation and dislike
                                                    </li>
                                                    <li>
                                                        Save the theme option once
                                                    </li>
                                                    <li>
                                                        Make sure everything is working properly and you can remove the old theme files out
                                                    </li>
                                                </ol>
                                                <h3>
                                                    How long the license is valid?
                                                </h3>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are anything embarrassing hidden in the middle of text.
                                                </p>
                                                <p>
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor veritatis minus aliquid ratione explicabo itaque a eaque provident deserunt quod. If you are anything embarrassing hidden in the middle of text.
                                                </p>
                                                <h3>
                                                    Conclusion
                                                </h3>
                                                <blockquote>
                                                    <p>
                                                        “ Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean”
                                                    </p>
                                                </blockquote>
                                                <p>
                                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
