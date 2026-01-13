@extends('admin.layouts.app')

@section('title', 'Test Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Test Permission Helper Functions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Helper Functions Test:</h4>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>hasPermission('manage-users'):</strong>
                                    <span class="badge {{ hasPermission('manage-users') ? 'badge-success' : 'badge-danger' }}">
                                        {{ hasPermission('manage-users') ? '✅ TRUE' : '❌ FALSE' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>hasRole('admin'):</strong>
                                    <span class="badge {{ hasRole('admin') ? 'badge-success' : 'badge-danger' }}">
                                        {{ hasRole('admin') ? '✅ TRUE' : '❌ FALSE' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>isAdmin():</strong>
                                    <span class="badge {{ isAdmin() ? 'badge-success' : 'badge-danger' }}">
                                        {{ isAdmin() ? '✅ TRUE' : '❌ FALSE' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>isSuperAdmin():</strong>
                                    <span class="badge {{ isSuperAdmin() ? 'badge-success' : 'badge-danger' }}">
                                        {{ isSuperAdmin() ? '✅ TRUE' : '❌ FALSE' }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h4>Current User Info:</h4>
                            @if(auth()->check())
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                        <p><strong>Status:</strong> {{ auth()->user()->status }}</p>
                                        <p><strong>Roles:</strong> {{ auth()->user()->roles->pluck('name')->join(', ') }}</p>
                                        <p><strong>Permissions:</strong> {{ auth()->user()->roles->pluck('permissions')->flatten()->pluck('name')->unique()->join(', ') }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    ❌ No user logged in
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h4>Blade Directives Test:</h4>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card {{ isAdmin() ? 'border-success' : 'border-danger' }}">
                                        <div class="card-header">
                                            <h5>@isAdmin Directive</h5>
                                        </div>
                                        <div class="card-body">
                                            @isAdmin
                                                <p class="text-success">✅ You can see this because you are admin!</p>
                                            @else
                                                <p class="text-danger">❌ You cannot see admin content</p>
                                            @endisAdmin
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card {{ isSuperAdmin() ? 'border-success' : 'border-danger' }}">
                                        <div class="card-header">
                                            <h5>@isSuperAdmin Directive</h5>
                                        </div>
                                        <div class="card-body">
                                            @isSuperAdmin
                                                <p class="text-success">✅ You can see this because you are super admin!</p>
                                            @else
                                                <p class="text-danger">❌ Only super admin can see this</p>
                                            @endisSuperAdmin
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card {{ hasPermission('manage-users') ? 'border-success' : 'border-danger' }}">
                                        <div class="card-header">
                                            <h5>@hasPermission Directive</h5>
                                        </div>
                                        <div class="card-body">
                                            @hasPermission('manage-users')
                                                <p class="text-success">✅ You have 'manage-users' permission!</p>
                                            @else
                                                <p class="text-danger">❌ You don't have 'manage-users' permission</p>
                                            @endhasPermission
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card {{ hasRole('admin') ? 'border-success' : 'border-danger' }}">
                                        <div class="card-header">
                                            <h5>@hasRole Directive</h5>
                                        </div>
                                        <div class="card-body">
                                            @hasRole('admin')
                                                <p class="text-success">✅ You have 'admin' role!</p>
                                            @else
                                                <p class="text-danger">❌ You don't have 'admin' role</p>
                                            @endhasRole
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        <h5>🎯 Next Steps:</h5>
                        <ol>
                            <li>Update sidebar menu in <code>resources/views/admin/layouts/app.blade.php</code> with permission checks</li>
                            <li>Add permission checks to buttons and forms in your views</li>
                            <li>Test with different user roles to ensure proper access control</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection