@extends('admin.layout.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Subadmins</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(session('error_message'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong></strong> {{ Session::get('error_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            @if(session('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong></strong> {{ Session::get('success_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            <form name="subadminForm" id="subadminForm" action="{{ url('admin/update-role/'.$id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="subadmin_id" value="{{ $id }}">
                                @if (!empty($subadminRoles))
                                    @foreach($subadminRoles as $role)
                                        @if($role['module'] == 'cms_pages')
                                            @if($role['view_access'] == 1)
                                                @php $viewCMSPages = "checked" @endphp
                                            @else
                                                @php $viewCMSPages = "" @endphp
                                            @endif
                                        @endif

                                        @if($role['module'] == 'cms_pages')
                                            @if($role['edit_access'] == 1)
                                                @php $editCMSPages = "checked" @endphp
                                            @else
                                                @php $editCMSPages = "" @endphp
                                            @endif
                                        @endif

                                        @if($role['module'] == 'cms_pages')
                                            @if($role['full_access'] == 1)
                                                @php $fullCMSPages = "checked" @endphp
                                            @else
                                                @php $fullCMSPages = "" @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                                <div class="card-body">
                                    
                                    <div class="form-group col-md-6">
                                        <label for="mobile">CMS Pages : &nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <input type="checkbox" name="cms_pages[view]" value="1" 
                                        @if(isset($viewCMSPages)) {{ $viewCMSPages }} @endif>View Access&nbsp;&nbsp;&nbsp;&nbsp;
                                        
                                        <input type="checkbox" name="cms_pages[edit]" value="1"
                                        @if(isset($editCMSPages)) {{ $editCMSPages }} @endif>View/Edit Access&nbsp;&nbsp;&nbsp;&nbsp; 
                                        
                                        <input type="checkbox" name="cms_pages[full]" value="1"
                                        @if(isset($fullCMSPages)) {{ $fullCMSPages }} @endif>Full Access&nbsp;&nbsp;&nbsp;&nbsp;

                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="form-group col-md-6">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">

                </div>
            </div>
            <!-- /.card -->
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection