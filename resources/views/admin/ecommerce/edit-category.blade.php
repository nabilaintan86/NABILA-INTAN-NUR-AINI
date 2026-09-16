@extends('layouts.admin')
@section('title', 'Edit category - RadneXt Responsive Admin Dashboard Template')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/libs/editors/quill.css') }}">
    @endpush
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Edit Category
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/ecommerce/edit-category') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/ecommerce/edit-category') }}#">ecommerce</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Edit Category
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                        <li>
                                            <a href="{{ url('/admin/ecommerce/categories') }}" class="btn btn-primary btn-md d-md-none"><em class="icon ni ni-eye"></em><span>View</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/ecommerce/categories') }}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-eye"></em><span>View Category</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <form action="edit-category.html#">
                                <div class="row g-gs">
                                    <div class="col-xxl-9">
                                        <div class="gap gy-4">
                                            <div class="gap-col">
                                                <div class="card card-gutter-md">
                                                    <div class="card-body">
                                                        <div class="row g-gs">
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Category Name</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" placeholder="Category name" value="Household">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Parent Category</label>
                                                                    <div class="form-control-wrap">
                                                                        <div class="form-control-wrap">
                                                                            <select class="js-select" data-search="true" data-sort="false">
                                                                                <option value="">
                                                                                    Select an option
                                                                                </option>
                                                                                <option value="1" selected>
                                                                                    Toothbrush
                                                                                </option>
                                                                                <option value="2">
                                                                                    Cameras
                                                                                </option>
                                                                                <option value="3">
                                                                                    Speakers
                                                                                </option>
                                                                                <option value="4">
                                                                                    Watches
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Meta Tag Title</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" placeholder="Meta tag title" value="Simple meta tag">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Meta Tag Keywords</label>
                                                                    <div class="form-control-wrap">
                                                                        <div class="form-control-wrap">
                                                                            <input class="js-tags" value="new, trending" type="text" placeholder="Meta tags">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Description</label>
                                                                    <div class="form-control-wrap">
                                                                        <div class="js-quill" data-toolbar="minimal" data-placeholder="Write category description here...">
                                                                            This is one of the world’s leading designer lifestyle brands
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gap-col">
                                                <ul class="d-flex align-items-center gap g-3">
                                                    <li>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/admin/ecommerce/products') }}" class="btn border-0">Cancel</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3">
                                        <div class="card card-gutter-md">
                                            <div class="card-body">
                                                <div class="row g-gs">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Thumbnail</label>
                                                            <div class="form-control-wrap">
                                                                <div class="image-upload-wrap d-flex flex-column align-items-center">
                                                                    <div class="media media-huge border">
                                                                        <img id="image-result" class="w-100 h-100" src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="avatar">
                                                                    </div>
                                                                    <div class="pt-3">
                                                                        <input class="upload-image" data-target="image-result" id="change-avatar" type="file" max="1" hidden><label for="change-avatar" class="btn btn-md btn-primary">Change</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-note mt-3">
                                                                Set the category thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Status</label>
                                                            <div class="form-control-wrap">
                                                                <select class="js-select" data-search="true" data-sort="false">
                                                                    <option value="">
                                                                        Select an option
                                                                    </option>
                                                                    <option value="1" selected>
                                                                        Published
                                                                    </option>
                                                                    <option value="2">
                                                                        Draft
                                                                    </option>
                                                                    <option value="3">
                                                                        Scheduled
                                                                    </option>
                                                                    <option value="4">
                                                                        Inactive
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('admin-assets/assets/js/libs/editors/quill.js') }}"></script>
        <script src="{{ asset('admin-assets/assets/js/editors/quill.js') }}"></script>
    @endpush
