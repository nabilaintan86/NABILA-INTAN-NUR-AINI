@extends('layouts.admin')
@section('title', 'Upload - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Form Upload
                                </h1>
                                <p>
                                    Give textual form controls like <code>input</code>s and <code>textarea</code>s an upgrade with custom styles, sizing, focus states, and more.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-4">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="dropzoneFile1" class="form-label">Default Dropzone</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-upload" id="dropzoneFile1">
                                                        <div class="dz-message" data-dz-message>
                                                            <div class="dz-message-icon">
                                                            </div>
                                                            <span class="dz-message-text">Drag and drop file</span>
                                                            <div class="dz-message-btn mt-2">
                                                                <button class="btn btn-md btn-primary">Upload</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="dropzoneFile2" class="form-label">Single File Dropzone</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-upload" id="dropzoneFile2" data-max-files="1">
                                                        <div class="dz-message" data-dz-message>
                                                            <div class="dz-message-icon">
                                                            </div>
                                                            <span class="dz-message-text">Drag and drop file</span>
                                                            <div class="dz-message-btn mt-2">
                                                                <button class="btn btn-md btn-primary">Upload</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="dropzoneFile3" class="form-label">Limited Size Dropzone (12mb)</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-upload" id="dropzoneFile3" data-max-filesize="12">
                                                        <div class="dz-message" data-dz-message>
                                                            <div class="dz-message-icon">
                                                            </div>
                                                            <span class="dz-message-text">Drag and drop file</span>
                                                            <div class="dz-message-btn mt-2">
                                                                <button class="btn btn-md btn-primary">Upload</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="dropzoneFile4" class="form-label">Change Icon and filetype</label>
                                                <div class="form-control-wrap">
                                                    <div class="js-upload" id="dropzoneFile4" data-accepted-files="image/*" data-message-icon="img">
                                                        <div class="dz-message" data-dz-message>
                                                            <div class="dz-message-icon">
                                                            </div>
                                                            <span class="dz-message-text">Drag and drop file</span>
                                                            <div class="dz-message-btn mt-2">
                                                                <button class="btn btn-md btn-primary">Upload</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-10">
                                            <div class="form-note">
                                                Use <code>data-max-files="1"</code> attribute to <code>.js-upload</code> to enable single file upload. same as use <code>data-max-filesize="12"</code> attribute for Limited file size, <code>data-accepted-files="image/*"</code> attribute for accepted filetype, <code>data-message-icon="img"</code> attribute for change icon.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;dropzoneFile1&quot; class=&quot;form-label&quot;&gt;Default Dropzone&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;div class=&quot;js-upload&quot; id=&quot;dropzoneFile1&quot;&gt;            &lt;div class=&quot;dz-message&quot; data-dz-message&gt;                &lt;div class=&quot;dz-message-icon&quot;&gt;&lt;/div&gt;                &lt;span class=&quot;dz-message-text&quot;&gt;Drag and drop file&lt;/span&gt;                &lt;div class=&quot;dz-message-btn mt-2&quot;&gt;                    &lt;button class=&quot;btn btn-md btn-primary&quot;&gt;Upload&lt;/button&gt;                &lt;/div&gt;            &lt;/div&gt;        &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Form File Input
                                    </h3>
                                    <p>
                                        Basic styled form file input.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gx-gs gy-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="formFile" class="form-label">Default file input example</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="file" id="formFile">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="formFileDisabled" class="form-label">Disabled file input example</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="file" id="formFileDisabled" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="formFileMultiple" class="form-label">Multiple files input example</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="file" id="formFileMultiple" multiple>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-note">
                                                Use <code>type="file"</code> attribute to <code>input</code> to enable file upload mode, same as <code>disabled</code> attribute to enable disabled mode, <code>multiple</code> attribute to enable multiple file upload mode.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            file input
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;form-group&quot;&gt;    &lt;label for=&quot;formFile&quot; class=&quot;form-label&quot;&gt;Default file input example&lt;/label&gt;    &lt;div class=&quot;form-control-wrap&quot;&gt;        &lt;input class=&quot;form-control&quot; type=&quot;file&quot; id=&quot;formFile&quot;&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
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
                                        Set heights using classes like <code>.form-control-lg</code> and <code>.form-control-sm</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <input class="form-control form-control-lg" id="formFileLg" type="file">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <input class="form-control" id="formFileRg" type="file">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-control-wrap">
                                                <input class="form-control form-control-sm" id="formFileSm" type="file">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            file input
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;form-control-wrap&quot;&gt;     &lt;input class=&quot;form-control&quot; id=&quot;formFileRg&quot; type=&quot;file&quot;&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
