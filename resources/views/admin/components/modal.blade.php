@extends('layouts.admin')
@section('title', 'Modal - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Modal
                                </h1>
                                <p>
                                    Use Bootstrap’s JavaScript modal plugin to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Examples
                                    </h3>
                                    <p>
                                        Toggle a working modal demo by clicking the button below. It will slide down and fade in from the top of the page.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> Launch demo modal </button>
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Woo-hoo, you're reading this text in a modal!
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sm btn-primary">Save changes</button>
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
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;modal&quot; data-bs-target=&quot;#exampleModal&quot;&gt;    Launch demo modal&lt;/button&gt;&lt;div class=&quot;modal fade&quot; id=&quot;exampleModal&quot; tabindex=&quot;-1&quot; aria-labelledby=&quot;exampleModalLabel&quot; aria-hidden=&quot;true&quot;&gt;    &lt;div class=&quot;modal-dialog&quot;&gt;    &lt;div class=&quot;modal-content&quot;&gt;        &lt;div class=&quot;modal-header&quot;&gt;        &lt;h5 class=&quot;modal-title&quot; id=&quot;exampleModalLabel&quot;&gt;Modal title&lt;/h5&gt;        &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;modal&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;        &lt;/div&gt;        &lt;div class=&quot;modal-body&quot;&gt;            Woo-hoo, you're reading this text in a modal!        &lt;/div&gt;        &lt;div class=&quot;modal-footer&quot;&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-secondary&quot; data-bs-dismiss=&quot;modal&quot;&gt;Close&lt;/button&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-primary&quot;&gt;Save changes&lt;/button&gt;        &lt;/div&gt;    &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
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
                                        When backdrop is set to static, the modal will not close when clicking outside of it. Click the button below to try it.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Launch static backdrop modal </button>
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    I will not close if you click outside of me. Don't even try to press escape key.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sm btn-primary">Understood</button>
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
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;modal&quot; data-bs-target=&quot;#staticBackdrop&quot;&gt;    Launch static backdrop modal&lt;/button&gt;&lt;div class=&quot;modal fade&quot; id=&quot;staticBackdrop&quot; data-bs-backdrop=&quot;static&quot; data-bs-keyboard=&quot;false&quot; tabindex=&quot;-1&quot; aria-labelledby=&quot;staticBackdropLabel&quot; aria-hidden=&quot;true&quot;&gt;    &lt;div class=&quot;modal-dialog&quot;&gt;    &lt;div class=&quot;modal-content&quot;&gt;        &lt;div class=&quot;modal-header&quot;&gt;        &lt;h5 class=&quot;modal-title&quot; id=&quot;staticBackdropLabel&quot;&gt;Modal title&lt;/h5&gt;        &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;modal&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;        &lt;/div&gt;        &lt;div class=&quot;modal-body&quot;&gt;            I will not close if you click outside of me. Don't even try to press escape key.        &lt;/div&gt;        &lt;div class=&quot;modal-footer&quot;&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-secondary&quot; data-bs-dismiss=&quot;modal&quot;&gt;Close&lt;/button&gt;        &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-primary&quot;&gt;Understood&lt;/button&gt;        &lt;/div&gt;    &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Scrolling long content
                                    </h3>
                                    <p>
                                        When modals become too long for the user’s viewport or device, they scroll independent of the page itself. Try the demo below to see what we mean.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scrollable"> Launch static backdrop modal </button>
                                    <div class="modal fade" id="scrollable" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scrollableLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="scrollableLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal. This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sm btn-primary">Save changes</button>
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
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;modal&quot; data-bs-target=&quot;#scrollable&quot;&gt;    Launch static backdrop modal&lt;/button&gt;&lt;div class=&quot;modal fade&quot; id=&quot;scrollable&quot; data-bs-keyboard=&quot;false&quot; tabindex=&quot;-1&quot; aria-labelledby=&quot;scrollableLabel&quot; aria-hidden=&quot;true&quot;&gt;    &lt;div class=&quot;modal-dialog modal-dialog-scrollable&quot;&gt;        &lt;div class=&quot;modal-content&quot;&gt;            &lt;div class=&quot;modal-header&quot;&gt;            &lt;h5 class=&quot;modal-title&quot; id=&quot;scrollableLabel&quot;&gt;Modal title&lt;/h5&gt;            &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;modal&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;            &lt;/div&gt;            &lt;div class=&quot;modal-body&quot;&gt;                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.                This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the predefined max-height of modal, content will be cropped and scrollable within the modal.            &lt;/div&gt;            &lt;div class=&quot;modal-footer&quot;&gt;                &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-secondary&quot; data-bs-dismiss=&quot;modal&quot;&gt;Close&lt;/button&gt;                &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-primary&quot;&gt;Save changes&lt;/button&gt;            &lt;/div&gt;        &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Vertically centered
                                    </h3>
                                    <p>
                                        Add <code>.modal-dialog-top</code>, <code>.modal-dialog-bottom</code> to <code>.modal-dialog</code> to vertically center the modal.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalTop"> Vertically top </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalBottom"> Vertically bottom </button>
                                        </li>
                                    </ul>
                                    <div class="modal fade" id="exampleModalTop" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scrollableLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="scrollableLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    This is a vertically top modal.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sm btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalBottom" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scrollableLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-bottom">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="scrollableLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    This is a vertically bottom modal.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sm btn-primary">Save changes</button>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;ul class=&quot;d-flex flex-wrap gap g-3&quot;&gt;    &lt;li&gt;       &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;modal&quot; data-bs-target=&quot;#exampleModalTop&quot;&gt;        Vertically top      &lt;/button&gt;    &lt;/li&gt;    &lt;li&gt;       &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;modal&quot; data-bs-target=&quot;#exampleModalBottom&quot;&gt;        Vertically bottom      &lt;/button&gt;    &lt;/li&gt;  &lt;/ul&gt;  &lt;div class=&quot;modal fade&quot; id=&quot;exampleModalTop&quot; data-bs-keyboard=&quot;false&quot; tabindex=&quot;-1&quot; aria-labelledby=&quot;scrollableLabel&quot; aria-hidden=&quot;true&quot;&gt;      &lt;div class=&quot;modal-dialog modal-dialog-top&quot;&gt;          &lt;div class=&quot;modal-content&quot;&gt;              &lt;div class=&quot;modal-header&quot;&gt;              &lt;h5 class=&quot;modal-title&quot; id=&quot;scrollableLabel&quot;&gt;Modal title&lt;/h5&gt;              &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;modal&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;              &lt;/div&gt;              &lt;div class=&quot;modal-body&quot;&gt;                  This is a vertically top modal.              &lt;/div&gt;              &lt;div class=&quot;modal-footer&quot;&gt;                  &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-secondary&quot; data-bs-dismiss=&quot;modal&quot;&gt;Close&lt;/button&gt;                  &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-primary&quot;&gt;Save changes&lt;/button&gt;              &lt;/div&gt;          &lt;/div&gt;      &lt;/div&gt;  &lt;/div&gt;    &lt;div class=&quot;modal fade&quot; id=&quot;exampleModalBottom&quot; data-bs-keyboard=&quot;false&quot; tabindex=&quot;-1&quot; aria-labelledby=&quot;scrollableLabel&quot; aria-hidden=&quot;true&quot;&gt;      &lt;div class=&quot;modal-dialog modal-dialog-bottom&quot;&gt;          &lt;div class=&quot;modal-content&quot;&gt;              &lt;div class=&quot;modal-header&quot;&gt;              &lt;h5 class=&quot;modal-title&quot; id=&quot;scrollableLabel&quot;&gt;Modal title&lt;/h5&gt;              &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;modal&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;              &lt;/div&gt;              &lt;div class=&quot;modal-body&quot;&gt;                  This is a vertically bottom modal.              &lt;/div&gt;              &lt;div class=&quot;modal-footer&quot;&gt;                  &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-secondary&quot; data-bs-dismiss=&quot;modal&quot;&gt;Close&lt;/button&gt;                  &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-primary&quot;&gt;Save changes&lt;/button&gt;              &lt;/div&gt;          &lt;/div&gt;      &lt;/div&gt;  &lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Toggle between modals
                                    </h3>
                                    <p>
                                        Toggle between multiple modals with some clever placement of the <code>data-bs-target</code> and <code>data-bs-toggle</code> attributes. For example, you could toggle a password reset modal from within an already open sign in modal. Please note multiple modals cannot be open at the same time—this method simply toggles between two separate modals.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalToggle"> Open first modal </button>
                                    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalToggleLabel">
                                                        Modal 1
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Show a second modal and hide this one with the button below.
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Open second modal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalToggleLabel2">
                                                        Modal 2
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Hide this modal and show the first with the button below.
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Back to first</button>
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
                                            <button data-clipboard-target="#code-example-5" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;modal&quot; data-bs-target=&quot;#exampleModalToggle&quot;&gt;      Open first modal  &lt;/button&gt;  &lt;div class=&quot;modal fade&quot; id=&quot;exampleModalToggle&quot; aria-hidden=&quot;true&quot; aria-labelledby=&quot;exampleModalToggleLabel&quot; tabindex=&quot;-1&quot;&gt;      &lt;div class=&quot;modal-dialog modal-dialog-centered&quot;&gt;        &lt;div class=&quot;modal-content&quot;&gt;          &lt;div class=&quot;modal-header&quot;&gt;            &lt;h5 class=&quot;modal-title&quot; id=&quot;exampleModalToggleLabel&quot;&gt;Modal 1&lt;/h5&gt;            &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;modal&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;          &lt;/div&gt;          &lt;div class=&quot;modal-body&quot;&gt;            Show a second modal and hide this one with the button below.          &lt;/div&gt;          &lt;div class=&quot;modal-footer&quot;&gt;            &lt;button class=&quot;btn btn-primary&quot; data-bs-target=&quot;#exampleModalToggle2&quot; data-bs-toggle=&quot;modal&quot;&gt;Open second modal&lt;/button&gt;          &lt;/div&gt;        &lt;/div&gt;      &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;modal fade&quot; id=&quot;exampleModalToggle2&quot; aria-hidden=&quot;true&quot; aria-labelledby=&quot;exampleModalToggleLabel2&quot; tabindex=&quot;-1&quot;&gt;    &lt;div class=&quot;modal-dialog modal-dialog-centered&quot;&gt;      &lt;div class=&quot;modal-content&quot;&gt;        &lt;div class=&quot;modal-header&quot;&gt;          &lt;h5 class=&quot;modal-title&quot; id=&quot;exampleModalToggleLabel2&quot;&gt;Modal 2&lt;/h5&gt;          &lt;button type=&quot;button&quot; class=&quot;btn-close&quot; data-bs-dismiss=&quot;modal&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;        &lt;/div&gt;        &lt;div class=&quot;modal-body&quot;&gt;          Hide this modal and show the first with the button below.        &lt;/div&gt;        &lt;div class=&quot;modal-footer&quot;&gt;          &lt;button class=&quot;btn btn-primary&quot; data-bs-target=&quot;#exampleModalToggle&quot; data-bs-toggle=&quot;modal&quot;&gt;Back to first&lt;/button&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Optional sizes
                                    </h3>
                                    <p>
                                        Modals have three optional sizes, available via modifier classes to be placed on a <code>.modal-dialog</code>. These sizes kick in at certain breakpoints to avoid horizontal scrollbars on narrower viewports.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-5">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Size
                                                    </th>
                                                    <th>
                                                        Class
                                                    </th>
                                                    <th>
                                                        Modal max-width
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="small">
                                                        Small
                                                    </td>
                                                    <td>
                                                        <code>.modal-sm</code>
                                                    </td>
                                                    <td>
                                                        <code>300px</code>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="small">
                                                        Default
                                                    </td>
                                                    <td>
                                                        <span class="text-muted small">None</span>
                                                    </td>
                                                    <td>
                                                        <code>500px</code>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="small">
                                                        Large
                                                    </td>
                                                    <td>
                                                        <code>.modal-lg</code>
                                                    </td>
                                                    <td>
                                                        <code>800px</code>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="small">
                                                        Extra large
                                                    </td>
                                                    <td>
                                                        <code>.modal-xl</code>
                                                    </td>
                                                    <td>
                                                        <code>1140px</code>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalXl">Extra large modal</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLg">Large modal</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalSm">Small modal</button>
                                        </li>
                                    </ul>
                                    <div class="modal fade" id="exampleModalXl" aria-hidden="true" aria-labelledby="exampleModalXlLabel" tabindex="-1">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalXlLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi, rem modi earum provident eos corrupti.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalLg" aria-hidden="true" aria-labelledby="exampleModalLgLabel" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLgLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi, rem modi earum provident eos corrupti.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalSm" aria-hidden="true" aria-labelledby="exampleModalSmLabel" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalSmLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi
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
                                            <button data-clipboard-target="#code-example-6" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div class=&quot;modal-dialog modal-xl&quot;&gt;...&lt;/div&gt;&lt;div class=&quot;modal-dialog modal-lg&quot;&gt;...&lt;/div&gt;&lt;div class=&quot;modal-dialog modal-sm&quot;&gt;...&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Fullscreen Modal
                                    </h3>
                                    <p>
                                        Another override is the option to pop up a modal that covers the user viewport, available via modifier classes that are placed on a <code>.modal-dialog</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-5">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Class
                                                    </th>
                                                    <th>
                                                        Availability
                                                    </th>
                                                    <th>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <code>.modal-fullscreen</code>
                                                    </td>
                                                    <td>
                                                        Always
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>.modal-fullscreen-sm-down</code>
                                                    </td>
                                                    <td>
                                                        <code>576px</code>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>.modal-fullscreen-md-down</code>
                                                    </td>
                                                    <td>
                                                        <code>768px</code>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>.modal-fullscreen-lg-down</code>
                                                    </td>
                                                    <td>
                                                        <code>992px</code>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>.modal-fullscreen-xl-down</code>
                                                    </td>
                                                    <td>
                                                        <code>1200px</code>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <code>.modal-fullscreen-xxl-down</code>
                                                    </td>
                                                    <td>
                                                        <code>1400px</code>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <ul class="d-flex flex-wrap gap g-3">
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalFullscreen">Full screen</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalFullscreenSm">Full screen below sm</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalFullscreenMd">Full screen below md</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalFullscreenLg">Full screen below lg</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalFullscreenXl">Full screen below xl</button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalFullscreenXxl">Full screen below xxl</button>
                                        </li>
                                    </ul>
                                    <div class="modal fade" id="exampleModalFullscreen" aria-hidden="true" aria-labelledby="exampleModalFullscreenLabel" tabindex="-1">
                                        <div class="modal-dialog modal-fullscreen">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalFullscreenLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi, rem modi earum provident eos corrupti.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalFullscreenSm" aria-hidden="true" aria-labelledby="exampleModalFullscreenSmLabel" tabindex="-1">
                                        <div class="modal-dialog modal-fullscreen-sm-down">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalFullscreenSmLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi, rem modi earum provident eos corrupti.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalFullscreenMd" aria-hidden="true" aria-labelledby="exampleModalFullscreenMdLabel" tabindex="-1">
                                        <div class="modal-dialog modal-fullscreen-md-down">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalFullscreenMdLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalFullscreenLg" aria-hidden="true" aria-labelledby="exampleModalFullscreenLgLabel" tabindex="-1">
                                        <div class="modal-dialog modal-fullscreen-lg-down">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalFullscreenLgLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalFullscreenXl" aria-hidden="true" aria-labelledby="exampleModalFullscreenXlLabel" tabindex="-1">
                                        <div class="modal-dialog modal-fullscreen-xl-down">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalFullscreenXlLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModalFullscreenXxl" aria-hidden="true" aria-labelledby="exampleModalFullscreenXxlLabel" tabindex="-1">
                                        <div class="modal-dialog modal-fullscreen-xxl-down">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalFullscreenXxlLabel">
                                                        Modal title
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In id voluptatem fugiat autem sequi
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
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div class=&quot;modal-dialog modal-fullscreen-sm-down&quot;&gt;  ...&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
