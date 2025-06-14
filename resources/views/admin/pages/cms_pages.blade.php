@extends('admin.layout.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">CMS Pages</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">CMS Pages</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if(session('success_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong></strong> {{ Session::get('success_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">CMS Pages</h3>
                            <a href="{{ url('admin/add-edit-cms-page') }}" style="max-width:150px; float:right;" class="btn btn-primary">Add CMS Page</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="cmspages" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>URL</th>
                                        <th>Created On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cms_pages as $page)
                                    <tr>
                                        <td>{{ $page['id'] }}</td>
                                        <td>{{ $page['title'] }}</td>
                                        <td>{{ $page['url'] }}</td>
                                        <td>{{ date("F j, Y, g:i a", strtotime($page['created_at'])) }}</td>
                                        <td>
                                            @if($pagesModule['edit_access'] == 1 || $pagesModule['full_access'] == 1)
                                                <!-- Add CMS Page status dynamically -->
                                                @if($page['status'] == 1)
                                                <a class='updateCmsPageStatus' id="page-{{ $page['id'] }}"
                                                    data-page-id="{{ $page['id'] }}" style='color:#3f6ed3' href="javascript:void(0)">
                                                    <i class="fas fa-toggle-on" data-status="Active"></i>
                                                </a>
                                                @else
                                                <a class='updateCmsPageStatus' id="page-{{ $page['id'] }}"
                                                    data-page-id="{{ $page['id'] }}" style="color:grey" href="javascript:void(0)">
                                                    <i class="fas fa-toggle-off" data-status="Inactive"></i>
                                                </a>
                                                @endif
                                            &nbsp;&nbsp;
                                            @endif

                                            @if($pagesModule['edit_access'] == 1 || $pagesModule['full_access'] == 1)
                                                <!-- Edit CMS Page -->
                                                <a href="{{ url('admin/add-edit-cms-page/'.$page['id']) }}"><i class='fas fa-edit' style='color:#3f6ed3'></i></a>
                                                &nbsp; &nbsp;
                                            @endif

                                            @if($pagesModule['full_access'] == 1)
                                                <!-- Delete CMS Page -->
                                                <!-- <a href="{{ url('admin/delete-cms-page/'.$page['id']) }}" class="confirmDelete" 
                                                name="CMS Page" title="Delete CMS PAge"><i class='fas fa-trash' style='color:#3f6ed3'></i></a> -->
                                                <a class="confirmDelete" name="CMS Page" title="Delete CMS Page" 
                                                href="javascript:void(0)" record="cms-page" recordid="{{ $page['id'] }}"
                                                ><i class='fas fa-trash' style='color:#3f6ed3'></i></a>
                                            @endif
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection