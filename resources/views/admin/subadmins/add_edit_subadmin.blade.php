@extends('admin.layout.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title }}</h1>
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
                            <form name="subadminForm" id="subadminForm" action="{{ empty($subadmindata['id']) ? url('admin/add-edit-subadmin') : url('admin/add-edit-subadmin/' . $subadmindata['id']) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group col-md-6">
                                        <label for="name">Name*</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name', $subadmindata['name'] ?? '') }}" autocomplete="off">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="mobile">Mobile no*</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile" value="{{ old('mobile', $subadmindata['mobile'] ?? '') }}" autocomplete="off">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" {{ !empty($subadmindata['id']) ? 'disabled' : '' }} class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email', $subadmindata['email'] ?? '') }}" autocomplete="off">
                                        <style>
                                            input[disabled] {
                                                color: #666666 !important;
                                            }
                                        </style>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="{{ old('password', $subadmindata['password'] ?? '') }}" autocomplete="off">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="image">Image</label>
                                        <input type="file" class="form-control" name="image" id="image">
                                        @if(!empty($subadmindata['image']))
                                        <a href="{{ url('admin/images/photos/'.$subadmindata['image']) }}" target="_blank">View Photo</a>
                                        <input type="hidden" name="current_image" value="{{ $subadmindata['image'] }}">
                                        @endif
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