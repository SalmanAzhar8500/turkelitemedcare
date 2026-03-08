@extends('admin.layouts.app')
@section('title', 'Appointments')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix mb-3">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card"><div class="body"><h6>Total Requests</h6><h3>{{ $stats['total'] }}</h3></div></div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card"><div class="body"><h6>Hair Analysis</h6><h3>{{ $stats['analysis'] }}</h3></div></div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card"><div class="body"><h6>Bookings</h6><h3>{{ $stats['booking'] }}</h3></div></div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header"><h2><strong>Appointments</strong> List</h2></div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Service</th>
                                <th>Guide</th>
                                <th>Date</th>
                                <th>Message</th>
                                <th>Submitted</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($appointments as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><span class="badge badge-{{ $item->type === 'analysis' ? 'info' : 'primary' }}">{{ ucfirst($item->type) }}</span></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ optional($item->service)->name }}</td>
                                    <td>{{ optional($item->patientGuide)->name }}</td>
                                    <td>{{ $item->appointment_date }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->message), 80) }}</td>
                                    <td>{{ $item->created_at?->format('d M Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center">No appointments found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $appointments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
