

@extends('admin.layouts.app')
@section('title','Dashboard')

@section('content')
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <h2>Dashboard
                    <small>Welcome to Admin Panel</small>
                </h2>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">Overview</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <h3 class="m-b-0">{{ $stats['appointments'] }}</h3>
                        <p class="text-muted m-b-0">Total Appointments</p>
                        <small>Today: {{ $stats['today_appointments'] }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <h3 class="m-b-0">{{ $stats['contact_messages'] }}</h3>
                        <p class="text-muted m-b-0">Contact Messages</p>
                        <small>Today: {{ $stats['today_contacts'] }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <h3 class="m-b-0">{{ $stats['services'] }}</h3>
                        <p class="text-muted m-b-0">Services</p>
                        <small>Live content on website</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <h3 class="m-b-0">{{ $stats['patient_guides'] }}</h3>
                        <p class="text-muted m-b-0">Patient Guides</p>
                        <small>Total Users: {{ $stats['users'] }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Recent</strong> Appointments</h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Phone</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                                
                          @php
                         
    $appointments = $recentAppointments ?? [];
@endphp

@if(count($appointments) > 0)
    @foreach($appointments as $item)
    
        <tr>
            <td>{{ $item->id ?? '-' }}</td>
            <td>{{ $item->name ?? '-' }}</td>
            <td>
                <span class="badge badge-{{ ($item->type ?? '') === 'analysis' ? 'info' : 'primary' }}">
                    {{ ucfirst($item->type ?? 'N/A') }}
                </span>
            </td>
            <td>{{ $item->phone ?? '-' }}</td>
<td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M, h:i A') : '-' }}</td>        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">No appointments found.</td>
    </tr>
@endif

 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Recent</strong> Contact Messages</h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentContacts as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->message), 45) }}</td>
                                    <td>{{ $item->created_at?->format('d M, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No contact messages found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header"><h2><strong>Quick</strong> Actions</h2></div>
                    <div class="body">
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary btn-round m-r-10 m-b-10">View Appointments</a>
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-info btn-round m-r-10 m-b-10">View Contact Messages</a>
                        <a href="{{ route('admin.site.services') }}" class="btn btn-warning btn-round m-r-10 m-b-10">Manage Services</a>
                        <a href="{{ route('admin.patient-guides.index') }}" class="btn btn-success btn-round m-r-10 m-b-10">Manage Patient Guides</a>
                        <a href="{{ route('admin.site.contact') }}" class="btn btn-default btn-round m-b-10">Contact Page Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
